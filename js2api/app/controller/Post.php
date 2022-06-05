<?php
namespace app\controller;

use app\common\model\Comment as CommentModel;
use app\common\model\Post as PostModel;
use app\common\Tools;
use app\controller\BaseController;
use think\facade\View;

class Post extends BaseController {
	public function index($id) {
		$post = PostModel::with(['author', 'meta', 'tags', 'category'])->find($id);
		if (!$post) {
			return $this->empty();
		}
		$post['view'] += 1;
		$post->save();

		$recommends = [];
		if ($post['type'] == 'post') {
			$recommends = PostModel::with(['author', 'meta', 'tags', 'category'])->where([
				['id', '<>', $id],
				['category_id', '=', $post['category_id']],
			])->orderRaw('time_add desc')->select();
		}

		$comments = CommentModel::with(['author'])->where('post_id', $id)->order('time_add desc')->select()->toArray();
		foreach ($comments as $key => $comment) {
			if ($comment['reply_to_id']) {
				$comments[$key]['reply_nickname'] = array_values(array_filter($comments, function ($item) use ($comment) {
					return $item['id'] == $comment['reply_to_id'];
				}))[0]['nickname'];
			}
		}
		$comments = Tools::makeTree($comments);

		//面包屑导航
		$crumbs = [
			[
				'name' => '首页',
				'link' => '/',
			],
		];
		if ($post['category']) {
			array_push($crumbs, [
				'name' => $post['category']['name'],
				'link' => '/category/' . $post['category']['id'],
			]);
		}
		array_push($crumbs, [
			'name' => $post['title'],
			'link' => '',
		]);

		//全局变量赋值
		View::assign([
			'title' => $post['title'],
			'post' => $post, //内容
			'recommends' => $recommends, //推荐内容
			'comments' => $comments, //评论
			'crumbs' => $crumbs, //面包屑导航
		]);

		// 模板输出
		if ($id == 1) {
			return View::fetch('guestbook');
		}
		return View::fetch();
	}
}
