<?php
namespace app\common\validate;

use think\Validate;

class Post extends Validate {
	protected $rule = [
		'title' => 'require',
	];

	protected $message = [
		'title.require' => '标题不能为空',
	];
}