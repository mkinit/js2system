## JS2SYSTEM 后端服务应用

### Apache伪静态
- 程序目录（虚拟主机部署需要）
```
<IfModule mod_rewrite.c>
	RewriteEngine on
	RewriteBase /
	RewriteCond %{REQUEST_URI} !^/public/
	RewriteRule ^(.*)$ public/$1?Rewrite [L,QSA]
</IfModule>
```
- public目录（网站入口）
``` 
<IfModule mod_rewrite.c>
	Options +FollowSymlinks -Multiviews
	DirectoryIndex index.php
	RewriteEngine On
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^(.*)$ index.php [L,E=PATH_INFO:$1]
</IfModule>
```

### Nginx伪静态
```
location / {
	if (!-e $request_filename){
		rewrite ^/(.*)$ /index.php?s=/$1 last;
	}
}
```

### 工具库
- token：php-jwt
- 图像处理：Intervention/image

### 接口数据传输类型
- 文本数据类型：application/json
- 二进制类型：multipart/form-data

### 自定义配置文件
- /config/key.php		各种密钥
	- $token_key			生成token时需要用到的密钥字符串，使用前系统前需要重新自定义
	- $email_host		代发邮件 SMTP 服务器地址
	- $email_port		代发邮件服务器端口
	- $email_username	代发邮件用户名
	- $email_password	代发邮件密码
	```
	//示例
	return [
		'token_key' => 'what the ?', //生成token时需要用到的密钥字符串，使用前系统前需要重新自定义
		'email_host' => 'smtp.exmail.qq.com', //代发邮件 SMTP 服务器地址
		'email_port' => '465', //代发邮件服务器端口
		'email_username' => 'im@mkinit.com', //代发邮件用户名
		'email_password' => '代发邮件密码', //代发邮件密码
	];
	```
- /public/theme/md5.txt	自动生成的网站主题MD5文件，用于前端模板静态资源自动更新


### 页面控制器功能
- 静态资源（CSS和JS）打包后不能包含map的注释，否则获取当前REQUEST_URI时不准确
- ☑ 主页
- ☑ 列表页
- ☑ 详情页
	- ☑ 评论
	- ☑ 嵌套评论
	- ☑ 回复邮件通知

- ☑ 留言页
- ☑ 搜索页
- ☑ 标签页
- ☑ 登录
- ☑ 退出登录
- ☑ 注册
- ☑ 用户中心
	- 站内通知
	- 修改密码
	- 修改邮箱
	- 修改资料（昵称）
	- 找回账号
	- 忘记密码

### 模板结构
主题目录位于web根目录的theme，后台自动获取主题目录下的每一个文件夹作为主题选项，创建好主题目录和对应页面即可，模板结构：
- layout.html			模版布局
- 404.html
- index/index.html		首页
- post/index.html		内容详情
- category/index.html	分类内容列表
- search/index.html		搜索页
- tag/index.html		标签页
- user/index.html		用户页
- login/index.html		登录页
- register/index.html	注册页

### 模板变量

####全局变量
- $user					已登录用户信息
	- $username			用户名
	- $nickname			昵称
	- $avatar			头像
	- $email			电子邮箱
	- $phone			手机
	- $time_add			注册时间
	- $role		
		- $name			角色名
		- $description	角色描述
		- $action		权限列表
- $controller_action	控制器和方法，用于区分每个页面
- $setting				网站信息
	- $site				网站域名
	- $site_name		网站名称
	- $site_title		网站标题
	- $site_keywords	网站关键词
	- $site_description	网站描述
	- $icp				备案号
	- $logo
	- $thumbnail_small	缩略图尺寸 小
	- $thumbnail_medium	缩略图尺寸 中
	- $thumbnail_large	缩略图尺寸 大
	- $theme			当前主题目录
	- $ba				百度统计ID
	- $product			产品分类ID（一级分类）
	- $news				新闻分类ID（一级分类）
	- $police			公安备案号
- $company				公司信息
	- $name				公司名
	- $company_code		营业执照代码
	- $tel				固话
	- $fax				传真
	- $phone			移动电话
	- $address			地址
	- $zip				邮编
	- $email			邮件
	- $wechat			微信二维码图片
	- $qq				QQ号
- $menu_list			菜单列表，后台支持多个菜单的创建，调用菜单使用$menu_list[index].list
	- $name				菜单名
	- $list				菜单数据
		- $label		菜单项名称
		- $link			菜单项链接
		- $children		子菜单
