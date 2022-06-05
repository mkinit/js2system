<?php
namespace app\controller\v1;

use app\common\Api;
use app\common\model\Action as ActionModel;
use app\common\model\RoleAction as RoleActionModel;
use app\common\model\Role as RoleModel;
use app\common\model\User as UserModel;
use app\common\validate\Role as RoleValidate;
use think\exception\ValidateException;

class Role extends Api {
	//角色列表
	public function index() {
		$roles = RoleModel::where('id', '>', 1)->select();
		foreach ($roles as $role) {
			$role['action_list'] = $role->action;
		}
		return $this->response($roles);
	}

	//角色信息
	public function read($id) {
		$role = RoleModel::where('id', $id)->find();
		if ($id == 1) {
			//管理员角色添加所有权限数据
			$role['action_list'] = ActionModel::select();
		} else {
			$role['action_list'] = $role->action;
		}

		return $this->response($role);
	}

	/*
		添加角色（POST）
		@param	$name			String	角色名称*
		@param	$description	String	角色描述*
		@param	$modify			0 | 1	是否可以修改角色权限，默认1
	*/
	public function add($name = '', $description = '', $modify = 1) {
		try {
			validate(RoleValidate::class)->check([
				'name' => $name,
				'description' => $description,
			]);
		} catch (ValidateException $e) {
			// 验证失败 输出错误信息
			return $this->response(null, $e->getError(), 400);
		}

		$role = new RoleModel;

		$role['name'] = $name;
		$role['description'] = $description;
		$role['modify'] = $modify;

		$result = $role->save();

		if ($result) {
			$role->id = (int) $role->id;
			return $this->response($role);
		}
	}

	/*
		删除角色（DELETE）
		@params	ids	String	角色id，英文逗号分隔
	*/
	public function delete($ids) {
		$ids_arr = explode(',', $ids);

		if (in_array(1, $ids_arr) || in_array(2, $ids_arr)) {
			return $this->response(null, '不允许删除系统默认的角色', 400);
		}

		foreach ($ids_arr as $id) {
			$users = UserModel::whereIn('role_id', $ids)->select();
			if (count($users)) {
				return $this->response(null, '不允许删除已经配置给用户的角色', 400);
			}
		}

		$result = RoleModel::destroy($ids_arr);

		$role_actions = RoleActionModel::where('role_id', $id)->select();
		if (count($role_actions)) {
			RoleActionModel::where('role_id', $id)->delete();
		}

		return $this->response();
	}

	/*
		修改角色信息（PUT）
		@param	$name			String	角色名称*
		@param	$description	String	角色描述*
	*/
	public function edit($id, $name = '', $description = '') {
		if ($id == 1 || $id == 2) {
			return $this->response(null, '不允许修改默认角色', 400);
		}

		$role = RoleModel::find($id);

		if (!$role) {
			return $this->response(null, '没有找到对应角色', 400);
		}

		if ($name != $role->name) {
			try {
				validate(RoleValidate::class)->check([
					'name' => $name,
					'description' => $description,
				]);
			} catch (ValidateException $e) {
				// 验证失败 输出错误信息
				return $this->response(null, $e->getError(), 400);
			}
		}

		$role['name'] = $name;
		$role['description'] = $description;
		$result = $role->save();

		if ($result) {
			return $this->response($role);
		}
	}

	/*
		角色授权
		@param	$id		String	角色ID
		@param	$action	String	权限ID，英文逗号分隔
	*/
	public function action($id, $action) {
		if ($id == 1 || $id == 2) {
			return $this->response(null, '不允许修改默认角色', 400);
		}
		$result = RoleActionModel::where('role_id', $id)->select();

		if (count($result)) {
			RoleActionModel::where('role_id', $id)->delete();
		}

		$action_arr = array_filter(explode(',', $action), function ($item) {
			return $item;
		});

		$role_action = new RoleActionModel;
		$action_list = [];
		foreach ($action_arr as $action_id) {
			$action_list[] = [
				'role_id' => $id,
				'action_id' => $action_id,
			];
		}
		$result = $role_action->saveAll($action_list);
		$result = array_map(function ($item) {
			$item['id'] = (int) $item['id'];
			$item['action_id'] = (int) $item['action_id'];
			return $item;
		}, $result->toArray());
		return $this->response($result);
	}
}