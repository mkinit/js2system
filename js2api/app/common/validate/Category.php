<?php
namespace app\common\validate;

use think\Validate;

class Category extends Validate {
	protected $rule = [
		'name' => 'require|unique:category',
	];

	protected $message = [
		'name.require' => '分类名称不能为空',
		'name.unique' => '分类名称已存在',
	];
}