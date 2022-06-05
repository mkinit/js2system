<?php
namespace app\controller;

use app\common\model\Post as PostModel;
use app\common\Tools;
use app\controller\BaseController;
use think\facade\View;
use think\Request;

class Category extends BaseController {
	public function index(Request $request, $id) {
		$category = Tools::findCategory($this->categories, $id); //当前分类
		if (!$category) {
			return $this->empty();
		}
		$category_child = Tools::getAllChildren($this->categories, $id); //当前分类的子分类
		$category_child_ids = array_column($category_child, 'id'); //当前分类的子分类ID

		//当前分类内容列表
		$posts = PostModel::with(['author', 'meta', 'tags', 'category'])->where([
			'type' => 'post',
			'category_id' => [$id, ...$category_child_ids],
		])
			->orderRaw('time_add desc')
			->paginate(12);

		foreach ($posts as $post) {
			$post->content = strip_tags($post->content); //过滤内容
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
		if ($category['parent_id']) {
			$parent_category = Tools::findCategory($this->categories, $category['parent_id']);
			array_push($crumbs, [
				'name' => $parent_category['name'],
				'link' => '/category/' . $parent_category['id'],
			]);
		}
		array_push($crumbs, [
			'name' => $category['name'],
			'link' => '',
		]);

		//全局变量赋值
		View::assign([
			'title' => $category['name'],
			'category' => $category, //当前分类
			'category_child' => $category_child, //子分类
			'posts' => $posts, //文章列表
			'page' => $page, //分页
			'crumbs' => $crumbs, //面包屑导航
		]);

		// 模板输出
		if (in_array($category['id'], $this->product_cate_ids)) {
			return View::fetch('product'); //使用产品的模板
		}
		return View::fetch();

	}
}
