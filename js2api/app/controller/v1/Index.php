<?php
namespace app\controller\v1;

use app\common\Api;
use think\Request;

class Index extends Api {
	//测试乱七八糟的数据
	public function index(Request $request, $ids) {
		//return json(get_class_methods("app\\controller\\v1\\Post"));//获取控制器所有方法
		//return json(get_class()); //获取当前控制器（完整路径）
		//return $request->controller(); //获取当前控制器
		//return $request->action(); //获取当前方法
		//dd($ids); //可以直接使用形参接收任意参数，参数名和前端传递的参数名一致，位置可变，但是请求方式要和路由对应，如果路由设置了只能用get方法，那么无法接收前端body参数
		//$route_list = \think\facade\Route::getRuleList();//路由列表

		return $this->response($ids);

	}

	//清除缓存
	public function clearCache() {
		$dir = root_path() . 'runtime/temp';
		$dh = opendir($dir);
		while ($file = readdir($dh)) {
			if ($file != "." && $file != "..") {
				$fullpath = $dir . "/" . $file;
				@unlink($fullpath);
			}
		}
		return $this->response();
	}
}
