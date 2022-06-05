<?php
namespace app\controller;

use app\controller\BaseController;
use think\facade\View;

class User extends BaseController {
	public function index() {
		//全局变量赋值
		View::assign([
			'title' => '用户中心',
			'user' => $this->user,
		]);
		// 模板输出
		return View::fetch();
	}
}
