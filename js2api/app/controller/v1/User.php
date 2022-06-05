<?php
namespace app\controller\v1;

use app\common\Api;
use app\common\model\Action as ActionModel;
use app\common\model\User as UserModel;
use app\common\Tools; //自定义工具类
use app\common\validate\User as UserValidate;
use think\exception\ValidateException;
use think\Request;

class User extends Api {

	/*
		获取用户列表（GET）
	*/
	public function index() {
		//系统管理员角色不显示
		$users = UserModel::with(['role'])->where('id', '>', 1)->page($this->page, $this->pageSize)->order('time_add desc')->select();
		foreach ($users as $user) {
			$user['role']['action_list'] = $user->role->action;
		}
		$pagination = [
			'total' => UserModel::where('role_id', '<>', 1)->count(),
		];
		$this->setPagination($pagination);
		$users->append(['username', 'email', 'phone']);
		return $this->response($users);
	}

	/*
		获取单个用户信息（GET）
	*/
	public function read($id) {
		$user = UserModel::with(['role'])->find($id);
		if ($user) {
			if ($user->id == 1) {
				$user['role']['action_list'] = ActionModel::select();
			} else {
				$user['role']['action_list'] = $user->role->action;
			}
			$user->append(['username', 'email', 'phone']);
			return $this->response($user);
		} else {
			return $this->response(null, '没有这条数据', 400);
		}
	}

	/*
		修改单个用户信息（PUT）
		@param 	$nickname 	String 	 昵称*
		@param 	$role_id 	Number 	 角色ID*
	*/
	public function edit($id, $nickname = '', $role_id = 0) {
		$user = UserModel::find($id);

		if (!$nickname) {
			return $this->response(null, '昵称不能为空', 400);
		}

		if (!$user) {
			return $this->response(null, '没有这这个用户', 400);
		}

		if ($user['id'] == 1) {
			return $this->response(null, '不允许修改系统管理员的信息', 400);
		}

		if (!$role_id) {
			return $this->response(null, '角色ID不能为空', 400);
		}

		if ($role_id == 1) {
			return $this->response(null, '用户角色不允许设置为系统管理员', 400);
		}

		if ($nickname != $user['nickname']) {
			try {
				validate(UserValidate::class)->check([
					'nickname' => $nickname,
				]);
			} catch (ValidateException $e) {
				return $this->response(null, $e->getMessage(), 400);
			}
		}

		$user->nickname = $nickname;
		$user->role_id = $role_id;

		$result = $user->save();
		if ($result) {
			$user['role'] = $user->role;
			$user['role']['action_list'] = $user->role->action;
			$user->append(['username', 'email', 'phone']);
			return $this->response($user);
		}
	}

	/*
		删除用户（DELETE）
	*/
	public function delete($ids) {
		$ids_arr = explode(',', $ids);
		//过滤ID为1的用户
		$ids_arr_filter = array_values(array_filter($ids_arr, function ($id) {
			return $id != 1;
		}));
		UserModel::destroy($ids_arr_filter);
		return $this->response();
	}

	/*
		用户注册（POST）
		@param	$username		String	用户名*
		@param	$password		String	密码*
		@param	$email			String	电子邮箱*
		@param	$verify_code	String	验证码*

	*/
	public function register($username = '', $password = '', $email = '', $verify_code = '') {
		if (!$username) {
			return $this->response(null, '用户名不能为空', 400);
		}
		if (!$password) {
			return $this->response(null, '密码不能为空', 400);
		}
		if (!$email) {
			return $this->response(null, '电子邮箱不能为空', 400);
		}
		if (!$verify_code) {
			return $this->response(null, '验证码不能为空', 400);
		}

		try {
			validate(UserValidate::class)->check([
				'username' => $username,
				'password' => $password,
				'email' => $email,
			]);
		} catch (ValidateException $e) {
			// 验证失败 输出错误信息
			return $this->response(null, $e->getError(), 400);
		}

		if (session('verify_code')) {
			$session_code = session('verify_code');
			$session_code_time = session('verify_code_time');
			$session_email = session('verify_email');
			if ($session_email != $email) {
				return $this->response(null, '当前电子邮箱与接收验证码的邮箱不匹配', 400);
			}
			if ((time() - $session_code_time) > 3000) {
				session('verify_code', null);
				session('verify_email', null);
				session('verify_code_time', null);
				return $this->response(null, '验证码已过期', 400);
			}
			if ($session_code != $verify_code) {
				return $this->response(null, '验证码错误', 400);
			}
		} else {
			return $this->response(null, '请先获取验证码', 400);
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
			$user = UserModel::find($new_user->id);
			$token = Tools::token($new_user->id); //生成token
			$user['role'] = $user->role;
			$user['role']['action_list'] = $user->role->action;
			$user['token'] = $token;
			session('verify_code', null);
			session('verify_code_time', null);
			$user->append(['username', 'email', 'phone']);
			return $this->response($user);
		}
	}

	/*
		用户登陆（POST）
		@param	$username	String	用户名*
		@param	$password	String	密码*
	*/
	public function login($username = '', $password = '') {
		if (!$username) {
			return $this->response(null, '用户名不能为空', 400);
		}
		if (!$password) {
			return $this->response(null, '密码不能为空', 400);
		}
		//查找用户
		$user = UserModel::where('username', $username)->find();

		//如果用户名存在
		if (!$user) {
			return $this->response(null, '用户名不存在', 400);
		}
		if ($user['password'] === md5($password)) {
			$token = Tools::token($user->id); //生成token
			$user['token'] = $token;
			$user['role'] = $user->role;
			if ($user->id == 1) {
				$user['role']['action_list'] = ActionModel::select();
			} else {
				$user['role']['action_list'] = $user->role->action;
			}
			$user->append(['username', 'email', 'phone']);
			return $this->response($user);
		} else {
			return $this->response(null, '密码错误', 400);
		}

	}

	/*
		获取当前用户信息（GET）
	 */
	public function info(Request $request) {
		$header = $request->header();
		$user = $this->user;
		$user['role'] = $user->role;
		$user['role']['action_list'] = $user->role->action;
		$user['token'] = $header['token'];
		$user->append(['username', 'email', 'phone']);
		return $this->response($user);
	}

	/*
		修改当前用户信息（PUT）
		$nickname 昵称
		$password_old 原密码
		$password_new 新密码
	*/
	public function update(Request $request) {
		$header = $request->header();
		$put_data = $request->put();
		$user = $this->user;

		//修改密码
		if (!empty($put_data['password_old'])) {
			if (md5($put_data['password_old']) !== $user->password) {
				return $this->response(null, '原密码错误', 400);
			}
			if (empty($put_data['password_new'])) {
				return $this->response(null, '请输入新密码', 400);
			}
			try {
				validate(UserValidate::class)->check([
					'password' => $put_data['password_new'],
				]);
			} catch (ValidateException $e) {
				// 验证失败 输出错误信息
				return $this->response(null, $e->getError(), 400);
			}
			$user['password'] = md5($put_data['password_new']);
		}

		if (!empty($put_data['nickname']) && $put_data['nickname'] != $user['nickname']) {
			try {
				validate(UserValidate::class)->check([
					'nickname' => $put_data['nickname'],
				]);

			} catch (ValidateException $e) {
				return $this->response(null, $e->getMessage(), 400);
			}
			$user->nickname = $put_data['nickname'];
		}

		$user->save();
		$token = Tools::token($user->id); //生成token
		$user['role'] = $user->role;
		$user['role']['action_list'] = $user->role->action;
		$user['token'] = $token;
		$user->append(['username', 'email', 'phone']);
		return $this->response($user);
	}

}
