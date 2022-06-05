<?php
namespace app\common\model;
use think\Model;

class Goods extends Model {

	//关联商品类型
	public function type() {
		return $this->hasOne(GoodsType::class, 'id');
	}

	//关联SKU
	public function sku() {
		return $this->hasMany(GoodsSku::class);
	}
}