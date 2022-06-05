<?php
declare (strict_types = 1);

namespace app\controller;
use app\common\model\Banner as BannerModel;
use app\common\model\Category as CategoryModel;
use app\common\model\Company as CompanyModel;
use app\common\model\Link as LinkModel;
use app\common\model\Menu as MenuModel;
use app\common\model\Setting as SettingModel;
use app\common\Tools;
use think\App;
use think\facade\View;

/**
 * 控制器基础类
 */
class BaseController {
	protected $app;
	protected $request;
	protected $categories; //所有分类
	protected $setting_data; //系统设置信息
	protected $theme = 'default'; //默认主题目录
	protected $product_cate_ids; //属于产品的分类ID
	protected $user; //登录用户的信息

	public function __construct(App $app) {
		$this->app = $app;
		$this->request = $this->app->request;
		$this->categories = CategoryModel::select()->toArray();
		$this->user = session('user');

		if (!session('referer')) {
			session('referer', '/');
		}

		//设置来路
		$server = $this->request->server();
		if (!empty($server['HTTP_REFERER']) && !stristr($server['HTTP_REFERER'], 'register') && !stristr($server['HTTP_REFERER'], 'login')) {
			session('referer', $server['HTTP_REFERER']);
		}

		//处理网站设置信息
		$setting = SettingModel::select();
		foreach ($setting as $key => $value) {
			$this->setting_data[$value['key']] = preg_match('/^[\d]+$/', $value['value']) ? ($value['value'] + 0) : $value['value'];
		}

		$product_cate_id = $this->setting_data['product'];
		//获取产品的所有子分类
		$product_child_cate = Tools::getAllChildren($this->categories, $product_cate_id);
		$this->product_cate_ids = array_merge([$product_cate_id], array_column($product_child_cate, 'id'));

		$news_cate_id = $this->setting_data['news'];
		//获取新闻的所有分类详情
		$news_child_cate = Tools::getAllChildren($this->categories, $news_cate_id);

		//设置主题
		if ($this->setting_data['theme'] && is_dir(public_path() . '/theme/' . $this->setting_data['theme'])) {
			$this->theme = $this->setting_data['theme'];
		}

		//公司信息
		$company = CompanyModel::select();
		$company_data = [];
		foreach ($company as $key => $value) {
			$company_data[$value['key']] = preg_match('/^[\d]+$/', $value['value']) ? $value['value'] + 0 : $value['value'];
		}

		//菜单
		$menu_list = MenuModel::select()->toArray();
		foreach ($menu_list as $key => $menu) {
			$menu_list_arr = unserialize($menu['list']);
			$menu_list_arr = $this->menuHandle($menu_list_arr);
			$menu_list[$key]['list'] = $menu_list_arr;
		}

		//轮播图
		$banners = BannerModel::order('sort asc')->select();

		//友情链接
		$links = LinkModel::select();
		//全局变量赋值
		View::assign([
			'user' => $this->user,
			'controller_action' => $this->request->controller() . '-' . $this->request->action(), //当前控制器-方法
			'setting' => $this->setting_data, //网站设置
			'company' => $company_data, //公司设置
			'menu_list' => $menu_list, //菜单
			'banners' => $banners, //轮播图
			'links' => $links, //友情链接
			'categories' => $this->categories, //内容分类
			'product_child_cate' => $product_child_cate, //产品子分类
			'news_child_cate' => $news_child_cate, //新闻子分类
		]);
	}

	protected function empty() {
		return View('theme/' . $this->setting_data['theme'] . '/404.html', [], 404);
	}

	public function __call($method, $args) {
		return $this->empty();
	}

	//菜单处理函数
	protected function menuHandle($arr) {
		return array_map(function ($item) {
			$link = $item['type'] == 'custom' ? $item['link'] : '/' . $item['type'] . '/' . $item['link'] . ($item['type'] == 'category' ? '' : '.html');
			$children = [];
			if (count($item['children'])) {
				$children = self::menuHandle($item['children']);
			}

			return [
				'label' => $item['label'],
				'link' => $link,
				'type' => $item['type'],
				'children' => $children,
			];
		}, $arr);
	}

}
