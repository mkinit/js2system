<?php

namespace app\controller\v1;
use think\facade\Db;
use think\facade\Env;

class TableIdReset {
	public function index() {
		$all_tables = Db::query('show tables');
		foreach ($all_tables as $key => $value) {
			$table = $value['Tables_in_' . Env::get('DATABASE.DATABASE')];
			Db::query('ALTER TABLE ' . $table . ' DROP id');
			Db::query('OPTIMIZE TABLE ' . $table);
			Db::query('ALTER TABLE ' . $table . ' ADD id int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');
		}
		return json('数据表重置完成');
	}
}
