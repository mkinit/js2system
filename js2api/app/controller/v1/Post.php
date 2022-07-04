<?php
namespace app\controller\v1;

use app\common\Api;
use app\common\model\Comment as CommentModel;
use app\common\model\Meta as MetaModel;
use app\common\model\Post as PostModel;
use app\common\model\PostTag as PostTagModel;
use app\common\model\Tag as TagModel;
use app\common\Tools;
use app\common\validate\Post as PostValidate;
use think\exception\ValidateException;

class Post extends Api {
	/*
		内容列表（GET）
		@param	$category_id	Number	分类ID
		@param	$type			String	类型，默认为非单页的内容，可选，post为文章，single为单页
		@param	$trashed		0 | 1		1：回收站；0：非回收站，默认非回收站（软删除）
		$param	$top			0 | 1		1：置顶；0：非置顶；不传返回所有，默认所有
		@param	$page			Number	分页
		@param	$page_size		Number	分页条数
	*/
	public function index($type = 'post', $category_id = 0, $trashed = 0, $top = null) {
		$where = [
			['type', '=', $type],
		];

		if ($category_id) {
			//需要获取子分类的内容
			$post_category_id = $category_id;
			$category_child = Tools::getAllChildren($this->categories, $post_category_id); //当前分类的子分类
			$category_child_ids = array_column($category_child, 'id'); //当前分类的子分类ID
			$where[] = ['category_id', 'in', array_merge($category_child_ids, [$post_category_id])];
		}

		if (!is_null($top)) {
			$where[] = ['top', '=', $top];
		}

		if ($trashed) {
			$posts = PostModel::onlyTrashed()->with(['author', 'meta', 'tags', 'category'])->where($where)->page($this->page, $this->pageSize)->order('time_delete desc')->select();
		} else {
			$posts = PostModel::with(['author', 'meta', 'tags', 'category'])->where($where)
				->order('id desc')
				->page($this->page, $this->pageSize)
				->select();
		}

		foreach ($posts as $post) {
			$post->content = strip_tags($post->content);
			if ($post->gallery) {
				$post->gallery = explode(',', $post->gallery);
			} else {
				$post->gallery = [];
			}
		}

		$total = 0;
		if ($trashed) {
			$total = PostModel::onlyTrashed()->where($where)->count();
		} else {
			$total = PostModel::where($where)->count();
		}

		$pagination = [
			'total' => $total,
		];
		$this->setPagination($pagination);
		return $this->response($posts);
	}

	/*
		内容详情（GET）
		@param $id 内容ID
	*/
	public function read($id) {
		$post = PostModel::with(['author', 'meta' => function ($query) {$query->order('id', 'asc');}, 'tags', 'category'])->where('id', $id)->find();
		if ($post) {
			PostModel::update(['view' => $post['view'] + 1], ['id' => $id]);
			if ($post->gallery) {
				$post->gallery = explode(',', $post->gallery);
			} else {
				$post->gallery = [];
			}
			return $this->response($post);
		} else {
			return $this->response(null, '没有这条数据', 404);
		}
	}

	/*
		添加内容（POST）
		@param	$title			String		标题*
		@param	$category_id	Number		分类ID*
		@param	$type			String		类型 post | single 默认为post
		@param	$content		String		内容
		@param	$thumbnail		String		缩略图地址
		@param	$gallery		Array		图集地址
		@param	$cover			String		页面顶部封面图片地址
		@param	$top			0 | 1		置顶
		@param	$time_add		DATETIME	日期时间（2022-13-14 20:13:14）
		@param	$meta			Array		自定义属性
		@param	$tags			Array		标签
	*/
	public function add($title = '', $category_id = 0, $type = 'post', $content = '', $thumbnail = '', $gallery = [], $cover = '', $top = 0, $time_add = null, $meta = null, $tags = null) {

		try {
			validate(PostValidate::class)->check([
				'title' => $title,
			]);
		} catch (ValidateException $e) {
			// 验证失败 输出错误信息
			return $this->response(null, $e->getError(), 400);
		}

		if ($type == 'post') {
			if (!$category_id) {
				return $this->response(null, '分类不能为空', 400);
			}
		}

		if (!is_null($meta) && !is_array($meta)) {
			return $this->response(null, '自定义属性必须是数组', 400);
		}
		if (!is_null($tags) && !is_array($tags)) {
			return $this->response(null, '标签必须是数组', 400);
		}

		//创建文章
		$post = new PostModel;
		$post->type = $type;
		$post->top = $top;
		$post->title = $title;
		$post->cover = $cover;
		$post->content = $content;
		$post->thumbnail = $thumbnail;
		$post->gallery = implode(',', $gallery);
		$post->category_id = $category_id;
		$post->author_id = $this->user['id'];

		if ($time_add) {
			$post->time_add = $time_add;
		}

		$result = $post->save();
		if ($result) {
			//自定义属性
			if ($meta) {
				foreach ($meta as $item) {
					$post->meta()->save([
						'key' => $item['key'],
						'value' => $item['value'],
					]);
				}
			}

			//标签
			$post->tags()->detach();
			if ($tags) {
				$tag_all = TagModel::select()->toArray(); //所有标签

				foreach ($tags as $tag_new) {
					$index = array_search($tag_new, array_column($tag_all, 'name'));
					if ($index === false) {
						//标签不存在，创建标签，写入中间表
						$new_tag = new TagModel;
						$new_tag['name'] = $tag_new;
						$new_tag->save();
						$post->tags()->attach($new_tag['id']);
					} else {
						//标签已存在，写入中间表
						$post->tags()->attach($tag_all[$index]['id']);
					}
				}
			}

			$post->author;
			$post->meta;
			$post->tags;
			$post->category;
			$post->id = (int) $post->id;
			return $this->response($post);
		}
	}

