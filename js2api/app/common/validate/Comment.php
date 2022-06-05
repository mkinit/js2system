<?php
namespace app\common\validate;

use think\Validate;

class Comment extends Validate {
	protected $rule = [
		'post_id' => 'require',
		'nickname' => 'require',
		'email' => 'require|email',
		'content' => 'require',
		'parent_id' => 'integer',
	];

	protected $message = [
		'post_id.require' => '文章ID不能为空',
		'nickname.require' => '昵称不能为空',
		'content.require' => '评论内容不能为空',
		'email.require' => '邮箱不能为空',
		'email.email' => '邮箱格式错误',
		'parent_id.integer' => '父级ID参数类型为整数',
	];
}