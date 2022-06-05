<?php
namespace app\controller\v1;

use app\common\Api;
use app\common\model\Post as PostModel;

class Search extends Api {
	/*
		默认内容搜索（GET）
		@param	$keywords	Sring				关键词搜索，英文逗号分隔
		@param	$type		'post'|'single'		内容类型
		@param	$trashed	0|1					回收站
		@param	$page		Number				分页
		@param	$page_size	Number				分页条数
	*/
	public function index($keywords = '', $type = 'post', $trashed = 0) {
		$keywords = explode(' ', $keywords);

		$where = [
			['type', '=', $type ? $type : 'post'],
		];

		foreach ($keywords as $key => $value) {
			array_push($where, ['title|content', 'like', '%' . $value . '%']);
		}
		if ($trashed) {
			$posts = PostModel::onlyTrashed()->with(['author', 'meta', 'tags', 'category'])->where($where)->page($this->page, $this->pageSize)->order('time_add desc')->select();
		} else {
			$posts = PostModel::with(['author', 'meta', 'tags', 'category'])->where($where)->page($this->page, $this->pageSize)->order('time_add desc')->select();
		}

		$pagination = [
			'total' => $trashed ? PostModel::onlyTrashed()->where($where)->count() : PostModel::where($where)->count(),
		];
		$this->setPagination($pagination);
		return $this->response($posts);
	}
}