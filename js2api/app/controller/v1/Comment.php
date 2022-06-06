<?php

namespace app\controller\v1;

use app\common\Api;
use app\common\Email as EmailTool;
use app\common\model\Comment as CommentModel;
use app\common\model\User as UserModel;
use app\common\Tools;
use app\common\validate\Comment as CommentValidate;
use think\exception\ValidateException;

class Comment extends Api {
	/*
		所有评论列表（GET）
		@param	$post_id	Number	文章id
		@param	$page		Number	分页
		@param	$page_size	Number	分页条数
	*/
	public function index($post_id = 0) {
		if ($post_id) {
			$comments = CommentModel::with(['author', 'post'])->hidden(['post.content'])->where('post_id', $post_id)->page($this->page, $this->pageSize)->order('time_add desc')->select();
		} else {
			$comments = CommentModel::with(['author', 'post'])->hidden(['post.content'])->page($this->page, $this->pageSize)->order('time_add desc')->select();
		}
		$pagination = [
			'total' => $post_id ? CommentModel::where('post_id', $post_id)->count() : CommentModel::count(),
		];
		$this->setPagination($pagination);
		return $this->response(Tools::makeTree($comments->toArray()));
	}

	/*
		添加评论（POST）
		@param	$post_id		Number	文章id*
		@param	$nickname		String	昵称*
		@param	$email			String	电子邮箱*
		@param	$content		String	评论内容*
		@param	$parent_id		Number	父级评论ID
		@param	$reply_to_id	Number	被回复评论ID
		@param	$link			String	链接
	*/
	public function add($post_id = null, $nickname = '', $email = '', $content = '', $parent_id = 0, $reply_to_id = 0, $link = '') {
		//防止频繁请求，API客户端必须携带cookie:PHPSESSID=f53ba7a7090cf68a134fb6a88d02fdcf
		$session_id = cookie('PHPSESSID');
		$session_path = runtime_path() . '/session/sess_' . $session_id; //session文件路径
		if (!is_file($session_path)) {
			return $this->response(null, '非法访问', 400);
		}

		if (session('comment_time')) {
			$comment_time = session('comment_time');
			$next = 60 - (time() - $comment_time);
			//一分钟后才能再次提交留言
			if ((time() - $comment_time) < 60) {
				return $this->response(null, $next . '秒后才能重新提交留言', 400);
			}
		}

		try {
			validate(CommentValidate::class)->check([
				'post_id' => $post_id,
				'nickname' => $nickname,
				'email' => $email,
				'content' => $content,
				'parent_id' => $parent_id,
			]);
		} catch (ValidateException $e) {
			// 验证失败 输出错误信息
			return $this->response(null, $e->getError(), 400);
		}

		$comment = new CommentModel;
		$comment->post_id = $post_id;
		$comment->user_id = $this->user ? $this->user->id : 0;
		$comment->nickname = $nickname;
		$comment->email = $email;
		$comment->content = $content;
		$comment->parent_id = $parent_id;
		$comment->reply_to_id = $reply_to_id;
		$comment->link = $link;
		if ($comment->save()) {
			session('comment_time', time());
			if (!$parent_id && !$reply_to_id) {
				//如果不是回复的邮件，通知管理员
				$result = EmailTool::send(UserModel::find(1)['email'], '来自《' . $this->setting_info['site_name'] . '》 的评论通知', '收到用户的评论：' . $content . '，请登录管理后台查看！');
			} else {
				//回复的邮件通知被回复的用户，需要定位被回复的用户邮件，评论楼层，网站地址
				$reply_to_email = CommentModel::where('id', $reply_to_id)->column('email')[0];
				EmailTool::send($reply_to_email, '收到来自《' . $this->setting_info['site_name'] . '》 的评论回复通知', '收到来自《' . $this->setting_info['site_name'] . '》 的评论回复：' . $content . '！https://' . $this->setting_info['site'] . '/post/' . $post_id . '.html#comment-' . $comment->id);
			}
			return $this->response($comment);
		}
	}
}
