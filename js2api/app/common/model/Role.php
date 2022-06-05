<?php
namespace app\common\model;

use think\Model;

class Role extends Model {
	protected $hidden = [
		'action',
	];

	//添加时间
	public function getTimeAddAttr($value) {
		return date('Y-m-d H:i', strtotime($value));
	}

	//修改时间
	public function getTimeModifyAttr($value) {
		if (!$value) {
			return null;
		}

		return date('Y-m-d H:i', strtotime($value));
	}

	//关联权限列表
	public function action() {
		//官方：关联模型，中间模型，外                键，中间表关联键，当前模型主键，中间模型主键
		//测试：关联模型，中间模型，中间表的当前模型关联键，关联模型主键，当前模型主键，中间表的关联模型关联键
		return $this->hasManyThrough(Action::class, RoleAction::class, 'role_id', 'id', 'id', 'action_id');
	}
}