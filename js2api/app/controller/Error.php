<?php
namespace app\controller;
use app\controller\BaseController;
use think\facade\View;

class Error extends BaseController {
	public function __call($method, $args) {
		return View('theme/' . $this->setting_data['theme'] . '/404.html', [], 404);
	}
}