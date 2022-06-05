<?php
namespace app\controller\v1;

use app\common\Api;
use app\common\model\Action as ActionModel;

class Permission extends Api {
	//权限列表
	public function action() {
		$action = ActionModel::select();
		$res_data = [];
		foreach ($action as $key => $value) {
			$group = strtolower($value['controller']);
			$res_data[$group][] = $value;
		}
		return $this->response($res_data);
	}
}