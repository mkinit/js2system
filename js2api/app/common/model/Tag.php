<?php
namespace app\common\model;

use think\Model;

class Tag extends Model {
	//关联内容
	public function posts() {
		return $this->belongsToMany(Post::class, PostTag::class, 'post_id', 'tag_id');
	}
}