<?php
namespace app\common\validate;

use think\Validate;

class User extends Validate {
	protected $rule = [
		'nickname' => 'unique:user',
		'username' => 'unique:user|min:4|regex:^[A-Za-z][\w]+', //用户名规则：必填、最少4位数
		'password' => 'min:6',
		'email' => 'unique:user|email',
		'phone' => 'unique:user',
	];

	protected $message = [
		'nickname.unique' => '昵称已被使用',
		'username.unique' => '用户名已被注册',
		'username.min' => '用户名最少需要4位数',
		'username.regex' => '用户名只能是英文字母或数字，并且是字母开头',
		'password.min' => '密码最少需要6位数',
		'email.unique' => '邮箱已被注册',
		'email.email' => '邮箱格式不正确',
		'phone.unique' => '号码已被注册',
	];
}