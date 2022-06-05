<?php
declare (strict_types = 1);

namespace app\controller\v1;

use app\common\Api;

class Error extends Api {
	public function index() {
		return $this->response(null, '资源不存在', 404);
	}
}