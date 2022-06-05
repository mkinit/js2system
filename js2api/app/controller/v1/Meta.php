<?php
namespace app\controller\v1;
use app\common\Api;
use app\common\model\Meta as MetaModel;

class Meta extends Api {
	//所有自定义字段键
	public function key() {
		$data = MetaModel::column('key');
		return $this->response(array_values(array_unique($data)));
	}
}