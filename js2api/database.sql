-- --------------------------------------------------------
-- 主机:                           127.0.0.1
-- 服务器版本:                        5.7.26 - MySQL Community Server (GPL)
-- 服务器操作系统:                      Win64
-- HeidiSQL 版本:                  11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 导出  表 js2system.js2system_action 结构
CREATE TABLE IF NOT EXISTS `js2system_action` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `controller` char(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `action` char(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` char(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='后台动作权限表';

-- 正在导出表  js2system.js2system_action 的数据：33 rows
DELETE FROM `js2system_action`;
/*!40000 ALTER TABLE `js2system_action` DISABLE KEYS */;
INSERT INTO `js2system_action` (`id`, `controller`, `action`, `name`) VALUES
	(1, 'Role', 'index', '角色管理-角色列表'),
	(2, 'Role', 'delete', '角色管理-删除角色'),
	(3, 'Role', 'edit', '角色管理-修改角色信息'),
	(4, 'Role', 'add', '角色管理-添加角色'),
	(5, 'Role', 'action', '角色管理-修改角色权限'),
	(6, 'User', 'index', '用户管理-用户列表'),
	(7, 'User', 'delete', '用户管理-删除用户'),
	(8, 'User', 'read', '用户管理-用户信息'),
	(9, 'User', 'edit', '用户管理-修改用户信息'),
	(10, 'Setting', 'update', '系统设置-系统设置'),
	(11, 'Company', 'update', '公司设置-公司信息设置'),
	(12, 'Menu', 'add', '菜单管理-添加菜单'),
	(13, 'Menu', 'update', '菜单管理-更新菜单'),
	(14, 'Menu', 'delete', '菜单管理-删除菜单'),
	(15, 'Link', 'delete', '友情链接-删除友情链接'),
	(16, 'Link', 'update', '友情链接-更新友情链接'),
	(17, 'Link', 'add', '友情链接-添加友情链接'),
	(18, 'File', 'upload', '文件管理-上传文件'),
	(19, 'File', 'delete', '文件管理-删除文件'),
	(20, 'File', 'update', '文件管理-更新文件描述'),
	(21, 'Category', 'delete', '分类管理-删除分类'),
	(22, 'Category', 'update', '分类管理-更新分类'),
	(23, 'Category', 'add', '分类管理-添加分类'),
	(24, 'Tag', 'delete', '标签管理-删除标签'),
	(25, 'Tag', 'update', '标签管理-更新标签'),
	(26, 'Tag', 'add', '标签管理-添加标签'),
	(27, 'Post', 'delete', '内容管理-删除内容'),
	(28, 'Post', 'update', '内容管理-更新内容'),
	(29, 'Post', 'add', '内容管理-添加内容'),
	(30, 'Post', 'restore', '内容管理-恢复内容'),
	(31, 'Banner', 'add', '轮播图管理-添加轮播图'),
	(32, 'Banner', 'delete', '轮播图管理-删除轮播图'),
	(33, 'Banner', 'update', '轮播图管理-更新轮播图');
/*!40000 ALTER TABLE `js2system_action` ENABLE KEYS */;

-- 导出  表 js2system.js2system_banner 结构
CREATE TABLE IF NOT EXISTS `js2system_banner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '跳转链接',
  `blank` int(1) NOT NULL DEFAULT '0' COMMENT '是否新窗口打开',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='轮播图';

-- 正在导出表  js2system.js2system_banner 的数据：3 rows
DELETE FROM `js2system_banner`;
/*!40000 ALTER TABLE `js2system_banner` DISABLE KEYS */;
INSERT INTO `js2system_banner` (`id`, `image_url`, `link`, `blank`, `sort`) VALUES
	(1, '/static/images/point-line.jpg', 'https://im.mkinit.com', 0, 0),
	(2, '/static/images/point-line.jpg', '', 0, 0),
	(3, '/static/images/point-line.jpg', '', 0, 0);
/*!40000 ALTER TABLE `js2system_banner` ENABLE KEYS */;

-- 导出  表 js2system.js2system_category 结构
CREATE TABLE IF NOT EXISTS `js2system_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父类ID',
  `keywords` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '关键词',
  `description` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `cover` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '封面图片',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='分类表';

-- 正在导出表  js2system.js2system_category 的数据：8 rows
DELETE FROM `js2system_category`;
/*!40000 ALTER TABLE `js2system_category` DISABLE KEYS */;
INSERT INTO `js2system_category` (`id`, `name`, `parent_id`, `keywords`, `description`, `cover`) VALUES
	(1, 'windows', 0, '', '操作系统相关内容', '/static/images/cover-placeholder.jpg'),
	(2, '前端笔记', 0, '', '记录前端开发相关的一切', '/static/images/cover-placeholder.jpg'),
	(3, 'javascript', 2, '', '记录javascript相关的一切', '/static/images/cover-placeholder.jpg'),
	(4, 'css', 2, '', '记录css相关的一切', '/static/images/cover-placeholder.jpg'),
	(5, '开发工具', 0, '', '记录开发相关的一切', '/static/images/cover-placeholder.jpg'),
	(6, 'vue', 2, '', '记录vue相关的一切', '/static/images/cover-placeholder.jpg'),
	(7, '后端笔记', 0, '', '记录后端开发相关的一切', '/static/images/cover-placeholder.jpg'),
	(8, 'php', 7, '', '记录php开发相关的一切', '/static/images/cover-placeholder.jpg');
/*!40000 ALTER TABLE `js2system_category` ENABLE KEYS */;

-- 导出  表 js2system.js2system_comment 结构
CREATE TABLE IF NOT EXISTS `js2system_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) NOT NULL COMMENT '文章ID',
  `content` varchar(17500) COLLATE utf8_unicode_ci NOT NULL COMMENT '评论内容',
  `nickname` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '作者昵称',
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '作者邮件',
  `link` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '作者链接',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级评论ID',
  `reply_to_id` int(10) DEFAULT '0' COMMENT '被回复评论ID',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '登录用户需要记录用户ID',
  `time_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='评论表';

-- 正在导出表  js2system.js2system_comment 的数据：0 rows
DELETE FROM `js2system_comment`;
/*!40000 ALTER TABLE `js2system_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `js2system_comment` ENABLE KEYS */;

-- 导出  表 js2system.js2system_company 结构
CREATE TABLE IF NOT EXISTS `js2system_company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `annotation` char(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '无用字段，备注用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='公司信息表';

-- 正在导出表  js2system.js2system_company 的数据：10 rows
DELETE FROM `js2system_company`;
/*!40000 ALTER TABLE `js2system_company` DISABLE KEYS */;
INSERT INTO `js2system_company` (`id`, `key`, `value`, `annotation`) VALUES
	(1, 'name', '公司名称', '公司名称'),
	(2, 'company_code', 'CC123456789', '营业执照代码'),
	(3, 'tel', '020-88888888', '电话'),
	(4, 'fax', '020-88888888', '传真'),
	(5, 'phone', '13800138000', '手机'),
	(6, 'address', '广东省，广州市，天河区', '地址'),
	(7, 'zip', '510000', '邮编'),
	(8, 'email', 'im@mkinit.com', '邮箱'),
	(9, 'wechat', '/static/images/wechat.jpg', '微信'),
	(10, 'qq', '124711255', 'qq');
/*!40000 ALTER TABLE `js2system_company` ENABLE KEYS */;

-- 导出  表 js2system.js2system_file 结构
CREATE TABLE IF NOT EXISTS `js2system_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名称',
  `desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '文件描述',
  `type` char(5) COLLATE utf8_unicode_ci NOT NULL COMMENT '文件类型：image、video、audio、file',
  `time_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '上传时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文件表';

-- 正在导出表  js2system.js2system_file 的数据：7 rows
DELETE FROM `js2system_file`;
/*!40000 ALTER TABLE `js2system_file` DISABLE KEYS */;
INSERT INTO `js2system_file` (`id`, `name`, `desc`, `type`, `time_add`) VALUES
	(1, '/upload/20220309160423-1.png', '系统设置', 'image', '2022-03-09 16:04:23'),
	(2, '/upload/20220309160443-1.png', '菜单设置', 'image', '2022-03-09 16:04:43'),
	(3, '/upload/20220309160453-1.png', '首页', 'image', '2022-03-09 16:04:53'),
	(4, '/upload/20220309160504-1.png', '文章管理', 'image', '2022-03-09 16:05:04'),
	(5, '/upload/20220309160656-1.png', '媒体库', 'image', '2022-03-09 16:06:56'),
	(6, '/upload/20220309160656-2.png', '文件管理', 'image', '2022-03-09 16:06:57'),
	(7, '/upload/20220309161334-1.png', '发布内容', 'image', '2022-03-09 16:13:34');
/*!40000 ALTER TABLE `js2system_file` ENABLE KEYS */;

-- 导出  表 js2system.js2system_goods 结构
CREATE TABLE IF NOT EXISTS `js2system_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品名称',
  `category_id` int(10) NOT NULL COMMENT '商品分类ID',
  `code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '商品货号',
  `multiple` int(1) NOT NULL DEFAULT '1' COMMENT '多规格商品，默认是',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品类型ID，默认继承分类',
  `price` decimal(10,2) DEFAULT NULL COMMENT '商品价格',
  `market_price` decimal(10,2) DEFAULT NULL COMMENT '市场价格',
  `sales` int(11) NOT NULL DEFAULT '0' COMMENT '销量',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `time_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `time_modify` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `time_delete` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商品表';

-- 正在导出表  js2system.js2system_goods 的数据：2 rows
DELETE FROM `js2system_goods`;
/*!40000 ALTER TABLE `js2system_goods` DISABLE KEYS */;
INSERT INTO `js2system_goods` (`id`, `name`, `category_id`, `code`, `multiple`, `type_id`, `price`, `market_price`, `sales`, `stock`, `time_add`, `time_modify`, `time_delete`) VALUES
	(1, '多规格长袖女装上衣', 1, NULL, 1, 2, NULL, NULL, 0, 0, '2022-02-20 15:43:26', '2022-02-20 21:18:51', NULL),
	(2, '单规格短袖女装上衣', 1, NULL, 0, 0, NULL, NULL, 0, 0, '2022-02-20 15:43:26', NULL, NULL);
/*!40000 ALTER TABLE `js2system_goods` ENABLE KEYS */;

-- 导出  表 js2system.js2system_goods_category 结构
CREATE TABLE IF NOT EXISTS `js2system_goods_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `parent_id` int(10) NOT NULL COMMENT '上级分类',
  `keywords` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '关键词',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '描述',
  `icon` int(10) DEFAULT NULL COMMENT '分类图标',
  `type_id` int(11) NOT NULL DEFAULT '1' COMMENT '商品类型ID，以商品为主',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商品分类';

-- 正在导出表  js2system.js2system_goods_category 的数据：1 rows
DELETE FROM `js2system_goods_category`;
/*!40000 ALTER TABLE `js2system_goods_category` DISABLE KEYS */;
INSERT INTO `js2system_goods_category` (`id`, `name`, `parent_id`, `keywords`, `description`, `icon`, `type_id`) VALUES
	(1, '女装', 0, NULL, NULL, NULL, 1);
/*!40000 ALTER TABLE `js2system_goods_category` ENABLE KEYS */;

-- 导出  表 js2system.js2system_goods_content 结构
CREATE TABLE IF NOT EXISTS `js2system_goods_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` mediumtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商品详情表';

-- 正在导出表  js2system.js2system_goods_content 的数据：0 rows
DELETE FROM `js2system_goods_content`;
/*!40000 ALTER TABLE `js2system_goods_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `js2system_goods_content` ENABLE KEYS */;

-- 导出  表 js2system.js2system_goods_gallery 结构
CREATE TABLE IF NOT EXISTS `js2system_goods_gallery` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT NULL COMMENT '商品ID',
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商品主图集';

-- 正在导出表  js2system.js2system_goods_gallery 的数据：0 rows
DELETE FROM `js2system_goods_gallery`;
/*!40000 ALTER TABLE `js2system_goods_gallery` DISABLE KEYS */;
/*!40000 ALTER TABLE `js2system_goods_gallery` ENABLE KEYS */;

-- 导出  表 js2system.js2system_goods_sku 结构
CREATE TABLE IF NOT EXISTS `js2system_goods_sku` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) NOT NULL COMMENT '商品ID',
  `spec_data` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '序列化的数据，包含规格、规格值、自定义规格值、规格图片',
  `price` decimal(10,2) NOT NULL COMMENT '价格',
  `market_price` decimal(10,2) DEFAULT NULL COMMENT '市场价',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `sales` int(11) NOT NULL DEFAULT '0' COMMENT '销量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='规格商品表';

