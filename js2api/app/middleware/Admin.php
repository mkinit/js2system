<?php
namespace app\middleware;

use app\common\Api;
use think\Request;

//需要管理员的拦截中间件
class Admin extends Api {
	public function handle(Request $request, \Closure $next) {
		if ($this->user['id'] != 1) {
			return $this->response(null, '需要系统管理员权限', 403);
		}
		return $next($request);
	}
}