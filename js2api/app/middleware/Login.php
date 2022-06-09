<?php
namespace app\middleware;

use app\common\Api;
use app\common\Tools;
use think\Request;

//需要登陆的拦截中间件
class Login extends Api {
	public function handle(Request $request, \Closure $next) {
		$header = $request->header();
		if (empty($header['token'])) {
			return $this->response(null, '请登陆', 401);
		}

		$result = Tools::tokenValidate($header['token']);
		if (empty($result->aud)) {
			return $this->response(null, $result, 401);
		}

		return $next($request);
	}
}