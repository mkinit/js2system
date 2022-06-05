<?php
namespace app\common\validate;

use think\Validate;

class Role extends Validate {
	protected $rule = [
		'name' => 'unique:role|require',
		'description' => 'require',
	];

	protected $message = [
		'name.unique' => '已存在相同名称的角色',
		'name.require' => '角色名称必须填写',
		'description.require' => '角色描述必须填写',
	];
}