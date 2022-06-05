<?php

namespace app\controller\v1;
use app\common\model\Action as ActionModel;
use think\facade\Db;

//初始化权限表
class ActionTableInit {
	public function index() {
		//清空权限表数据
		Db::name('action')->delete(true);

		//重置权限表id
		$prefix = config('database.connections.mysql.prefix');
		Db::query('ALTER TABLE ' . $prefix . 'action DROP id');
		Db::query('OPTIMIZE TABLE ' . $prefix . 'action');
		Db::query('ALTER TABLE ' . $prefix . 'action ADD id int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');

		//记录所有权限方法
		$new_action = new ActionModel;
		$list = [];
		$route_list = \think\facade\Route::getRuleList();
		foreach ($route_list as $key => $value) {
			if (!empty($value['option']['action_name'])) {
				$controller_action = explode('/', $value['route']);
				array_push($list, [
					'name' => $value['option']['action_name'],
					'controller' => explode('.', $controller_action[0])[1],
					'action' => $controller_action[1],
				]);
			}
		}
		$new_action->saveAll($list);
		return json('权限表初始化完成');
	}
}
