<?php
namespace app\common\model;

use think\Model;

class Link extends Model {
	//添加时间
	public function getTimeAddAttr($value) {
		return date('Y-m-d H:i', strtotime($value));
	}
}