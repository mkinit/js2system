<?php
namespace app\middleware;

use app\common\Api;
use app\common\model\User as UserModel;
use app\common\Tools;
use think\Request;

//需要登陆的拦截中间件
class Login extends Api {
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
		$this->user = $user;
		return $next($request);
	}
}