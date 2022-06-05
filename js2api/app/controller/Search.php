<?php
namespace app\controller;

use app\common\model\Post as PostModel;
use app\controller\BaseController;
use think\facade\View;
use think\Request;

//use app\common\Tools;

class Search extends BaseController {
	public function index(Request $request, $keywords = '') {
		if (empty($keywords)) {
			return View('theme/' . $this->theme . '/404.html', [], 404);
		}
		$keywords_arr = explode(' ', $keywords);
		$where = [];
		foreach ($keywords_arr as $key => $value) {
			array_push($where, ['title|content', 'like', '%' . $value . '%']);
		}

		//当前分类内容列表
		$posts = PostModel::with(['author', 'meta', 'tags', 'category'])->where($where)->order('time_add desc')->paginate([
			'list_rows' => 10,
			'query' => ['keywords' => $keywords],

		]);
		foreach ($posts as $post) {
			$post->content = strip_tags($post->content);
		}

		// 获取分页显示
		$page = $posts->render();

		//面包屑导航
		$crumbs = [
			[
				'name' => '首页',
				'link' => '/',
			],
		];
		array_push($crumbs, [
			'name' => '“' . $keywords . '” 的搜索结果',
			'link' => '',
		]);

		//全局变量赋值
		View::assign([
			'title' => '“' . $keywords . '” 的搜索结果',
			'posts' => $posts, //文章列表
			'page' => $page, //分页
			'crumbs' => $crumbs, //面包屑导航
		]);
		// 模板输出
		return View::fetch();
	}
}
