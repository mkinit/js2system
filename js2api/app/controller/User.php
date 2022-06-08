<?php
namespace app\controller;

use app\common\validate\User as UserValidate;
use app\controller\BaseController;
use think\facade\View;

class User extends BaseController {
	public function index($action = '', $nickname = '', $password_old = '', $password_new = '') {
		$user = $this->user;
		//判断是否已经登录
		if (!$user) {
			return redirect('/login.html');
		}

		//模板变量
		$response = [
			'title' => '用户资料',
			'message' => '',
		];

		//更新资料
		if ($action == 'update') {
			if ($nickname && $user['nickname'] != $nickname) {
				try {
					validate(UserValidate::class)->check([
						'nickname' => $nickname,
					]);
				} catch (ValidateException $e) {
					$response['message'] = $e->getMessage();
					View::assign($response);
					return View::fetch();
				}
			}

			$user->nickname = $nickname;

			//修改密码
			if (!empty($password_old)) {
				if (md5($password_old) !== $user->password) {
					$response['message'] = '原密码错误';
					View::assign($response);
					return View::fetch();
				}
				if (empty($password_new)) {
					$response['message'] = '请输入新密码';
					View::assign($response);
					return View::fetch();
				}
				try {
					validate(UserValidate::class)->check([
						'password' => $password_new,
					]);
				} catch (ValidateException $e) {
					// 验证失败 输出错误信息
					$response['message'] = $e->getError();
					View::assign($response);
					return View::fetch();
				}
				$user['password'] = md5($password_new);
			}

			$user->save();
			$response['message'] = '更新成功';
			View::assign($response);
			return View::fetch();
		}

		//全局变量赋值
		View::assign($response);
		// 模板输出
		return View::fetch();
	}
}
