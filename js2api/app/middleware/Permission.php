<?php
namespace app\middleware;

use app\common\Api;
use app\common\Tools;
use think\Request;

//需要权限验证的拦截中间件
class Permission extends Api {
	public $controller = '';
	public $action = '';
	public function handle(Request $request, \Closure $next) {
		$header = $request->header();
		if (empty($header['token'])) {
			return $this->response(null, '请登陆', 401);
		}

		$result = Tools::tokenValidate($header['token']);
		if (empty($result->aud)) {
			return $this->response(null, $result, 401);
		}

		$user = $this->user;
		//如果不是系统管理员需要判断权限
		if ($user['id'] != 1) {
			$controller = explode('.', $request->controller())[1]; //获取当前控制器（过滤版本前缀）
			$action = $request->action(); //获取当前方法
			$action_list = $user['role']['action_list']->toArray();
			$role_actions_filter = array_values(array_filter($action_list, function ($item) use ($controller, $action) {
				return $item['controller'] == $this->controller && $item['action'] == $this->action;
			})); //过滤出是否有当前路由的权限
			if (count($role_actions_filter) > 0) {
				return $next($request);
			} else {
				return $this->response(null, '权限不足', 403);
			}
		}
		return $next($request);
	}
}