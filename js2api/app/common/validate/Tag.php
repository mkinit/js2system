<?php
namespace app\common\validate;

use think\Validate;

class Tag extends Validate {
	protected $rule = [
		'name' => 'unique:tag',
	];

	protected $message = [
		'name.unique' => '标签名称已存在',
	];
}