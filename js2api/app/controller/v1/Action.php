<?php
namespace app\controller\v1;

use app\common\Api;
use app\common\model\Action as ActionModel;

class Action extends Api {
	//动作列表
	public function index() {
		$actions = ActionModel::select();
		$res_data = [];
		foreach ($actions as $action) {
			$group = strtolower($action['controller']); //按控制器分组
			$group_action = explode('-', $action['name']);
			$action['name'] = $group_action[1];
			$res_data[$group]['group'] = $group_action[0];
			$res_data[$group]['action'][] = $action;
		}
		return $this->response($res_data);
	}
}