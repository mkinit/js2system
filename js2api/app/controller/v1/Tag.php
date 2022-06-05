<?php

namespace app\controller\v1;

use app\common\Api;
use app\common\model\PostTag as PostTagModel;
use app\common\model\Tag as TagModel;
use app\common\validate\tag as tagValidate;
use think\exception\ValidateException;
use think\Request;

class Tag extends Api {
	/*
		标签列表
	*/
	public function index() {
		$tags = TagModel::page($this->page, $this->pageSize)->order('id desc')->select()->toArray();
		$pagination = [
			'total' => TagModel::count(),
		];
		$this->setPagination($pagination);
		return $this->response($tags);
	}

	/*
		标签详情（GET）
		@param $id 标签ID*
	*/
	public function read($id) {
		$tag = TagModel::find($id);
		if (!$tag) {
			return $this->response(null, '没有该标签', 400);
		}
		return $this->response($tag);
	}

	/*
		更新标签详情（PUT）
		@param	$id				String	标签ID*
		@data	$name			String	标签名称*
		@data	$keywords		String	关键词
		@data	$description	String	描述
	*/
	public function update($id, $name = '', $keywords = '', $description = '') {
		$tag = TagModel::find($id);

		if (!$tag) {
			return $this->response(null, '没有该标签', 400);
		}

		if (!$name) {
			return $this->response(null, '标签名不能为空', 400);
		}

		if ($name && strtolower($tag['name']) !== strtolower($name)) {
			try {
				validate(tagValidate::class)->check([
					'name' => $name,
				]);
			} catch (ValidateException $e) {
				// 验证失败 输出错误信息
				return $this->response(null, $e->getError(), 400);
			}
		}

		$tag->name = $name;
		$tag->keywords = $keywords;
		$tag->description = $description;

		$result = $tag->save();
		if ($result) {
			return $this->response($tag);
		}
	}

	/*
		删除标签（DELETE）
		@param $id 标签ID*
	*/
	public function delete($ids) {
		$ids_arr = explode(',', $ids);
		TagModel::destroy($ids_arr);
		PostTagModel::where('tag_id', 'in', $ids_arr)->delete();
		return $this->response();
	}

	/*
		添加标签（POST）
		@data name 标签名称*
		@data keywords 关键词
		@data description 描述
	*/
	public function add(Request $request) {
		$post_data = $request->post();
		if (empty($post_data['name'])) {
			return $this->response(null, '缺少标签名称', 400);
		}
		try {
			validate(tagValidate::class)->check([
				'name' => $post_data['name'],
			]);
		} catch (ValidateException $e) {
			// 验证失败 输出错误信息
			return $this->response(null, $e->getError(), 400);
		}

		$tag = new TagModel;
		$tag->name = $post_data['name'];

		$optional = ['keywords', 'description']; //选填属性

		foreach ($optional as $value) {
			if (!empty($post_data[$value])) {
				$tag->$value = $post_data[$value];
			}
		}
		$result = $tag->save();
		if ($result) {
			$tag['id'] = (int) $tag['id'];
			return $this->response($tag);
		}
	}
}
