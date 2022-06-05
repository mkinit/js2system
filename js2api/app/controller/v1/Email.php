<?php
namespace app\controller\v1;

use app\common\Api;
use app\common\Email as EmailTool;
use app\common\Tools;
use think\Request;

class Email extends Api {
	//用户注册验证码
	public function verify($email, Request $request) {
		//防止频繁请求，API客户端必须携带cookie:PHPSESSID=f53ba7a7090cf68a134fb6a88d02fdcf
		$session_id = cookie('PHPSESSID');
		$session_path = runtime_path() . '/session/sess_' . $session_id; //session文件路径
		if (!is_file($session_path)) {
			return $this->response(null, '非法访问', 400);
		}

		$verify_code = Tools::verifyCode(6);
		if (session('verify_code')) {
			$verify_code_time = session('verify_code_time');
			$next = 60 - (time() - $verify_code_time);
			//一分钟后才能再次发送验证码
			if ((time() - $verify_code_time) < 60) {
				return $this->response(null, $next . '秒后才能重新发送验证码', 400);
			}
		}
		session('verify_email', $email);
		session('verify_code', $verify_code);
		session('verify_code_time', time());
		$content_html = '来自《' . $this->setting_info['site_name'] . '》的验证码：<h3>' . $verify_code . '</h3>';
		$result = EmailTool::send($email, '来自' . $this->setting_info['site_name'] . '的验证码', $content_html);
		if ($result === true) {
			return $this->response(null, '验证码发送成功');
		} else {
			return $this->response(null, $result, 400);
		}
	}
}