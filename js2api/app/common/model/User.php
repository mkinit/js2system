<?php
namespace app\common\model;
use think\Model;
use think\model\concern\SoftDelete;

class User extends Model {
	use SoftDelete;

	protected $type = [
		'time_delete' => 'datetime',
	];

	protected $deleteTime = 'time_delete';

	protected $hidden = [
		'password', 'role_id', 'email', 'phone', 'username',
	];

	//注册时间
	public function getTimeAddAttr($value) {
		return date('Y-m-d H:i', strtotime($value));
	}

	//删除时间
	public function getTimeDeleteAttr($value) {
		if (!$value) {
			return null;
		}

		return date('Y-m-d H:i', strtotime($value));
	}

	//关联角色
	public function role() {
		return $this->hasOne(Role::class, 'id', 'role_id');
	}

}