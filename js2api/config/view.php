<?php
// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------

//获取主题名称
use app\common\Tools;
$theme = Tools::getThemeName();
return [
	'type' => 'Think',
	'auto_rule' => 1, // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写 3 保持操作方法
	'view_dir_name' => 'theme', // 模板目录名
	'view_path' => 'theme/' . $theme . '/', //模板路径
	'tpl_cache' => true, //模板编译缓存
	'display_cache' => true, //模板渲染缓存
	'tpl_replace_string' => [
		'__static__' => '/static',
		'__js__' => '/theme/' . $theme . '/static/js',
		'__style__' => '/theme/' . $theme . '/static/style',
		'__img__' => '/theme/' . $theme . '/static/images',
	], //模板替换输出
	'layout_on' => true, //模板布局
	'layout_name' => 'layout',
	'view_suffix' => 'html', // 模板后缀
	'view_depr' => DIRECTORY_SEPARATOR, // 模板文件名分隔符
	'tpl_begin' => '{', // 模板引擎普通标签开始标记
	'tpl_end' => '}', // 模板引擎普通标签结束标记
	'taglib_begin' => '{', // 标签库标签开始标记
	'taglib_end' => '}', // 标签库标签结束标记
	'taglib_pre_load' => 'app\common\taglib\Article,app\common\taglib\System', // 预先加载的标签库
];
