<?php
namespace app\common\validate;

use think\Validate;

class Menu extends Validate {
	protected $rule = [
		'name' => 'require|unique:menu',
	];

	protected $message = [
		'name.require' => '菜单名称不能为空',
		'name.unique' => '菜单名称已存在',
	];
}