	/*
		更新内容详情（PUT）
		@param	$title			String		标题*
		@param	$category_id	Number		分类ID*
		@param	$type			String		类型 post | single 默认为post
		@param	$content		String		内容
		@param	$thumbnail		String		缩略图地址
		@param	$gallery		Array		图集地址
		@param	$cover			String		页面顶部封面图片地址
		@param	$top			0 | 1		置顶
		@param	$time_add		DATETIME	日期时间（2022-13-14 20:13:14）
		@param	$meta			Array		自定义属性
		@param	$tags			Array		标签
	*/
	public function update($id, $title = '', $category_id = 0, $type = 'post', $content = '', $thumbnail = '', $gallery = [], $cover = '', $top = 0, $time_add = null, $meta = null, $tags = null) {
		$post = PostModel::find($id);
		if (!$post) {
			return $this->response(null, '没有该内容', 404);
		}

		try {
			validate(PostValidate::class)->check([
				'title' => $title,
			]);
		} catch (ValidateException $e) {
			// 验证失败 输出错误信息
			return $this->response(null, $e->getError(), 400);
		}

		if ($type == 'post') {
			if (!$category_id) {
				return $this->response(null, '分类不能为空', 400);
			}
		}

		if (!is_null($meta) && !is_array($meta)) {
			return $this->response(null, '自定义属性必须是数组', 400);
		}
		if (!is_null($tags) && !is_array($tags)) {
			return $this->response(null, '标签必须是数组', 400);
		}

		$post->type = $type;
		$post->top = $top;
		$post->title = $title;
		$post->cover = $cover;
		$post->content = $content;
		$post->thumbnail = $thumbnail;
		$post->gallery = implode(',', $gallery);
		$post->category_id = $category_id;

		$result = $post->save();
		if ($result) {
			//自定义属性
			MetaModel::where('post_id', $id)->delete(); //删除所有自定义属性后重新添加
			if ($meta) {
				foreach ($meta as $item) {
					$post->meta()->save([
						'key' => $item['key'],
						'value' => $item['value'],
					]);
				}
			}

			//标签
			$post->tags()->detach();
			if ($tags) {
				$tag_all = TagModel::select()->toArray(); //所有标签

				foreach ($tags as $tag_new) {
					$index = array_search($tag_new, array_column($tag_all, 'name'));
					if ($index === false) {
						//标签不存在，创建标签，写入中间表
						$new_tag = new TagModel;
						$new_tag['name'] = $tag_new;
						$new_tag->save();
						$post->tags()->attach($new_tag['id']);
					} else {
						//标签已存在，写入中间表
						$post->tags()->attach($tag_all[$index]['id']);
					}
				}
			}
			$post->author;
			$post->meta;
			$post->tags;
			$post->category;
			return $this->response($post);
		}
	}

	/*
		删除内容（DELETE）
		@params force 0||1 真实删除，可选
	*/
	public function delete($ids = null, $force = 0) {
		if (is_null($ids) || !$ids) {
			return $this->response(null, '缺少内容ID', 400);
		}

		$ids_arr = explode(',', $ids);
		if (in_array(1, $ids_arr)) {
			return $this->response(null, '留言页面不能删除', 400);
		}
		if ($force) {
			PostModel::destroy($ids_arr, true);
			CommentModel::where('post_id', 'in', $ids)->delete(); //删除评论
			MetaModel::where('post_id', 'in', $ids)->delete(); //删除自定义属性
			PostTagModel::where('post_id', 'in', $ids)->delete(); //标签中间表

			return $this->response();
		} else {
			PostModel::destroy(explode(',', $ids));
			return $this->response();
		}
	}

	/*
		恢复内容（POST）
		@param	$ids	String	内容id，多个用英文逗号分隔
	*/
	public function restore($ids = null) {
		if (is_null($ids) || !$ids) {
			return $this->response(null, '缺少内容ID', 400);
		}
		$ids = explode(',', $ids);
		foreach ($ids as $key => $value) {
			$post = PostModel::onlyTrashed()->find($value);
			$post->restore();
		}
		return $this->response();
	}

	/*
		内容置顶
		@param 	$id		Number	内容id
		@param 	$top	0|1		置顶
	*/
	public function setTop($id, $top) {
		$post = PostModel::where('id', $id)->find();
		if (!$post) {
			return $this->response(null, '没有该内容', 400);
		}
		$post->top = $top;
		$post->save();
		return $this->response();
	}
}