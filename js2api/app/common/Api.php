<?php
/*
 * 统一处理接口数据的基类
 */

namespace app\common;
use app\common\model\Category as CategoryModel;
use app\common\model\Setting as SettingModel;
use app\common\model\User as UserModel;
use think\Request;

class Api {
	protected $page;
	protected $pageSize;
	protected $pagination; //分页信息
	protected $setting_info; //系统设置信息
	protected $thumbnail_size; //系统设置的缩略图尺寸
	protected $categories; //分类目录
	protected $user;
	public function __construct(Request $request) {
		$this->request = $request;
		$this->page = $request->get('page', 1);
		$this->pageSize = $request->get('page_size', 10);
		$this->getSettingInfo();
		$this->categories = CategoryModel::select();

		$header = $request->header();
		if (empty($header['token'])) {
			$this->user = session('user');
		} else {
			$result = Tools::tokenValidate($header['token']);
			if (!empty($result->aud)) {
				$user = UserModel::find($result->aud);
				$user['role'] = $user->role;
				$user['role']['action_list'] = $user->role->action;
				$user['token'] = $header['token'];
				$this->user = $user;
			}
		}

	}

	//系统设置信息
	protected function getSettingInfo() {
		//网站主题目录
		$theme_dir = scandir('theme');
		$themes = [];
		foreach ($theme_dir as $value) {
			if ($value != '.' && $value != '..' && is_dir('theme/' . $value)) {
				$themes[] = $value;
			}
		}

		$setting = SettingModel::select();
		$res_data = ['theme_dir' => $themes];
		$thumbnail = [];
		foreach ($setting as $key => $value) {
			$res_data[$value['key']] = preg_match('/^[\d]+$/', $value['value']) ? $value['value'] + 0 : $value['value'];
			if (preg_match('/thumbnail_/', $value['key'])) {
				$thumbnail[$value['key']] = (int) $value['value'];
			}
		}
		$this->thumbnail_size = $thumbnail;
		$this->setting_info = $res_data;
	}

	//设置分页信息
	protected function setPagination($data) {
		$data['current_page'] = (int) $this->page;
		$data['page_size'] = (int) $this->pageSize;
		$data['page_total'] = ceil($data['total'] / (int) $this->pageSize);
		$this->pagination = $data;
	}

	//响应数据
	protected function response($data = null, $msg = '请求成功', $code = 200) {
		switch ($msg) {
		case 'Wrong number of segments':
			$msg = '无法验证身份令牌';
			break;
		case 'Expired token':
			$msg = '身份验证过期，请重新登录';
			break;
		}
		$result = array(
			'code' => $code,
			'msg' => $msg,
			'data' => $data,
		);
		if ($this->pagination) {
			$result['pagination'] = $this->pagination;
		}
		return json($result, $code);
	}
}