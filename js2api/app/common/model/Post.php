<?php
namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Post extends Model {

	use SoftDelete;

	protected $type = [
		'time_delete' => 'datetime',
	];

	protected $deleteTime = 'time_delete';

	//统一处理发布时间
	public function getTimeAddAttr($value) {
		return date('Y-m-d H:i', strtotime($value));
	}

	//统一处理修改时间
	public function getTimeModifyAttr($value) {
		return date('Y-m-d H:i', strtotime($value));
	}

	//统一处理删除时间
	public function getTimeDeleteAttr($value) {
		if (!$value) {
			return null;
		}

		return date('Y-m-d H:i', strtotime($value));
	}

	//关联作者
	public function author() {
		return $this->hasOne(User::class, 'id', 'author_id');
	}

	//关联自定义字段
	public function meta() {
		return $this->hasMany(Meta::class);
	}

	//关联标签
	public function tags() {
		return $this->belongsToMany(Tag::class, PostTag::class, 'tag_id', 'post_id');
	}

	//关联分类
	public function category() {
		return $this->hasOne(Category::class, 'id', 'category_id');
	}
}