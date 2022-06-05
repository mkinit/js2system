<?php
namespace app\common\validate;

use think\Validate;

class Link extends Validate {
	protected $rule = [
		'name' => 'require',
		'link' => 'require',
	];

	protected $message = [
		'name.require' => '链接名称不能为空',
		'link.require' => '链接地址不能为空',
	];
}