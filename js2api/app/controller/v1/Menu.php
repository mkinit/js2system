<?php

namespace app\controller\v1;

use app\common\Api;
use app\common\model\Menu as MenuModel;
use app\common\validate\Menu as MenuValidate;
use think\exception\ValidateException;

class Menu extends Api {

	public function index() {
		$menu = MenuModel::select();
		foreach ($menu as $key => $value) {
			$value['list'] = unserialize($value['list']);
		}
		return $this->response($menu);
	}

	/*
		添加菜单
		@data name* 菜单名称 String
	*/
	public function add($name = '') {
		try {
			validate(MenuValidate::class)->check([
				'name' => $name,
			]);
		} catch (ValidateException $e) {
			// 验证失败 输出错误信息
			return $this->response(null, $e->getError(), 400);
		}

		$menu = new MenuModel;
		$menu->name = $name;
		$menu->list = serialize([]);

		$result = $menu->save();
		if ($result) {
			$menu->list = unserialize($menu->list);
			$menu->id = (int) $menu->id;
			return $this->response($menu);
		}
	}

	/*
		删除菜单
	*/
	public function delete($id) {
		$menu = MenuModel::find($id);
		if ($menu) {
			if ($menu->delete()) {
				return $this->response();
			}
		} else {
			return $this->response(null, '没有这条数据', 400);
		}
	}

	/*
		更新菜单（PUT）
		@data	$list	Array	菜单列表
		{list: [{"label": "产品中心","link": 7,"type": "category","children": [{"label": "产品分类一","link": 12,"type": "category","children": []}]},{"label": "资讯中心","link": 8,"type": "category","children": [{"label": "公司新闻","link": 9,"type": "category","children": []}]},{"label": "关于我们","link": "3","type": "post","children": []}]}
	*/

	public function update($id, $list = []) {
		$menu = MenuModel::find($id);
		if (!$menu) {
			return $this->response(null, '没有该菜单', 404);
		}

		$menu->list = serialize($list);
		$result = $menu->save();
		if ($result) {
			$menu->list = unserialize($menu->list);
			return $this->response($menu);
		}
	}
}
