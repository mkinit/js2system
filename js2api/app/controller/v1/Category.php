<?php

namespace app\controller\v1;

use app\common\Api;
use app\common\model\Category as CategoryModel;
use app\common\model\Post as PostModel;
use app\common\Tools;
use app\common\validate\Category as CategoryValidate;
use think\exception\ValidateException;

class Category extends Api {
	/*
		分类列表
	*/
	public function index() {
		$categories = $this->categories->toArray();
		$categories_tree = Tools::makeTree($categories);
		return $this->response($categories_tree);
	}

	/*
		分类详情（GET）
	*/
	public function read($id) {
		$category = CategoryModel::find($id);
		if (!$category) {
			return $this->response(null, '没有该分类', 400);
		}
		return $this->response($category);
	}

	/*
		更新分类详情（PUT）
		@param	$id				String	分类ID*
		@param	$name			Number	分类名称*
		@param	$parent_id		String	父类ID
		@param	$keywords		String	关键词
		@param	$description	String	描述
		@param	$cover			String	封面图片
	*/
	public function update($id, $name = '', $parent_id = 0, $keywords = '', $description = '', $cover = '') {

		$category = CategoryModel::find($id);

		if (!$category) {
			return $this->response(null, '没有该分类', 400);
		}

		if (strtolower($category->name) !== strtolower($name)) {
			try {
				validate(CategoryValidate::class)->check([
					'name' => $name,
				]);
			} catch (ValidateException $e) {
				// 验证失败 输出错误信息
				return $this->response(null, $e->getError(), 400);
			}

		}

		$category->name = $name;
		$category->parent_id = $parent_id;
		$category->keywords = $keywords;
		$category->description = $description;
		$category->cover = $cover;

		$result = $category->save();

		if ($result) {
			return $this->response($category);
		}
	}

	/*
		删除分类（DELETE）
		@param $id 分类ID*
	*/
	public function delete($ids) {
		$ids_arr = explode(',', $ids);

		$posts_count = PostModel::where('category_id', 'in', $ids_arr)->count();
		if ($posts_count) {
			return $this->response(null, '分类下有文章，不能删除', 400);
		}

		CategoryModel::destroy($ids_arr);
		//删除分类后所有子分类变成顶级分类
		foreach ($ids_arr as $id) {
			$children = CategoryModel::where('parent_id', $id)->select();
			foreach ($children as $child) {
				$child->parent_id = 0;
				$child->save();
			}
		}
		return $this->response();
	}

	/*
		添加分类（POST）
		@param	$name			String	分类名称*
		@param	$parent_id		Number	父类ID
		@param	$keywords		String	关键词
		@param	$description	String	描述
		@param	$cover			String	封面图片
	*/
	public function add($name = '', $parent_id = 0, $keywords = '', $description = '', $cover = '') {

		try {
			validate(CategoryValidate::class)->check([
				'name' => $name,
			]);
		} catch (ValidateException $e) {
			// 验证失败 输出错误信息
			return $this->response(null, $e->getError(), 400);
		}

		$category = new CategoryModel;

		$category->name = $name;
		$category->parent_id = $parent_id;
		$category->keywords = $keywords;
		$category->description = $description;
		$category->cover = $cover;

		$result = $category->save();
		if ($result) {
			$category->id = (int) $category->id;
			return $this->response($category);
		}
	}
}
