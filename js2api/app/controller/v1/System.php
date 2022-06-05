<?php

namespace app\controller\v1;
use app\common\Api;
use app\common\model\Category as CategoryModel;
use app\common\model\Comment as CommentModel;
use app\common\model\File as FileModel;
use app\common\model\Guestbook as GuestbookModel;
use app\common\model\Post as PostModel;
use app\common\model\Tag as TagModel;
use think\facade\Db;

class System extends Api {
	public function index() {
		return $this->response([
			'host' => $_SERVER['HTTP_HOST'], //主机地址
			'server' => php_uname('s'), //服务器操作系统
			'php_version' => PHP_VERSION, //PHP版本
			'db_version' => config()['database']['default'] . ' ' . Db::query('SELECT VERSION() AS ver')[0]['ver'], //数据库
			'think_version' => app()->version(), //框架版本
			'category_count' => CategoryModel::count(), //分类数量
			'post_count' => PostModel::where('type', 'post')->count(), //文章数量
			'single_count' => PostModel::where('type', 'single')->count(), //单页数量
			'file_count' => FileModel::count(), //文件数量
			'guestbook_count' => GuestbookModel::count(), //留言数量
			'comment_count' => CommentModel::count(), //评论数量
			'tag_count' => TagModel::count(), //标签数量
		]);
	}
}