-- 正在导出表  js2system.js2system_goods_sku 的数据：3 rows
DELETE FROM `js2system_goods_sku`;
/*!40000 ALTER TABLE `js2system_goods_sku` DISABLE KEYS */;
INSERT INTO `js2system_goods_sku` (`id`, `goods_id`, `spec_data`, `price`, `market_price`, `stock`, `sales`) VALUES
	(1, 1, 'a:2:{i:0;a:4:{s:7:"spec_id";i:1;s:13:"spec_value_id";i:1;s:15:"spec_value_text";s:6:"特小";s:5:"image";s:0:"";}i:1;a:4:{s:7:"spec_id";i:2;s:13:"spec_value_id";i:8;s:15:"spec_value_text";s:9:"中国红";s:5:"image";s:0:"";}}', 8.50, NULL, 10, 0),
	(2, 1, 'a:2:{i:0;a:4:{s:7:"spec_id";i:1;s:13:"spec_value_id";i:7;s:15:"spec_value_text";s:6:"特大";s:5:"image";s:0:"";}i:1;a:4:{s:7:"spec_id";i:2;s:13:"spec_value_id";i:14;s:15:"spec_value_text";s:9:"神秘紫";s:5:"image";s:0:"";}}', 15.00, NULL, 20, 0),
	(3, 1, 'a:2:{i:0;a:4:{s:7:"spec_id";i:1;s:13:"spec_value_id";i:3;s:15:"spec_value_text";s:6:"中码";s:5:"image";s:0:"";}i:1;a:4:{s:7:"spec_id";i:2;s:13:"spec_value_id";i:10;s:15:"spec_value_text";s:9:"贵族黄";s:5:"image";s:0:"";}}', 9.50, NULL, 15, 0);
