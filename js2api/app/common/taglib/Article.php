<?php
namespace app\common\taglib;
use think\template\TagLib;

class Article extends TagLib {

	/**
	 * 标签列表
	 */
	protected $tags = [
		//不要写参数声明，会有BUG
		'list' => ['attr' => '', 'close' => 1], //最新内容列表
	];

	/*
		$name String 自定义循环中的value变量名称，防止与页面变量重名导致覆盖数据，最终变量名：$name_post
		$limit Number 文章数量
		$cate Number 分类ID，限制文章分类
		$order String[rand] 排序字段，rand：随机，view：浏览次数

		{Article:list name="news" limit="6" cate="$post['category']['id']" order="rand"}
		<li>{$news_post['title']}</li>
		{/Article:list}
	*/

	public function tagList($attr, $content) {
		$name = empty($attr["name"]) ? 'js2' : $attr["name"];
		$limit = empty($attr["limit"]) ? 10 : $attr["limit"];
		$where = empty($attr["cate"]) ? '["type" => "post"]' : '[["type","=","post"],["category_id","in",implode(",",array_merge([' . $attr["cate"] . '],array_column(\app\common\Tools::getAllChildren($categories,' . $attr["cate"] . '),"id")))]]';
		$order = 'time_add desc';
		if (!empty($attr["order"])) {
			switch ($attr["order"]) {
			case 'rand':
				$order = 'rand()';
				break;
			case 'view':
				$order = 'view desc';
				break;
			}
		}
		$parse = '<?php ';
		$parse .= '$__LIST__=\app\common\model\Post::with(["category", "tags", "meta", "author"])->where(' . $where . ')->orderRaw("' . $order . '")->limit(' . $limit . ')->select();';
		$parse .= ' ?>';
		$parse .= '{volist name="$__LIST__" id="' . $name . '_post"}';
		$parse .= $content;
		$parse .= '{/volist}';
		return $parse;
	}

}