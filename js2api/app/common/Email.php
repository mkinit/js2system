<?php
namespace app\common;

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use app\common\Tools;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use think\facade\Config;

//Load Composer's autoloader
//require '/vendor/autoload.php';

class Email {
	//邮件发送基本方法
	public static function send($email, $title, $content) {
		//Create an instance; passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
			//$mail->SMTPDebug = SMTP::DEBUG_SERVER; //日志输出
			$mail->CharSet = 'UTF-8'; //防止中文乱码
			$mail->isSMTP(); //Send using SMTP
			$mail->Host = Config::get('key.email_host'); //Set the SMTP server to send through
			$mail->SMTPAuth = true; //Enable SMTP authentication
			$mail->Username = Config::get('key.email_username'); //SMTP username
			$mail->Password = Config::get('key.email_password'); //SMTP password
			$mail->SMTPSecure = 'ssl'; //网易邮箱必须使用这个
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
			$mail->Port = Config::get('key.email_port'); //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

			//Recipients
			$mail->setFrom(Config::get('key.email_username'), Tools::getSiteName()); //发件人
			$mail->addAddress($email); //收件人

			//附件
			//$mail->addAttachment('/var/tmp/file.tar.gz'); //发送附件

			//Content
			$mail->isHTML(true); //使用 HTML 格式
			$mail->Subject = $title; //邮件标题
			$mail->Body = $content; //邮件内容

			$mail->send();
			return true;
		} catch (Exception $e) {
			return $mail->ErrorInfo;
		}
	}
}