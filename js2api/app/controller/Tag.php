<?php
namespace app\controller;

use app\common\model\Tag as TagModel;
use app\controller\BaseController;
use think\facade\Db;
use think\facade\View;
use think\Request;

class Tag extends BaseController {
	public function index(Request $request, $id) {
		$tag = TagModel::find($id); //当前分类
		if (!$tag) {
			return $this->empty();
		}

		//当前标签内容列表
		$posts = Db::view('tag', '*')
			->view('post_tag', '*', 'post_tag.tag_id=tag.id')
			->view('post', '*', 'post.id=post_tag.post_id')
			->where([
				'type' => 'post',
				'tag.id' => $id,
			])
			->orderRaw('time_add desc')
			->paginate(10);
		// 获取分页显示
		$page = $posts->render();
		$posts = $posts->toArray()['data'];
		foreach ($posts as $key => $post) {
			$posts[$key]['content'] = strip_tags($post['content']); //过滤内容
			$category = array_filter($this->categories, function ($item) use ($post) {return $item['id'] == $post['category_id'];});
			$posts[$key]['category'] = array_values($category)[0]; //当前分类
		}

		//面包屑导航
		$crumbs = [
			[
				'name' => '首页',
				'link' => '/',
			],
		];
		array_push($crumbs, [
			'name' => '标签：“' . $tag['name'] . '” 的相关结果',
			'link' => '',
		]);

		//全局变量赋值
		View::assign([
			'title' => '标签：“' . $tag['name'] . '” 的相关结果',
			'tag' => $tag, //当前标签
			'posts' => $posts, //文章列表
			'page' => $page, //分页
			'crumbs' => $crumbs, //面包屑导航
		]);

		// 模板输出
		return View::fetch();
	}
}