/*!40000 ALTER TABLE `js2system_goods_sku` ENABLE KEYS */;

-- 导出  表 js2system.js2system_goods_spec 结构
CREATE TABLE IF NOT EXISTS `js2system_goods_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '规格名称',
  `type_id` int(10) NOT NULL COMMENT '模型ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商品规格';

-- 正在导出表  js2system.js2system_goods_spec 的数据：3 rows
DELETE FROM `js2system_goods_spec`;
/*!40000 ALTER TABLE `js2system_goods_spec` DISABLE KEYS */;
INSERT INTO `js2system_goods_spec` (`id`, `name`, `type_id`) VALUES
	(1, '尺寸', 1),
	(2, '颜色', 1),
	(3, '内存', 2);
/*!40000 ALTER TABLE `js2system_goods_spec` ENABLE KEYS */;

-- 导出  表 js2system.js2system_goods_spec_value 结构
CREATE TABLE IF NOT EXISTS `js2system_goods_spec_value` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `spec_id` int(11) NOT NULL COMMENT '规格ID',
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '商品规格值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商品规格值';

-- 正在导出表  js2system.js2system_goods_spec_value 的数据：14 rows
DELETE FROM `js2system_goods_spec_value`;
/*!40000 ALTER TABLE `js2system_goods_spec_value` DISABLE KEYS */;
INSERT INTO `js2system_goods_spec_value` (`id`, `spec_id`, `name`) VALUES
	(1, 1, 'XS'),
	(2, 1, 'S'),
	(3, 1, 'M'),
	(4, 1, 'L'),
	(5, 1, 'XL'),
	(6, 1, 'XXL'),
	(7, 1, 'XXXL'),
	(8, 2, '红'),
	(9, 2, '橙'),
	(10, 2, '黄'),
	(11, 2, '绿'),
	(12, 2, '青'),
	(13, 2, '蓝'),
	(14, 2, '紫');
/*!40000 ALTER TABLE `js2system_goods_spec_value` ENABLE KEYS */;

-- 导出  表 js2system.js2system_goods_type 结构
CREATE TABLE IF NOT EXISTS `js2system_goods_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模型名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商品类型';

-- 正在导出表  js2system.js2system_goods_type 的数据：1 rows
DELETE FROM `js2system_goods_type`;
/*!40000 ALTER TABLE `js2system_goods_type` DISABLE KEYS */;
INSERT INTO `js2system_goods_type` (`id`, `name`) VALUES
	(1, '常规类型');
/*!40000 ALTER TABLE `js2system_goods_type` ENABLE KEYS */;

-- 导出  表 js2system.js2system_guestbook 结构
CREATE TABLE IF NOT EXISTS `js2system_guestbook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '姓名',
  `email` char(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '电子邮箱',
  `phone` char(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '电话号码',
  `content` varchar(17500) COLLATE utf8_unicode_ci NOT NULL COMMENT '留言内容',
  `user_id` int(10) DEFAULT '0' COMMENT '登录用户的ID',
  `time_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='留言表';

-- 正在导出表  js2system.js2system_guestbook 的数据：0 rows
DELETE FROM `js2system_guestbook`;
/*!40000 ALTER TABLE `js2system_guestbook` DISABLE KEYS */;
/*!40000 ALTER TABLE `js2system_guestbook` ENABLE KEYS */;

-- 导出  表 js2system.js2system_link 结构
CREATE TABLE IF NOT EXISTS `js2system_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `time_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='友链表';

-- 正在导出表  js2system.js2system_link 的数据：0 rows
DELETE FROM `js2system_link`;
/*!40000 ALTER TABLE `js2system_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `js2system_link` ENABLE KEYS */;