- $banners				轮播图
	- $image_url		图片地址
	- $link				链接
	- $blank			是否新窗口打开
- $links				友情链接
	- $name				链接名称
	- $link				链接地址
- $categories			所有分类
	- $id				分类ID
	- $name				分类名
	- $keywords			关键词
	- $description		描述
	- $cover			封面
- $product_child_cate	产品子分类（如果有设置的话）
	- 变量参考内容详情
- $news_child_cate		新闻子分类（如果有设置的话）
	- 变量参考内容详情

#### 内容详情变量
- $post
	- $title			内容标题
	- $content			内容详情
	- $type				内容类型
	- $author
		- $id
		- $nickname		昵称
		- $time_add		注册时间
		- $time_delete
	- $category
		- $id
		- $name			分类名
		- $parent_id	上级分类ID
		- $keywords		关键词
		- $description	描述
		- $cover		封面
	- $tags[]
		- $id
		- $name			标签名
		- $keywords		关键词
		- $description	描述
	- $meta[]
		- $id
		- $post_id		文章ID
		- $key			自定义属性键
		- $value		自定义属性值
	- $thumbnail		缩略图
	- $cover			封面
	- $view				阅读次数
	- $top				是否置顶
	- $time_add			发布时间
	- $time_modify		修改时间
	- $time_delete		删除时间

#### 首页
- $posts				内容列表
- $page					分页（html），使用 {$page|raw} 输出

#### 分类页
- $title				页面标题
- $category				当前分类
- $category_child		子分类
- $posts				文章列表
- $crumbs				面包屑导航

#### 内容页
- $title				页面标题
- $post					文章
- $recommends			当前文章分类其他文章
- $crumbs				面包屑导航

#### 搜索页
- $title				页面标题
- $posts				文章列表
- $crumbs				面包屑导航

#### 标签页
- $title				页面标题
- $tag					当前标签
- $posts				文章列表
- $crumbs				面包屑导航

#### 用户页
- $title				页面标题

#### 登录页
- $title				页面标题
- $username				用户名
- $password				密码

#### 注册页
- $title				页面标题
- $email				电子邮箱
- $verify_code			邮件验证码
- $username				用户名
- $password				密码
- $message				提交注册的提示

### 内置标签（标签扩展）
- 文章列表
	```
	/*
		$name String 自定义循环中的value变量名称，防止与页面变量重名导致覆盖数据，最终变量名：$name+_post
		$limit Number 文章数量
		$cate Number 分类ID，限制文章分类
		$order String[rand] 排序字段，rand：随机，view：浏览次数
	*/
	{Article:list name="news" limit="6" cate="$post['category']['id']" order="rand"}
		<li>{$news_post['title']}</li>
		{/Article:list}
	```

- 系统版本(用户修改主题时自动更新静态资源)
	```
	{System:version}
	```

### 接口功能

#### 数据统计
- 分类数量
- ☑ 分类数量
- ☑ 文章数量
- ☑ 单页数量
- ☑ 评论数量
- ☑ 文件数量
- ☑ 留言数量
- ☑ 主机系统
- ☑ 域名
- ☑ 框架版本
- ☑ PHP版本
- ☑ 数据库驱动
- 上传限制大小

#### 权限管理
- ☑ 权限列表
- ☑ 角色列表
- ☑ 角色信息
- ☑ 创建角色
- ☑ 删除角色
- ☑ 修改角色信息
- ☑ 角色权限分配
- ☑ 用户角色分配

#### 操作记录

#### 用户系统（User）
- 第三方登陆
- ☑ 注册
	- ☑ 邮件验证
		- ☑ 限制频繁请求接口
	- 发送欢迎注册
- ☑ 登录
- ☑ 当前用户信息 
- ☑ 修改当前用户信息
- ☑ 修改密码
- 修改邮件
- 修改手机
- 后台添加用户
- ☑ 删除用户（软删除）
	- ☑ 批量删除
- 恢复用户
- ☑ 获取单个用户信息
- ☑ 修改单个用户信息
- ☑ 获取用户列表
- ☑ 用户评论列表
- 点赞文章
- 收藏文章
- 收藏商品

#### 会员
	- 等级
 	- 积分

