<?php
namespace app\controller;

use app\controller\BaseController;
use think\facade\View;

class User extends BaseController {
	public function index() {

		//判断是否已经登录
		if (!$this->user) {
			return redirect('/login.html');
		}

		//全局变量赋值
		View::assign([
			'title' => '用户中心',
			'user' => $this->user,
		]);
		// 模板输出
		return View::fetch();
	}
}
