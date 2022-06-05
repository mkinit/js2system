<?php
namespace app\controller;

use app\common\model\Post as PostModel;
use app\controller\BaseController;
use think\facade\View;

class Index extends BaseController {
	public function index() {
		$posts = PostModel::with(['author', 'meta', 'tags', 'category'])->where([
			'type' => 'post',
		])
			->orderRaw('time_add desc')
			->paginate(10);

		foreach ($posts as $post) {
			$post->content = strip_tags($post->content); //过滤内容
		}

		// 获取分页显示
		$page = $posts->render();

		//全局变量赋值
		View::assign([
			'posts' => $posts, //文章列表
			'page' => $page, //分页
		]);

		// 模板输出
		return View::fetch();
	}
}
