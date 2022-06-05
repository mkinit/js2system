<?php
namespace app\controller;

use app\common\model\User as UserModel;
use app\common\validate\User as UserValidate;
use app\controller\BaseController;
use think\exception\ValidateException;
use think\facade\View;

class Register extends BaseController {
	public function index($email = '', $verify_code = '', $username = '', $password = '') {
		//判断是否已经登录
		if ($this->user) {
			return redirect('/user.html');
		}

		//设置来路
		$server = $this->request->server();
		if (!empty($server['HTTP_REFERER']) && !stristr($server['HTTP_REFERER'], "register")) {
			$referer = $server['HTTP_REFERER']; //注册后返回来路
			session('referer', $referer);
		} else {
			session('referer', '/');
		}

		$method = strtolower($this->request->method()); //请求方法

		//模板变量
		$response = [
			'title' => '用户注册',
			'email' => '',
			'verify_code' => '',
			'username' => '',
			'password' => '',
			'message' => '',
		];

		//注册
		if ($method == 'post') {
			$response['email'] = $email;
			$response['verify_code'] = $verify_code;
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
			if (!$email) {
				$response['message'] = '电子邮箱不能为空';
				View::assign($response);
				return View::fetch();
			}
			if (!$verify_code) {
				$response['message'] = '验证码不能为空';
				View::assign($response);
				return View::fetch();
			}

			try {
				validate(UserValidate::class)->check([
					'username' => $username,
					'password' => $password,
					'email' => $email,
				]);
			} catch (ValidateException $e) {
				// 验证失败 输出错误信息
				$response['message'] = $e->getError();
				View::assign($response);
				return View::fetch();
			}

			if (session('verify_code')) {
				$session_code = session('verify_code');
				$session_code_time = session('verify_code_time');
				$session_email = session('verify_email');
				if ($session_email != $email) {
					$response['message'] = '当前电子邮箱与接收验证码的邮箱不匹配';
					View::assign($response);
					return View::fetch();
				}
				if ((time() - $session_code_time) > 3000) {
					session('verify_code', null);
					session('verify_email', null);
					session('verify_code_time', null);
					$response['message'] = '验证码已过期';
					View::assign($response);
					return View::fetch();
				}
				if ($session_code != $verify_code) {
					$response['message'] = '验证码错误';
					View::assign($response);
					return View::fetch();
				}
			} else {
				$response['message'] = '请先获取验证码';
				View::assign($response);
				return View::fetch();
			}

			//创建用户
			$new_user = new UserModel;
			$new_user->username = $username;
			$new_user->nickname = $username;
			$new_user->password = md5($password);
			$new_user->email = $email;
			$result = $new_user->save();

			//注册成功
			if ($result) {
				session('verify_code', null);
				session('verify_code_time', null);
				$user = UserModel::find($new_user->id);
				$user->append(['username', 'email', 'phone']);
				$user['role'] = $user->role;
				$user['role']['action_list'] = $user->role->action;
				session('user', $user); //写入session
				return redirect(session('referer'));
			}

		}

		//全局变量赋值
		View::assign($response);
		// 模板输出
		return View::fetch();
	}
}
