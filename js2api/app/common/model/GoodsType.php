<?php
namespace app\common\model;
use think\Model;

class GoodsType extends Model {
	//关联规格
	public function spec() {
		return $this->hasMany(GoodsSpec::class, 'type_id');
	}
}