<?php
namespace app\middleware;

use app\common\Api;
use app\common\model\Role as RoleModel;
use app\common\model\User as UserModel;
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

		try {
			$result = Tools::tokenValidate($header['token']);
		} catch (\Throwable $e) {
			return $this->response(null, $e->getMessage(), 401);
		}

		$user = UserModel::find($result->aud);
		//如果不是系统管理员需要判断权限
		if ($user['id'] != 1) {
			$this->controller = explode('.', $request->controller())[1]; //获取当前控制器（过滤版本前缀）
			$this->action = $request->action(); //获取当前方法
			$role_id = $user->role['id']; //获取角色ID
			$role = RoleModel::find($role_id); //查找角色
			$role_actions = json_decode(json_encode($role->action), true); //获取角色所有权限
			$role_actions_filter = array_values(array_filter($role_actions, 'self::filterControllerAction')); //过滤出是否有当前路由的权限
			if (count($role_actions_filter) > 0) {
				return $next($request);
			} else {
				return $this->response(null, '权限不足', 403);
			}
		}
		return $next($request);
	}

	//权限过滤器
	public function filterControllerAction($item) {
		return $item['controller'] == $this->controller && $item['action'] == $this->action;
	}

}