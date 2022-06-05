<?php
namespace app\controller\v1;

use app\common\Api;
use app\common\model\Company as CompanyModel;
use think\Request;

class Company extends Api {

	public function index() {
		$company = CompanyModel::select();
		$res_data = [];
		foreach ($company as $key => $value) {
			$res_data[$value['key']] = $value['value'];

		}
		return $this->response($res_data);
	}

	/*
		修改系统信息（PUT）
		@param	$name			String	公司名称
		@param	$company_code	String	营业执照代码
		@param	$tel			String	电话
		@param	$fax			String	传真
		@param	$phone			String	手机
		@param	$address		String	地址
		@param	$zip			String	邮编
		@param	$email			String	邮箱
		@param	$wechat			String	微信二维码图片地址
		@param	$qq	String		qq

	*/
	public function update(Request $request) {

		$put_data = $request->put();

		foreach ($put_data as $key => $value) {
			CompanyModel::update(['value' => $value], ['key' => $key]);
		}

		$company = CompanyModel::select();
		$res_data = [];
		foreach ($company as $key => $value) {
			$res_data[$value['key']] = preg_match('/^[\d]+$/', $value['value']) ? $value['value'] + 0 : $value['value'];
		}
		return $this->response($res_data);
	}
}