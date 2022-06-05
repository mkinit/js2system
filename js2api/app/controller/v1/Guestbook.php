<?php
namespace app\controller\v1;

use app\common\Api;
use app\common\Email as EmailTool;
use app\common\model\Guestbook as GuestbookModel;
use app\common\model\User as UserModel;
use app\common\validate\Guestbook as GuestbookValidate;
use think\exception\ValidateException;

class Guestbook extends Api {
	/*
		留言列表（GET）
		@param	$page		Number	分页
		@param	$page_size	Number	分页条数
	*/
	public function index() {
		$get_data = $this->request->get();
		$guest_books = GuestbookModel::order('time_add desc')->select();
		$pagination = [
			'total' => GuestbookModel::count(),
		];
		$this->setPagination($pagination);
		return $this->response($guest_books);
	}

	/*
		添加留言
		@param	$name		String	名称*
		@param	$content	String	留言内容*
		@param	$email		String	电子邮箱
		@param	$phone		String	电话号码
	*/
	public function add($name = '', $content = '', $phone = '', $email = '') {
		//防止频繁请求，API客户端必须携带cookie:PHPSESSID=f53ba7a7090cf68a134fb6a88d02fdcf
		$session_id = cookie('PHPSESSID');
		$session_path = runtime_path() . '/session/sess_' . $session_id; //session文件路径
		if (!is_file($session_path)) {
			return $this->response(null, '非法访问', 400);
		}

		if (session('guestbook_time')) {
			$guestbook_time = session('guestbook_time');
			$next = 60 - (time() - $guestbook_time);
			//一分钟后才能再次提交留言
			if ((time() - $guestbook_time) < 60) {
				return $this->response(null, $next . '秒后才能重新提交留言', 400);
			}
		}

		try {
			validate(GuestbookValidate::class)->check([
				'name' => $name,
				'content' => $content,
			]);
		} catch (ValidateException $e) {
			// 验证失败 输出错误信息
			return $this->response(null, $e->getError(), 400);
		}

		$message = new GuestbookModel;

		$message->name = $name;
		$message->content = $content;
		$message->phone = $phone;
		$message->email = $email;
		$message->time_add = date("Y-m-d H:i");
		if ($message->save()) {
			session('guestbook_time', time());
			$message->id = (int) $message->id;
			//通知管理员
			EmailTool::send(UserModel::find(1)['email'], '来自《' . $this->setting_info['site_name'] . '》 的留言通知', '收到用户的留言，请登录管理后台查看！');
			return $this->response($message);
		}
	}
}