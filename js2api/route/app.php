<?php

use app\middleware\Admin; //需要验证权限
use app\middleware\Login; //需要系统管理员
use app\middleware\Permission; //需要登陆
use think\facade\Route;

//前端页面路由
Route::group('', function () {
	Route::get('category/:id', 'Category/index')->option(['ext' => '']); //分类页
	Route::get('tag/:id', 'Tag/index')->option(['ext' => '']); //标签页
	Route::get('post/:id', 'Post/index')->option(['ext' => 'html']); //详情页
	Route::get('search', 'Search/index'); //搜索页
});

/*
 ** 接口路由
 ** 需要权限控制的路由需要定义action_name作为中文描述，模块名称-权限名称
 */
//API路由
Route::group('js2-json/v1', function () {
	Route::miss('v1.Error/index'); //404
	Route::get('api', 'v1.Index/index'); //所有路由
	Route::get('clear-cache', 'v1.Index/clearCache'); //清除缓存
	Route::get('mysql-reset', 'v1.TableIdReset/index')->middleware(Admin::class); //重置数据表ID
	Route::get('action-init', 'v1.ActionTableInit/index')->middleware(Admin::class); //重置权限表

	//自定义字段
	Route::group('meta', function () {
		Route::get('key', 'v1.Meta/key'); //自定义字段键列表
	});

	//动作路由
	Route::group('action', function () {
		Route::get('', 'v1.Action/index'); //动作列表
	});

	//角色路由
	Route::group('role', function () {
		Route::get('', 'v1.Role/index')->action_name('角色管理-角色列表'); //角色列表
		Route::get(':id', 'v1.Role/read'); //角色信息
		Route::post('add', 'v1.Role/add')->action_name('角色管理-添加角色')->middleware(Admin::class); //添加角色
		Route::put(':id', 'v1.Role/edit')->action_name('角色管理-修改角色信息')->middleware(Admin::class); //修改角色信息
		Route::delete('', 'v1.Role/delete')->action_name('角色管理-删除角色')->middleware(Admin::class); //删除角色
		Route::post('action', 'v1.Role/action')->action_name('角色管理-修改角色权限')->middleware(Admin::class); //修改角色权限
	});

	//用户路由
	Route::group('user', function () {
		Route::post('register', 'v1.User/register'); //用户注册
		Route::post('login', 'v1.User/login'); //用户登陆
		Route::get('info', 'v1.User/info')->middleware(Login::class); //当前用户信息
		Route::put('update', 'v1.User/update')->middleware(Login::class); //修改当前用户信息
		Route::put('email-modify', 'v1.User/emailModify')->middleware(Login::class); //修改邮件
		Route::put('password-reset', 'v1.User/passwordReset'); //修改邮件
	});

	//用户路由（管理员）
	Route::group('users', function () {
		Route::get('', 'v1.User/index')->action_name('用户管理-用户列表')->middleware(Permission::class);
		Route::get(':id', 'v1.User/read')->action_name('用户管理-用户信息')->middleware(Permission::class);
		Route::delete('', 'v1.User/delete')->action_name('用户管理-删除用户')->middleware(Permission::class);
		Route::put(':id', 'v1.User/edit')->action_name('用户管理-修改用户信息')->middleware(Permission::class);
	});

	//系统设置
	Route::group('setting', function () {
		Route::get('', 'v1.Setting/index'); //系统信息
		Route::put('', 'v1.Setting/update')->action_name('系统设置-系统设置')->middleware(Permission::class);
	});

	//公司信息设置
	Route::group('company', function () {
		Route::get('', 'v1.Company/index'); //系统信息
		Route::put('', 'v1.Company/update')->action_name('公司设置-公司信息设置')->middleware(Permission::class);
	});

	//系统环境信息
	route::group('system', function () {
		Route::get('', 'v1.System/index')->middleware(Login::class);
	});

	//菜单
	Route::group('menu', function () {
		Route::get('', 'v1.Menu/index'); //菜单列表
		Route::post('', 'v1.Menu/add')->action_name('菜单管理-添加菜单')->middleware(Permission::class);
		Route::put(':id', 'v1.Menu/update')->action_name('菜单管理-更新菜单')->middleware(Permission::class);
		Route::delete(':id', 'v1.Menu/delete')->action_name('菜单管理-删除菜单')->middleware(Permission::class);
	});

	//友情链接
	Route::group('links', function () {
		Route::get('', 'v1.Link/index'); //友情链接列表
		Route::get(':id', 'v1.Link/read'); //单个友情链接
		Route::post('add', 'v1.Link/add')->action_name('友情链接-添加友情链接')->middleware(Permission::class);
		Route::put(':id', 'v1.Link/update')->action_name('友情链接-更新友情链接')->middleware(Permission::class);
		Route::delete('', 'v1.Link/delete')->action_name('友情链接-删除友情链接')->middleware(Permission::class);
	});

	//文件系统
	Route::group('files', function () {
		Route::get('', 'v1.File/index');
		Route::post('', 'v1.File/upload')->action_name('文件管理-上传文件')->middleware(Permission::class);
		Route::delete('', 'v1.File/delete')->action_name('文件管理-删除文件')->middleware(Permission::class);
		Route::put(':id', 'v1.File/update')->action_name('文件管理-更新文件描述')->middleware(Permission::class);
	});

	//分类系统
	Route::group('categories', function () {
		Route::get('', 'v1.Category/index'); //分类列表
		Route::get(':id', 'v1.Category/read'); //分类详情
		Route::post('add', 'v1.Category/add')->action_name('分类管理-添加分类')->middleware(Permission::class);
		Route::put(':id', 'v1.Category/update')->action_name('分类管理-更新分类')->middleware(Permission::class);
		Route::delete('', 'v1.Category/delete')->action_name('分类管理-删除分类')->middleware(Permission::class);
	});

	//标签系统
	Route::group('tags', function () {
		Route::get('', 'v1.Tag/index'); //标签列表
		Route::get(':id', 'v1.Tag/read'); //标签详情
		Route::post('add', 'v1.Tag/add')->action_name('标签管理-添加标签')->middleware(Permission::class);
		Route::put(':id', 'v1.Tag/update')->action_name('标签管理-更新标签')->middleware(Permission::class);
		Route::delete('', 'v1.Tag/delete')->action_name('标签管理-删除标签')->middleware(Permission::class);
	});

	//内容系统
	Route::group('posts', function () {
		Route::get('', 'v1.Post/index'); //内容列表
		Route::get(':id', 'v1.Post/read'); //内容详情
		Route::put('set-top/:id', 'v1.Post/setTop'); //内容置顶
		Route::post('add', 'v1.Post/add')->action_name('内容管理-添加内容')->middleware(Permission::class);
		Route::put(':id', 'v1.Post/update')->action_name('内容管理-更新内容')->middleware(Permission::class);
		Route::delete('', 'v1.Post/delete')->action_name('内容管理-删除内容')->middleware(Permission::class);
		Route::post('restore', 'v1.Post/restore')->action_name('内容管理-恢复内容')->middleware(Permission::class);
	});

	//商品系统
	Route::group('goods', function () {
		Route::get(':id', 'v1.Goods/read'); //内容详情
	});

	//搜索系统
	Route::group('search', function () {
		Route::get('', 'v1.Search/index'); //默认为内容搜索
	});

	//留言系统
	Route::group('guestbook', function () {
		Route::get('', 'v1.Guestbook/index');
		Route::post('add', 'v1.Guestbook/add'); //添加留言
	});

	//轮播图
	Route::group('banners', function () {
		Route::get('', 'v1.Banner/index'); //轮播图列表
		Route::post('', 'v1.Banner/add')->action_name('轮播图管理-添加轮播图')->middleware(Permission::class);
		Route::put(':id', 'v1.Banner/update')->action_name('轮播图管理-更新轮播图')->middleware(Permission::class);
		Route::delete('', 'v1.Banner/delete')->action_name('轮播图管理-删除轮播图')->middleware(Permission::class);
	});

	//评论
	Route::group('comments', function () {
		Route::get('', 'v1.Comment/index');
		Route::post('add', 'v1.Comment/add'); //添加评论
	});

	//邮件
	Route::group('mail', function () {
		Route::get('verify-code', 'v1.Email/verify');
	});
});
