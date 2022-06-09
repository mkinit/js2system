<?php
namespace app\middleware;

use app\common\Api;
use think\Request;

//需要登陆的拦截中间件
class Login extends Api {
	public function handle(Request $request, \Closure $next) {
		if (!$this->user) {
			return $this->response(null, '请登陆', 401);
		}
		return $next($request);
	}
}