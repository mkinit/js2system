<?php
namespace app\middleware;

use app\common\Api;
use app\common\model\User as UserModel;
use app\common\Tools;
use think\Request;

//需要管理员的拦截中间件
class Admin extends Api {
	public function handle(Request $request, \Closure $next) {
		$header = $request->header();
		if (empty($header['token'])) {
			return $this->response(null, '请登陆', 401);
		}

		try {
			$result = Tools::tokenValidate($header['token']);
		} catch (\Throwable $e) {
			return $this->response(null, $e->getMessage(), 401);
		}

		$user = UserModel::find($result->aud);
		if ($user['id'] != 1) {
			return $this->response(null, '需要系统管理员权限', 403);
		}
		$this->user = $user;
		return $next($request);
	}
}