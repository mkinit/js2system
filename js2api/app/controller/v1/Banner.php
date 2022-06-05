<?php

namespace app\controller\v1;

use app\common\Api;
use app\common\model\Banner as BannerModel;

class Banner extends Api {
	/*
		轮播图列表
	*/
	public function index() {
		$banner_list = BannerModel::order('sort desc')->select();
		return $this->response($banner_list);
	}

	/*
		更新轮播图详情（PUT）
		@param	$id
		@param	$image_url	String	图片地址*
		@param	$link		String	跳转链接
		@param	$blank		Number	新窗口打开
		@param	$sort		Number	排序，倒序，需要模板控制器配合
	*/
	public function update($id, $image_url = '', $link = '', $blank = 0, $sort = 0) {
		$banner = BannerModel::find($id);

		if (!$banner) {
			return $this->response(null, '没有该轮播图', 400);
		}

		$banner->image_url = $image_url;
		$banner->link = $link;
		$banner->blank = $blank;
		$banner->sort = $sort;
		$result = $banner->save();

		if ($result) {
			return $this->response($banner);
		}
	}

	/*
		删除轮播图（DELETE）
	*/
	public function delete($ids) {
		$ids_arr = explode(',', $ids);
		BannerModel::destroy($ids_arr);
		return $this->response();
	}

	/*
		添加轮播图（POST）
		@param	$image_url	String	图片地址*
		@param	$link		String	跳转链接
		@param	$blank		Number	新窗口打开
		@param	$sort		Number	排序，倒序，需要模板控制器配合
	*/
	public function add($image_url = '', $link = '', $blank = 0, $sort = 0) {

		$banner = new BannerModel;

		$banner->image_url = $image_url;
		$banner->link = $link;
		$banner->blank = $blank;
		$banner->sort = $sort;
		$result = $banner->save();

		if ($result) {
			$banner->id = (int) $banner->id;
			return $this->response($banner);
		}
	}
}
