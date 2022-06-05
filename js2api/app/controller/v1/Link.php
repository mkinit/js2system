<?php
namespace app\controller\v1;

use app\common\Api;
use app\common\model\Link as LinkModel;
use app\common\validate\Link as LinkValidate;
use think\exception\ValidateException;

class Link extends Api {
	public function index() {
		$links = LinkModel::order('id desc')->page($this->page, $this->pageSize)->select();
		$pagination = [
			'total' => LinkModel::count(),
		];
		$this->setPagination($pagination);
		return $this->response($links);
	}

	public function read($id) {
		$link = LinkModel::find($id);
		if ($link) {
			return $this->response($link);
		} else {
			return $this->response(null, '没有这条数据', 400);
		}
	}

	/*
		添加链接
		@param	$name	String 链接名称*
		@param	$link	String 链接地址*
	*/
	public function add($name = '', $link = '') {
		try {
			validate(LinkValidate::class)->check([
				'name' => $name,
				'link' => $link,
			]);
		} catch (ValidateException $e) {
			// 验证失败 输出错误信息
			return $this->response(null, $e->getError(), 400);
		}

		$new_link = new LinkModel;

		$new_link->name = $name;
		$new_link->link = $link;

		$result = $new_link->save();
		if ($result) {
			$new_link->id = (int) $new_link->id;
			return $this->response($new_link);
		}
	}

	/*
		更新链接
		@param	$name	String 链接名称*
		@param	$link	String 链接地址*
	*/
	public function update($id, $name = '', $link = '') {
		try {
			validate(LinkValidate::class)->check([
				'name' => $name,
				'link' => $link,
			]);
		} catch (ValidateException $e) {
			// 验证失败 输出错误信息
			return $this->response(null, $e->getError(), 400);
		}

		$result = LinkModel::update(['name' => $name, 'link' => $link], ['id' => $id]);
		if ($result) {
			return $this->response($result);
		}
	}

	public function delete($ids) {
		LinkModel::destroy(explode(',', $ids));
		return $this->response();
	}

}