-- 导出  表 js2system.js2system_menu 结构
CREATE TABLE IF NOT EXISTS `js2system_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单名称',
  `list` varchar(17500) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'a:0:{}' COMMENT '菜单列表',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='菜单表';

-- 正在导出表  js2system.js2system_menu 的数据：1 rows
DELETE FROM `js2system_menu`;
/*!40000 ALTER TABLE `js2system_menu` DISABLE KEYS */;
INSERT INTO `js2system_menu` (`id`, `name`, `list`) VALUES
	(1, '菜单', 'a:5:{i:0;a:5:{s:2:"id";s:21:"ZEU6Vih1oGGDnA0Q-HLqD";s:5:"label";s:7:"windows";s:4:"link";i:1;s:4:"type";s:8:"category";s:8:"children";a:0:{}}i:1;a:5:{s:2:"id";s:21:"klqpqygKMPI8zuyww1S4D";s:5:"label";s:12:"前端笔记";s:4:"link";i:2;s:4:"type";s:8:"category";s:8:"children";a:3:{i:0;a:5:{s:2:"id";s:21:"ffMDnWSxREafX3RVNTB4m";s:5:"label";s:10:"javascript";s:4:"link";i:3;s:4:"type";s:8:"category";s:8:"children";a:0:{}}i:1;a:5:{s:2:"id";s:21:"5zK-oKu07-GN9xlZotgUc";s:5:"label";s:3:"css";s:4:"link";i:4;s:4:"type";s:8:"category";s:8:"children";a:0:{}}i:2;a:5:{s:2:"id";s:21:"OBVrA2TNwlBQmn1gHy-pr";s:5:"label";s:3:"vue";s:4:"link";i:6;s:4:"type";s:8:"category";s:8:"children";a:0:{}}}}i:2;a:5:{s:2:"id";s:21:"go5uc-rwNRQCdkBXSTDTN";s:5:"label";s:12:"开发工具";s:4:"link";i:5;s:4:"type";s:8:"category";s:8:"children";a:0:{}}i:3;a:5:{s:2:"id";s:21:"OkqCEfEvSxitDFfNyI2hk";s:5:"label";s:12:"关于……";s:4:"link";i:3;s:4:"type";s:4:"post";s:8:"children";a:0:{}}i:4;a:5:{s:2:"id";s:21:"SKzDbjUsS5pI43iwcGzdk";s:5:"label";s:6:"注册";s:4:"link";s:14:"/register.html";s:4:"type";s:6:"custom";s:8:"children";a:0:{}}}');
/*!40000 ALTER TABLE `js2system_menu` ENABLE KEYS */;

-- 导出  表 js2system.js2system_meta 结构
CREATE TABLE IF NOT EXISTS `js2system_meta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) NOT NULL COMMENT '文章ID',
  `key` char(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '字段名称',
  `value` char(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '字段内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='内容自定义字段表';

-- 正在导出表  js2system.js2system_meta 的数据：0 rows
DELETE FROM `js2system_meta`;
/*!40000 ALTER TABLE `js2system_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `js2system_meta` ENABLE KEYS */;

-- 导出  表 js2system.js2system_post 结构
CREATE TABLE IF NOT EXISTS `js2system_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '标题',
  `content` mediumtext COLLATE utf8_unicode_ci COMMENT '内容',
  `type` char(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '内容类型（post/single）',
  `author_id` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '作者ID',
  `category_id` char(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '分类ID，英文逗号分隔',
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '缩略图',
  `gallery` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cover` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '页面顶部封面',
  `view` int(10) NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶',
  `time_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `time_modify` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `time_delete` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `key` (`title`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章表';

-- 正在导出表  js2system.js2system_post 的数据：3 rows
DELETE FROM `js2system_post`;
/*!40000 ALTER TABLE `js2system_post` DISABLE KEYS */;
INSERT INTO `js2system_post` (`id`, `title`, `content`, `type`, `author_id`, `category_id`, `thumbnail`, `gallery`, `cover`, `view`, `top`, `time_add`, `time_modify`, `time_delete`) VALUES
	(1, '留言页面', '', 'single', 1, '0', '', NULL, NULL, 154, 0, '2021-05-02 22:52:28', '2022-06-14 22:42:38', NULL),
	(2, '联系我们', '<p>电子邮箱：im@mkinit.com</p>', 'single', 1, '0', '', NULL, NULL, 14, 0, '2021-05-03 23:59:32', '2022-03-11 16:24:36', NULL),
	(3, '关于……', '<p>个人博客是在学习ThinkPHP6和NUXT时开发的系统；</p>\n<p>后台基于vue2、element-ui前后端分离的模式；</p>\n<p>前台有传统的后端渲染（模板）和基于node.js的后端渲染NUXT框架（SSR）两种模式。</p>\n<p>基本功能包括：系统设置、菜单、分类、内容（标签、自定义字段、评论）、链接、文件、用户、角色权限等模块，已完善的有60个增删改查数据接口；</p>\n<p>部分功能参考了wordpress，比如文章的标签、自定义字段、媒体库。</p>\n<p><img src="https://im.mkinit.com/upload/20220309160504-1_900.png" alt="/upload/20220309160504-1.png" data-origin="https://im.mkinit.com/upload/20220309160504-1.png" /><img src="https://im.mkinit.com/upload/20220309160453-1_900.png" alt="/upload/20220309160453-1.png" data-origin="https://im.mkinit.com/upload/20220309160453-1.png" /><img src="https://im.mkinit.com/upload/20220309160443-1_900.png" alt="/upload/20220309160443-1.png" data-origin="https://im.mkinit.com/upload/20220309160443-1.png" /><img src="https://im.mkinit.com/upload/20220309160423-1_900.png" alt="/upload/20220309160423-1.png" data-origin="https://im.mkinit.com/upload/20220309160423-1.png" /><img src="https://im.mkinit.com/upload/20220309160656-2_900.png" alt="/upload/20220309160656-2.png" data-origin="https://im.mkinit.com/upload/20220309160656-2.png" /><img src="https://im.mkinit.com/upload/20220309160656-1_900.png" alt="/upload/20220309160656-1.png" data-origin="https://im.mkinit.com/upload/20220309160656-1.png" /><img src="https://im.mkinit.com/upload/20220309161334-1_900.png" alt="/upload/20220309161334-1.png" data-origin="https://im.mkinit.com/upload/20220309161334-1.png" /></p>', 'single', 1, '0', '', NULL, NULL, 51, 0, '2021-08-16 23:04:43', '2022-06-14 20:35:13', NULL);
/*!40000 ALTER TABLE `js2system_post` ENABLE KEYS */;

-- 导出  表 js2system.js2system_post_tag 结构
CREATE TABLE IF NOT EXISTS `js2system_post_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='内容和标签中间表';

-- 正在导出表  js2system.js2system_post_tag 的数据：21 rows
DELETE FROM `js2system_post_tag`;
/*!40000 ALTER TABLE `js2system_post_tag` DISABLE KEYS */;
INSERT INTO `js2system_post_tag` (`id`, `post_id`, `tag_id`) VALUES
	(1, 9, 6),
	(2, 10, 5),
	(3, 4, 12),
	(4, 4, 11),
	(5, 7, 10),
	(6, 7, 9),
	(7, 8, 8),
	(8, 8, 7),
	(9, 11, 3),
	(10, 11, 4),
	(11, 12, 2),
	(12, 12, 1),
	(13, 4, 13),
	(14, 13, 14),
	(15, 13, 15),
	(16, 14, 16),
	(17, 15, 16),
	(18, 16, 17),
	(19, 16, 18),
	(20, 17, 19),
	(21, 17, 20);
/*!40000 ALTER TABLE `js2system_post_tag` ENABLE KEYS */;

-- 导出  表 js2system.js2system_region 结构
CREATE TABLE IF NOT EXISTS `js2system_region` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` int(10) unsigned NOT NULL COMMENT '地区代码',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '地区名称',
  `parent_code` int(10) unsigned NOT NULL COMMENT '上级地区代码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=375 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='行政区域';

-- 正在导出表  js2system.js2system_region 的数据：374 rows
DELETE FROM `js2system_region`;
/*!40000 ALTER TABLE `js2system_region` DISABLE KEYS */;
INSERT INTO `js2system_region` (`id`, `code`, `name`, `parent_code`) VALUES
	(1, 1, '中国', 0),
	(2, 11, '北京市', 1),
	(3, 12, '天津市', 1),
	(4, 13, '河北省', 1),
	(5, 14, '山西省', 1),
	(6, 15, '内蒙古自治区', 1),
	(7, 21, '辽宁省', 1),
	(8, 22, '吉林省', 1),
	(9, 23, '黑龙江省', 1),
	(10, 31, '上海市', 1),
	(11, 32, '江苏省', 1),
	(12, 33, '浙江省', 1),
	(13, 34, '安徽省', 1),
	(14, 35, '福建省', 1),
	(15, 36, '江西省', 1),
	(16, 37, '山东省', 1),
	(17, 41, '河南省', 1),
	(18, 42, '湖北省', 1),
	(19, 43, '湖南省', 1),
	(20, 44, '广东省', 1),
	(21, 45, '广西壮族自治区', 1),
	(22, 46, '海南省', 1),
	(23, 50, '重庆市', 1),
	(24, 51, '四川省', 1),
	(25, 52, '贵州省', 1),
	(26, 53, '云南省', 1),
	(27, 54, '西藏自治区', 1),
	(28, 61, '陕西省', 1),
	(29, 62, '甘肃省', 1),
	(30, 63, '青海省', 1),
	(31, 64, '宁夏回族自治区', 1),
	(32, 65, '新疆维吾尔自治区', 1),
	(33, 1101, '市辖区', 11),
	(34, 1201, '市辖区', 12),
	(35, 1301, '石家庄市', 13),
	(36, 1302, '唐山市', 13),
	(37, 1303, '秦皇岛市', 13),
	(38, 1304, '邯郸市', 13),
	(39, 1305, '邢台市', 13),
	(40, 1306, '保定市', 13),
	(41, 1307, '张家口市', 13),
	(42, 1308, '承德市', 13),
	(43, 1309, '沧州市', 13),
	(44, 1310, '廊坊市', 13),
	(45, 1311, '衡水市', 13),
	(46, 1401, '太原市', 14),
	(47, 1402, '大同市', 14),
	(48, 1403, '阳泉市', 14),
	(49, 1404, '长治市', 14),
	(50, 1405, '晋城市', 14),
	(51, 1406, '朔州市', 14),
	(52, 1407, '晋中市', 14),
	(53, 1408, '运城市', 14),
	(54, 1409, '忻州市', 14),
	(55, 1410, '临汾市', 14),
	(56, 1411, '吕梁市', 14),
	(57, 1501, '呼和浩特市', 15),
	(58, 1502, '包头市', 15),
	(59, 1503, '乌海市', 15),
	(60, 1504, '赤峰市', 15),
	(61, 1505, '通辽市', 15),
	(62, 1506, '鄂尔多斯市', 15),
	(63, 1507, '呼伦贝尔市', 15),
	(64, 1508, '巴彦淖尔市', 15),
	(65, 1509, '乌兰察布市', 15),
	(66, 1522, '兴安盟', 15),
	(67, 1525, '锡林郭勒盟', 15),
	(68, 1529, '阿拉善盟', 15),
	(69, 2101, '沈阳市', 21),
	(70, 2102, '大连市', 21),
	(71, 2103, '鞍山市', 21),
	(72, 2104, '抚顺市', 21),
	(73, 2105, '本溪市', 21),
	(74, 2106, '丹东市', 21),
	(75, 2107, '锦州市', 21),
	(76, 2108, '营口市', 21),
	(77, 2109, '阜新市', 21),
	(78, 2110, '辽阳市', 21),
	(79, 2111, '盘锦市', 21),
	(80, 2112, '铁岭市', 21),
	(81, 2113, '朝阳市', 21),
	(82, 2114, '葫芦岛市', 21),
	(83, 2201, '长春市', 22),
	(84, 2202, '吉林市', 22),
	(85, 2203, '四平市', 22),
	(86, 2204, '辽源市', 22),
	(87, 2205, '通化市', 22),
	(88, 2206, '白山市', 22),
	(89, 2207, '松原市', 22),
	(90, 2208, '白城市', 22),
	(91, 2224, '延边朝鲜族自治州', 22),
	(92, 2301, '哈尔滨市', 23),
	(93, 2302, '齐齐哈尔市', 23),
	(94, 2303, '鸡西市', 23),
	(95, 2304, '鹤岗市', 23),
	(96, 2305, '双鸭山市', 23),
	(97, 2306, '大庆市', 23),
	(98, 2307, '伊春市', 23),
	(99, 2308, '佳木斯市', 23),
	(100, 2309, '七台河市', 23),
	(101, 2310, '牡丹江市', 23),
	(102, 2311, '黑河市', 23),
	(103, 2312, '绥化市', 23),
	(104, 2327, '大兴安岭地区', 23),
	(105, 3101, '市辖区', 31),
	(106, 3201, '南京市', 32),
	(107, 3202, '无锡市', 32),
	(108, 3203, '徐州市', 32),
	(109, 3204, '常州市', 32),
	(110, 3205, '苏州市', 32),
	(111, 3206, '南通市', 32),
	(112, 3207, '连云港市', 32),
	(113, 3208, '淮安市', 32),
	(114, 3209, '盐城市', 32),
	(115, 3210, '扬州市', 32),
	(116, 3211, '镇江市', 32),
	(117, 3212, '泰州市', 32),
	(118, 3213, '宿迁市', 32),
	(119, 3301, '杭州市', 33),
	(120, 3302, '宁波市', 33),
	(121, 3303, '温州市', 33),
	(122, 3304, '嘉兴市', 33),
	(123, 3305, '湖州市', 33),
	(124, 3306, '绍兴市', 33),
	(125, 3307, '金华市', 33),
	(126, 3308, '衢州市', 33),
	(127, 3309, '舟山市', 33),
	(128, 3310, '台州市', 33),
	(129, 3311, '丽水市', 33),
	(130, 3401, '合肥市', 34),
	(131, 3402, '芜湖市', 34),
	(132, 3403, '蚌埠市', 34),
	(133, 3404, '淮南市', 34),
	(134, 3405, '马鞍山市', 34),
	(135, 3406, '淮北市', 34),
	(136, 3407, '铜陵市', 34),
	(137, 3408, '安庆市', 34),
	(138, 3410, '黄山市', 34),
	(139, 3411, '滁州市', 34),
	(140, 3412, '阜阳市', 34),
	(141, 3413, '宿州市', 34),
	(142, 3415, '六安市', 34),
	(143, 3416, '亳州市', 34),
	(144, 3417, '池州市', 34),
	(145, 3418, '宣城市', 34),
	(146, 3501, '福州市', 35),
	(147, 3502, '厦门市', 35),
	(148, 3503, '莆田市', 35),
	(149, 3504, '三明市', 35),
	(150, 3505, '泉州市', 35),
	(151, 3506, '漳州市', 35),
	(152, 3507, '南平市', 35),
	(153, 3508, '龙岩市', 35),
	(154, 3509, '宁德市', 35),
	(155, 3601, '南昌市', 36),
	(156, 3602, '景德镇市', 36),
	(157, 3603, '萍乡市', 36),
	(158, 3604, '九江市', 36),
	(159, 3605, '新余市', 36),
	(160, 3606, '鹰潭市', 36),
	(161, 3607, '赣州市', 36),
	(162, 3608, '吉安市', 36),
	(163, 3609, '宜春市', 36),
	(164, 3610, '抚州市', 36),
	(165, 3611, '上饶市', 36),
	(166, 3701, '济南市', 37),
	(167, 3702, '青岛市', 37),
	(168, 3703, '淄博市', 37),
	(169, 3704, '枣庄市', 37),
	(170, 3705, '东营市', 37),
	(171, 3706, '烟台市', 37),
	(172, 3707, '潍坊市', 37),
	(173, 3708, '济宁市', 37),
	(174, 3709, '泰安市', 37),
	(175, 3710, '威海市', 37),
	(176, 3711, '日照市', 37),
	(177, 3713, '临沂市', 37),
	(178, 3714, '德州市', 37),
	(179, 3715, '聊城市', 37),
	(180, 3716, '滨州市', 37),
	(181, 3717, '菏泽市', 37),
	(182, 4101, '郑州市', 41),
	(183, 4102, '开封市', 41),
	(184, 4103, '洛阳市', 41),
	(185, 4104, '平顶山市', 41),
	(186, 4105, '安阳市', 41),
	(187, 4106, '鹤壁市', 41),
	(188, 4107, '新乡市', 41),
	(189, 4108, '焦作市', 41),
	(190, 4109, '濮阳市', 41),
	(191, 4110, '许昌市', 41),
	(192, 4111, '漯河市', 41),
	(193, 4112, '三门峡市', 41),
	(194, 4113, '南阳市', 41),
	(195, 4114, '商丘市', 41),
	(196, 4115, '信阳市', 41),
	(197, 4116, '周口市', 41),
	(198, 4117, '驻马店市', 41),
	(199, 4190, '省直辖县级行政区划', 41),
	(200, 4201, '武汉市', 42),
	(201, 4202, '黄石市', 42),
	(202, 4203, '十堰市', 42),
	(203, 4205, '宜昌市', 42),
	(204, 4206, '襄阳市', 42),
	(205, 4207, '鄂州市', 42),
	(206, 4208, '荆门市', 42),
	(207, 4209, '孝感市', 42),
	(208, 4210, '荆州市', 42),
	(209, 4211, '黄冈市', 42),
	(210, 4212, '咸宁市', 42),
	(211, 4213, '随州市', 42),
	(212, 4228, '恩施土家族苗族自治州', 42),
	(213, 4290, '省直辖县级行政区划', 42),
	(214, 4301, '长沙市', 43),
	(215, 4302, '株洲市', 43),
	(216, 4303, '湘潭市', 43),
	(217, 4304, '衡阳市', 43),
	(218, 4305, '邵阳市', 43),
	(219, 4306, '岳阳市', 43),
	(220, 4307, '常德市', 43),
	(221, 4308, '张家界市', 43),
	(222, 4309, '益阳市', 43),
	(223, 4310, '郴州市', 43),
	(224, 4311, '永州市', 43),
	(225, 4312, '怀化市', 43),
	(226, 4313, '娄底市', 43),
	(227, 4331, '湘西土家族苗族自治州', 43),
	(228, 4401, '广州市', 44),
	(229, 4402, '韶关市', 44),
	(230, 4403, '深圳市', 44),
	(231, 4404, '珠海市', 44),
	(232, 4405, '汕头市', 44),
	(233, 4406, '佛山市', 44),
	(234, 4407, '江门市', 44),
	(235, 4408, '湛江市', 44),
	(236, 4409, '茂名市', 44),
	(237, 4412, '肇庆市', 44),
	(238, 4413, '惠州市', 44),
	(239, 4414, '梅州市', 44),
	(240, 4415, '汕尾市', 44),
	(241, 4416, '河源市', 44),
	(242, 4417, '阳江市', 44),
	(243, 4418, '清远市', 44),
	(244, 4419, '东莞市', 44),
	(245, 4420, '中山市', 44),
	(246, 4451, '潮州市', 44),
	(247, 4452, '揭阳市', 44),
	(248, 4453, '云浮市', 44),
	(249, 4501, '南宁市', 45),
	(250, 4502, '柳州市', 45),
	(251, 4503, '桂林市', 45),
	(252, 4504, '梧州市', 45),
	(253, 4505, '北海市', 45),
	(254, 4506, '防城港市', 45),
	(255, 4507, '钦州市', 45),
	(256, 4508, '贵港市', 45),
	(257, 4509, '玉林市', 45),
	(258, 4510, '百色市', 45),
	(259, 4511, '贺州市', 45),
	(260, 4512, '河池市', 45),
	(261, 4513, '来宾市', 45),
	(262, 4514, '崇左市', 45),
	(263, 4601, '海口市', 46),
	(264, 4602, '三亚市', 46),
	(265, 4603, '三沙市', 46),
	(266, 4604, '儋州市', 46),
	(267, 4690, '省直辖县级行政区划', 46),
	(268, 5001, '市辖区', 50),
	(269, 5002, '县', 50),
	(270, 5101, '成都市', 51),
	(271, 5103, '自贡市', 51),
	(272, 5104, '攀枝花市', 51),
	(273, 5105, '泸州市', 51),
	(274, 5106, '德阳市', 51),
	(275, 5107, '绵阳市', 51),
	(276, 5108, '广元市', 51),
	(277, 5109, '遂宁市', 51),
	(278, 5110, '内江市', 51),
	(279, 5111, '乐山市', 51),
	(280, 5113, '南充市', 51),
	(281, 5114, '眉山市', 51),
	(282, 5115, '宜宾市', 51),
	(283, 5116, '广安市', 51),
	(284, 5117, '达州市', 51),
	(285, 5118, '雅安市', 51),
	(286, 5119, '巴中市', 51),
	(287, 5120, '资阳市', 51),
	(288, 5132, '阿坝藏族羌族自治州', 51),
	(289, 5133, '甘孜藏族自治州', 51),
	(290, 5134, '凉山彝族自治州', 51),
	(291, 5201, '贵阳市', 52),
	(292, 5202, '六盘水市', 52),
	(293, 5203, '遵义市', 52),
	(294, 5204, '安顺市', 52),
	(295, 5205, '毕节市', 52),
	(296, 5206, '铜仁市', 52),
	(297, 5223, '黔西南布依族苗族自治州', 52),
	(298, 5226, '黔东南苗族侗族自治州', 52),
	(299, 5227, '黔南布依族苗族自治州', 52),
	(300, 5301, '昆明市', 53),
	(301, 5303, '曲靖市', 53),
	(302, 5304, '玉溪市', 53),
	(303, 5305, '保山市', 53),
	(304, 5306, '昭通市', 53),
	(305, 5307, '丽江市', 53),
	(306, 5308, '普洱市', 53),
	(307, 5309, '临沧市', 53),
	(308, 5323, '楚雄彝族自治州', 53),
	(309, 5325, '红河哈尼族彝族自治州', 53),
	(310, 5326, '文山壮族苗族自治州', 53),
	(311, 5328, '西双版纳傣族自治州', 53),
	(312, 5329, '大理白族自治州', 53),
	(313, 5331, '德宏傣族景颇族自治州', 53),
	(314, 5333, '怒江傈僳族自治州', 53),
	(315, 5334, '迪庆藏族自治州', 53),
	(316, 5401, '拉萨市', 54),
	(317, 5402, '日喀则市', 54),
	(318, 5403, '昌都市', 54),
	(319, 5404, '林芝市', 54),
	(320, 5405, '山南市', 54),
	(321, 5406, '那曲市', 54),
	(322, 5425, '阿里地区', 54),
	(323, 6101, '西安市', 61),
	(324, 6102, '铜川市', 61),
	(325, 6103, '宝鸡市', 61),
	(326, 6104, '咸阳市', 61),
	(327, 6105, '渭南市', 61),
	(328, 6106, '延安市', 61),
	(329, 6107, '汉中市', 61),
	(330, 6108, '榆林市', 61),
	(331, 6109, '安康市', 61),
	(332, 6110, '商洛市', 61),
	(333, 6201, '兰州市', 62),
	(334, 6202, '嘉峪关市', 62),
	(335, 6203, '金昌市', 62),
	(336, 6204, '白银市', 62),
	(337, 6205, '天水市', 62),
	(338, 6206, '武威市', 62),
	(339, 6207, '张掖市', 62),
	(340, 6208, '平凉市', 62),
	(341, 6209, '酒泉市', 62),
	(342, 6210, '庆阳市', 62),
	(343, 6211, '定西市', 62),
	(344, 6212, '陇南市', 62),
	(345, 6229, '临夏回族自治州', 62),
	(346, 6230, '甘南藏族自治州', 62),
	(347, 6301, '西宁市', 63),
	(348, 6302, '海东市', 63),
	(349, 6322, '海北藏族自治州', 63),
	(350, 6323, '黄南藏族自治州', 63),
	(351, 6325, '海南藏族自治州', 63),
	(352, 6326, '果洛藏族自治州', 63),
	(353, 6327, '玉树藏族自治州', 63),
	(354, 6328, '海西蒙古族藏族自治州', 63),
	(355, 6401, '银川市', 64),
	(356, 6402, '石嘴山市', 64),
	(357, 6403, '吴忠市', 64),
	(358, 6404, '固原市', 64),
	(359, 6405, '中卫市', 64),
	(360, 6501, '乌鲁木齐市', 65),
	(361, 6502, '克拉玛依市', 65),
	(362, 6504, '吐鲁番市', 65),
	(363, 6505, '哈密市', 65),
	(364, 6523, '昌吉回族自治州', 65),
	(365, 6527, '博尔塔拉蒙古自治州', 65),
	(366, 6528, '巴音郭楞蒙古自治州', 65),
	(367, 6529, '阿克苏地区', 65),
	(368, 6530, '克孜勒苏柯尔克孜自治州', 65),
	(369, 6531, '喀什地区', 65),
	(370, 6532, '和田地区', 65),
	(371, 6540, '伊犁哈萨克自治州', 65),
	(372, 6542, '塔城地区', 65),
	(373, 6543, '阿勒泰地区', 65),
	(374, 6590, '自治区直辖县级行政区划', 65);
/*!40000 ALTER TABLE `js2system_region` ENABLE KEYS */;

-- 导出  表 js2system.js2system_role 结构
CREATE TABLE IF NOT EXISTS `js2system_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `description` char(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '角色描述',
  `modify` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可以修改角色权限',
  `time_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_modify` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色表';

-- 正在导出表  js2system.js2system_role 的数据：2 rows
DELETE FROM `js2system_role`;
/*!40000 ALTER TABLE `js2system_role` DISABLE KEYS */;
INSERT INTO `js2system_role` (`id`, `name`, `description`, `modify`, `time_add`, `time_modify`) VALUES
	(1, '系统管理员', '拥有后台所有权限，不允许删除、修改', 0, '2021-11-29 16:02:36', '2021-11-30 11:21:23'),
	(2, '普通用户', '没有任何后台权限，注册账号默认角色，不允许删除、修改', 0, '2021-11-29 16:02:36', '2021-11-30 11:21:19');
/*!40000 ALTER TABLE `js2system_role` ENABLE KEYS */;

-- 导出  表 js2system.js2system_role_action 结构
CREATE TABLE IF NOT EXISTS `js2system_role_action` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `action_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色和动作权限中间表';

-- 正在导出表  js2system.js2system_role_action 的数据：0 rows
DELETE FROM `js2system_role_action`;
/*!40000 ALTER TABLE `js2system_role_action` DISABLE KEYS */;
/*!40000 ALTER TABLE `js2system_role_action` ENABLE KEYS */;

-- 导出  表 js2system.js2system_setting 结构
CREATE TABLE IF NOT EXISTS `js2system_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `annotation` char(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '无用字段，备注用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统信息表（数据不能清）';

-- 正在导出表  js2system.js2system_setting 的数据：16 rows
DELETE FROM `js2system_setting`;
/*!40000 ALTER TABLE `js2system_setting` DISABLE KEYS */;
INSERT INTO `js2system_setting` (`id`, `key`, `value`, `annotation`) VALUES
	(1, 'site', 'js2api.com', '网站域名'),
	(2, 'site_name', '竹夭', '网站名称'),
	(3, 'site_keywords', '', '网站关键字'),
	(4, 'site_description', '竹夭的个人博客，记录一些前后端开发的内容，包括但不限于。', '网站描述'),
	(5, 'icp', '粤ICP备20058813号', '工信部备案号'),
	(6, 'logo', '/static/images/logo.jpg', 'logo'),
	(7, 'thumbnail_small', '300', '缩略图小'),
	(8, 'thumbnail_medium', '600', '缩略图中'),
	(9, 'thumbnail_large', '900', '缩略图大'),
	(10, 'upload_limit', '2', '文件上传大小限制'),
	(11, 'theme', 'blog', '网站模板（目录名称，前后端分离模式不可用）'),
	(12, 'ba', '5ab7eb2539b0d3e91ff9077125c1c6bf', '百度统计ID'),
	(13, 'product', '', '产品栏目，分类ID'),
	(14, 'news', '', '新闻栏目，分类ID'),
	(15, 'site_title', '个人博客', '网站标题'),
	(16, 'police', '', '公安备案号');
/*!40000 ALTER TABLE `js2system_setting` ENABLE KEYS */;

-- 导出  表 js2system.js2system_tag 结构
CREATE TABLE IF NOT EXISTS `js2system_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '标签名',
  `keywords` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '关键词',
  `description` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='内容标签';

-- 正在导出表  js2system.js2system_tag 的数据：20 rows
DELETE FROM `js2system_tag`;
/*!40000 ALTER TABLE `js2system_tag` DISABLE KEYS */;
INSERT INTO `js2system_tag` (`id`, `name`, `keywords`, `description`) VALUES
	(1, '插件', NULL, NULL),
	(2, '组件', NULL, NULL),
	(3, 'vscode', NULL, NULL),
	(4, 'ftp', NULL, NULL),
	(5, 'npm', NULL, NULL),
	(6, 'nvm', NULL, NULL),
	(7, '内网穿透', NULL, NULL),
	(8, 'ngrok', NULL, NULL),
	(9, 'css', NULL, NULL),
	(10, '轮播图', NULL, NULL),
	(11, 'win10', NULL, NULL),
	(12, 'win7', NULL, NULL),
	(13, '系统安装', NULL, NULL),
	(14, 'html', NULL, NULL),
	(15, 'video', NULL, NULL),
	(16, 'loading', NULL, NULL),
	(17, 'less', NULL, NULL),
	(18, 'localStorage', NULL, NULL),
	(19, 'php', NULL, NULL),
	(20, 'thinkphp', NULL, NULL);
/*!40000 ALTER TABLE `js2system_tag` ENABLE KEYS */;

-- 导出  表 js2system.js2system_user 结构
CREATE TABLE IF NOT EXISTS `js2system_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `password` char(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户密码',
  `nickname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户昵称',
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户头像',
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户邮箱',
  `phone` char(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户手机号',
  `time_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间',
  `role_id` int(10) unsigned NOT NULL DEFAULT '2' COMMENT '角色ID（默认为普通用户）',
  `time_delete` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nickname` (`nickname`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户表';

-- 正在导出表  js2system.js2system_user 的数据：2 rows
DELETE FROM `js2system_user`;
/*!40000 ALTER TABLE `js2system_user` DISABLE KEYS */;
INSERT INTO `js2system_user` (`id`, `username`, `password`, `nickname`, `avatar`, `email`, `phone`, `time_add`, `role_id`, `time_delete`) VALUES
	(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', NULL, 'im@mkinit.com', '13800138000', '1990-02-08 00:00:00', 1, NULL),
	(2, 'mkinit', 'e10adc3949ba59abbe56e057f20f883e', 'mkinit', NULL, 'im3@mkinit.com', '13800138001', '2021-11-26 15:40:51', 2, NULL);
/*!40000 ALTER TABLE `js2system_user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
