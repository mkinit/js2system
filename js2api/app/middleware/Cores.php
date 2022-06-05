<?php

namespace app\middleware;

//允许跨域中间件
class Cores {
	public function handle($request, \Closure $next) {
		$response = $next($request);
		$origin = $request->header('Origin', '');

		//OPTIONS请求返回204请求
		if ($request->method(true) === 'OPTIONS') {
			$response->code(204);
		}
		$response->header([
			'Access-Control-Allow-Origin' => $origin,
			'Access-Control-Allow-Methods' => '*',
			'Access-Control-Allow-Headers' => '*',
			'Access-Control-Allow-Credentials' => 'true',
		]);
		return $response;
	}
}