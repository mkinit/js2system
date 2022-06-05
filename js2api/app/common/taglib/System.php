<?php
namespace app\common\taglib;
use app\common\Tools;
use think\template\TagLib;

class System extends TagLib {

	/**
	 * 标签列表
	 */
	protected $tags = [
		//不要写参数声明，会有BUG
		'version' => ['attr' => '', 'close' => 0], //版本信息
	];
	public function tagVersion() {
		$theme_name = Tools::getThemeName(); //当前主题名称
		$theme_list_path = public_path() . '/theme/'; //主题列表目录
		$has_file = is_file($theme_list_path . '/md5.txt');
		$theme_md5 = Tools::hashDirectory($theme_list_path . $theme_name);
		if ($has_file) {
			$old_md5 = file_get_contents($theme_list_path . 'md5.txt');
			if ($theme_md5 !== $old_md5) {
				$file = fopen($theme_list_path . '/md5.txt', 'w');
				fwrite($file, $theme_md5);
				fclose($file);
			}
		} else {
			$file = fopen($theme_list_path . '/md5.txt', 'w');
			fwrite($file, $theme_md5);
			fclose($file);
		}
		return $theme_md5;
	}

}