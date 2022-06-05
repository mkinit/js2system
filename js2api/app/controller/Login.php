<?php
namespace app\controller;

use app\common\model\User as UserModel;
use app\controller\BaseController;
use think\facade\View;

class Login extends BaseController {
	public function index($action = '', $username = '', $password = '') {
		if ($action == 'logout') {
			session('user', null);
			return redirect('/');
		}

		//判断是否已经登录
		if ($this->user) {
			return redirect('/user.html');
		}

		$method = strtolower($this->request->method()); //请求方法

		//模板变量
		$response = [
			'title' => '用户登录',
			'username' => '',
			'password' => '',
			'message' => '',
		];
		if ($method == 'post') {
			$response['username'] = $username;
			$response['password'] = $password;

			if (!$username) {
				$response['message'] = '用户名不能为空';
				View::assign($response);
				return View::fetch();
			}
			if (!$password) {
				$response['message'] = '密码不能为空';
				View::assign($response);
				return View::fetch();
			}

			//查找用户
			$user = UserModel::where('username', $username)->find();

			//如果用户名存在
			if (!$user) {
				$response['message'] = '用户名不存在';
				View::assign($response);
				return View::fetch();
			}

			if ($user['password'] !== md5($password)) {
				$response['message'] = '密码错误';
				View::assign($response);
				return View::fetch();
			}

			$user['role'] = $user->role;
			$user->append(['username', 'email', 'phone']);
			if ($user->id == 1) {
				$user['role']['action_list'] = ActionModel::select();
			} else {
				$user['role']['action_list'] = $user->role->action;
			}
			session('user', $user); //写入session
			return redirect(session('referer'));
		}

		//全局变量赋值
		View::assign($response);
		// 模板输出
		return View::fetch();
	}
}