#### 公司信息
- 设置字段
	- ☑ 公司名称
	- ☑ 营业执照代码
	- ☑ 电话号码
	- ☑ 传真号码
	- ☑ 手机号码
	- ☑ 公司地址
	- ☑ 邮政编码
	- ☑ 电子邮箱
	- ☑ 联系人微信二维码
	- ☑ QQ
- ☑ 公司信息
- ☑ 更新公司信息

#### 系统设置（Setting）
- 设置字段
	- ☑ 网站域名（用于后台预览网站按钮链接、邮件通知附带网站地址）
	- ☑ 网站名称
	- ☑ 网站标题
	- ☑ 网站关键词
	- ☑ 网站描述
	- ☑ 网站icp备案号
	- ☑ 公网安备
	- ☑ 网站logo
	- ☑ 缩略图（大）
	- ☑ 缩略图（中）
	- ☑ 缩略图（小）
	- ☑ 上传文件大小限制
	- ☑ 网站主题
	- ☑ 百度统计ID
	- ☑ 产品栏目设置
	- ☑ 新闻栏目设置
	- 开放注册开关
- ☑ 系统信息
- ☑ 更新系统信息

#### 友情链接系统（Link）
- ☑ 链接列表
- ☑ 单个友情链接
- ☑ 添加链接
- ☑ 更新友情链接
- ☑ 删除友情链接
	- ☑ 批量删除

#### 文件系统（File）
- ☑ 单文件上传
- ☑ 多文件上传
- ☑ 文件列表
- ☑ 文件删除
	- ☑ 批量删除
- ☑ 更新描述
- ☑ 文件搜索（过滤）
- ☑ 数据库记录

#### 分类系统（Category）
- ☑ 分类列表
- ☑ 分类详情
- ☑ 添加分类
- ☑ 更新分类
- ☑ 删除分类
	- ☑ 批量删除

#### 标签系统
- ☑ 标签列表
- ☑ 添加标签
- ☑ 更新标签
- ☑ 删除标签

#### 内容系统（Post）
- 类型（type）
	- ☑ 文章：post，默认，不需传参
	- ☑ 单页：single
- ☑ 内容列表（最新文章列表）
- ☑ 分类内容列表（分类过滤）
- ☑ 内容详情
- ☑ 添加内容
	- ☑ 添加自定义字段
	- ☑ 删除自定义字段
	- ☑ 置顶（默认否）
	- ☑ 内容插入文件/媒体从媒体库选择
	- ☑ 置顶独立接口
	- ☑ 添加/删除标签
- ☑ 更新内容
	- ☑ 添加自定义字段
	- ☑ 删除自定义字段
- ☑ 删除内容
	- ☑ 批量删除
- ☑ 回收站
- ☑ 真实删除
	- ☑ 批量真实删除
- ☑ 恢复内容
- 点赞
- 隐藏内容（输入域名可看）

#### 评论系统（嵌套设计）
- ☑ 发布评论
	- ☑ 限制频繁请求接口
- ☑ 评论列表
- ☑ 评论邮件通知
- 删除评论
	- 批量删除

#### 搜索系统（Search）
- ☑ 内容搜索
- ☑ 单页搜索

#### 菜单系统
支持创建多个菜单
- ☑ 菜单列表
- ☑ 添加菜单
- ☑ 删除菜单
- ☑ 更新菜单

#### 留言系统（guestbook）
- 访客信息（IP，浏览器，操作系统）
- ☑ 发布留言
	- ☑ 限制频繁请求接口
- ☑ 留言列表
- ☑ 留言邮件通知
- 删除留言
	- 批量删除
- 留言备注

#### 首页轮播banner
- ☑ 轮播图列表
- ☑ 添加轮播图
- ☑ 更新轮播图
- ☑ 删除轮播图


#### 权限系统
- 权限控制的路由(route/app.php)需要定义action_name作为中文描述：模块名称-权限名称，然后使用权限初始化接口生成权限表
- ☑ 修改角色信息
- ☑ 添加角色
- ☑ 角色授权
- 菜单权限（前端路由显示）

### 商品系统
- 数据库中商品相关的数据表和控制器是在研究商品结构的时候写的，对于博客无用，删除不影响

#### 商品分类
- 添加分类
- 删除分类
- 修改分类

#### 商品类型
- 添加类型
- 删除类型
- 修改类型

#### 商品规格
- 添加规格
- 删除规格
- 修改规格

#### 商品
- 添加商品
- 删除商品
- 修改商品



