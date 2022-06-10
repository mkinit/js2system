<?php
namespace app\common;
use Firebase\JWT\JWT;
use think\facade\Config;

class Tools {
	//获取当前主题名称
	public static function getThemeName() {
		$theme = \think\facade\Db::name('setting')->where('key', 'theme')->value('value');
		$theme_path = '../public/theme/' . $theme;
		if (!$theme || !is_dir($theme_path)) {
			$theme = 'default';
		}
		return $theme;
	}

	//获取系统设置的网站名称
	public static function getSiteName() {
		$name = \think\facade\Db::name('setting')->where('key', 'site_name')->value('value');
		return $name;
	}

	/*
		* 根据目录文件生成md5
		* https://jonlabelle.com/snippets/view/php/generate-md5-hash-for-directory
		* @param	$directory	string
		* @return	boolean|string
	*/
	public static function hashDirectory($directory) {
		if (!is_dir($directory)) {
			return false;
		}
		$files = array();
		$dir = dir($directory);
		while (false !== ($file = $dir->read())) {
			if ($file != '.' and $file != '..') {
				if (is_dir($directory . '/' . $file)) {
					$files[] = self::hashDirectory($directory . '/' . $file);
				} else {
					$files[] = md5_file($directory . '/' . $file);
				}
			}
		}
		$dir->close();
		return md5(implode('', $files));
	}

	//生成token
	public static function token($user_id) {
		$nowtime = time();
		$payload = array(
			'aud' => $user_id, //jwt所面向的用户
			'iat' => $nowtime, //签发时间
			'exp' => $nowtime + 86400, //24小时，过期时间（秒）
		);
		return JWT::encode($payload, Config::get('key.token_key'));
	}

	//验证token
	public static function tokenValidate($token) {
		//0208作为测试时的万能token
		if (env('APP_DEBUG') && $token === '0208') {
			return (object) array("aud" => 1, "iat" => 1617083383, "exp" => 1617169783);
		}
		try {
			return JWT::decode($token, Config::get('key.token_key'), ['HS256']);
		} catch (\Throwable $e) {
			return $e->getMessage();
		}
		return JWT::decode($token, Config::get('key.token_key'), ['HS256']);
	}

	//生成无限级树结构 https://www.cnblogs.com/zc290987034/p/13530889.html
	public static function makeTree($list) {
		$tree = array();
		$packData = array();

		//将所有的分类id作为数组key
		foreach ($list as $k => $v) {
			$packData[$v['id']] = $v;
		}
		//利用引用，将每个分类添加到父类child数组中，这样一次遍历即可形成树形结构。
		foreach ($packData as $key => $val) {
			if ($val['parent_id'] != 0) {
				//找到其父类
				$packData[$val['parent_id']]['children'][] = &$packData[$key];
			} else {
				$tree[] = &$packData[$key];
			}
		}
		return $tree;
	}

	/*
		获取扁平结构数据的所有的子孙分类
		@param Array $categories 所有分类
		@param Number $parent_id 上级关联key
		@return Array
	*/
	public static function getAllChildren($categories, $parent_id) {
		if ((int) $parent_id == 0) {
			return [];
		}
		$children = [];
		foreach ($categories as $item) {
			if ($item['parent_id'] == $parent_id) {
				//如果分类的parent_id等于传进来的parent_id，那么item就是目标分类
				$children[] = $item;
				$children = array_merge($children, self::getAllChildren($categories, $item['id']));
			};
		};
		return $children;
	}

	/*
		递归查找分类，广度优先
		@param Array $categories 分类数组
		@param Number|String $id 要查找的分类ID
		@param String $child_key 子分类的key
	*/
	public static function findCategory($categories, $id, $child_key = '') {
		$children_arr = [];
		foreach ($categories as $category) {
			if ($category['id'] == $id) {
				return $category;
			}
			if (!empty($child_key) && !empty($category[$child_key])) {
				array_push($children_arr, ...$category[$child_key]);
			}
		}
		if (count($children_arr)) {
			return self::findCategory($children_arr, $id, '$child_key');
		}

	}

	//判断是否图片文件
	public static function isImage($ext) {
		return preg_match('/jpe?g|png|bmp|gif/i', $ext);
	}

	//判断是否为视频文件
	public static function isVideo($ext) {
		return preg_match('/avi|wmv|mpe?g|mov|mp4/i', $ext);
	}

	//判断是否为音频文件
	public static function isAudio($ext) {
		return preg_match('/mp3|wav|aac|flac|ogg|wma|ape/i', $ext);
	}

	//随机验证码
	public static function verifyCode($num) {
		$str = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
		$verify_code = '';
		for ($i = 0; $i < $num; $i++) {
			$verify_code .= $str[rand(0, 9)];
		}
		return $verify_code;
	}
}