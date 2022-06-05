<?php
namespace app\common\model;

use think\Model;

class Comment extends Model {

	public function author() {
		return $this->hasOne(User::class, 'id', 'user_id');
	}

	public function post() {
		return $this->hasOne(Post::class, 'id', 'post_id');
	}
}