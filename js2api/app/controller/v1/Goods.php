<?php
namespace app\controller\v1;

use app\common\Api;
use app\common\model\Goods as GoodsModel;

class Goods extends Api {
	//商品列表
	public function index() {

	}

	//商品详情
	public function read($id) {
		/* 商品SKU数据结构
			[
				{
					"spec_id": 1,
					"spec_value_id": 1,
					"spec_value": "xs",
					"spec_value_text": "特小",
					"image": ""
				},
				{
					"spec_id": 2,
					"spec_value_id": 8,
					"spec_value": "红色",
					"spec_value_text": "中国红",
					"image": ""
				}
			]
		*/
		$goods = GoodsModel::find($id);
		$goods->type->spec;
		$goods->sku;
		//反序列化SKU数据
		foreach ($goods['sku'] as $sku) {
			$sku['spec_data'] = unserialize($sku['spec_data']);
		}
		$goods = $goods->toArray();

		foreach ($goods['type']['spec'] as $key => $spec) {
			//将SKU数据放到对应的规格数据里
			foreach ($goods['sku'] as $sku) {
				foreach ($sku['spec_data'] as $spec_data) {
					if ($spec['id'] == $spec_data['spec_id']) {

						$goods['type']['spec'][$key]['spec_data'][] = $spec_data;
					}
				}

			}
		}

		return $this->response($goods);
	}

	/*
		添加商品
		$name
		$category_id
		$code
		$multiple
		$type_id
		$market_price
		$price
		$stock
		SKU数据
		$spec_data
		$market_price
		$price
		$stock
		主图
		$gallery
		内容
		$content
	*/
	public function add(Request $request) {
		$post_data = $request->post();
	}
}