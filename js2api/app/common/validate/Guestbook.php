<?php
namespace app\common\validate;

use think\Validate;

class Guestbook extends Validate {
	protected $rule = [
		'name' => 'require',
		'content' => 'require',
	];

	protected $message = [
		'name.require' => '名称不能为空',
		'content.require' => '留言内容不能为空',
	];
}