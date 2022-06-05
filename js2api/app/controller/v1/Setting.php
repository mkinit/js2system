<?php
namespace app\controller\v1;

use app\common\Api;
use app\common\model\Setting as SettingModel;
use think\Request;

class Setting extends Api {

	public function index() {
		return $this->response($this->setting_info);
	}

	/*
		修改系统信息（PUT）
		@data site_name 网站名称
		@data site_keywords 网站关键词
		@data site_description 网站描述
		@data icp 备案号
		@data thumbnail_small 缩略图小
		@data thumbnail_medium 缩略图中
		@data thumbnail_large 缩略图大
		@data upload_limit 上传文件大小限制
		@data theme 网站模板（目录名称，前后端分离模式不可用）
		@data ba 百度统计ID
		@data product 产品中心分类
		@data news 新闻中心分类

	*/
	public function update(Request $request) {

		$put_data = $request->put();
		foreach ($put_data as $key => $value) {
			SettingModel::update(['value' => $value], ['key' => $key]);
		}

		return $this->response($this->setting_info);
	}
}