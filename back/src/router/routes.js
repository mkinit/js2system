export default [{
		//404
		name: 'error',
		path: '*',
		component: () => import('../view/404'),
		meta: {
			title: '404',
			keep_alive: true, //缓存
		},
		roles: ['admin'], //可访问的角色
	},
	{
		//首页
		name: 'home',
		path: '/',
		component: () => import('../view/home/home'),
		meta: {
			title: '系统管理',
			must_login: true
		},
	},
	{
		//系统
		name: 'setting',
		path: '/setting',
		component: () => import('../view/setting/setting'),
		meta: {
			title: '系统设置',
			must_login: true
		},
	},
	{
		//公司信息
		name: 'company',
		path: '/company',
		component: () => import('../view/company/company'),
		meta: {
			title: '公司信息',
			must_login: true
		},
	},
	{
		//菜单
		name: 'menu',
		path: '/menu',
		component: () => import('../view/menu/menu'),
		meta: {
			title: '菜单管理',
			must_login: true
		},
	},
	{
		//内容管理
		name: 'post',
		path: '/post',
		component: () => import('../view/post/post'),
		meta: {
			title: '内容管理',
			must_login: true,
			keep_alive: true, //缓存
		}
	},
	{
		//编辑内容，默认为创建新内容，action=edit时候才是编辑
		name: 'post-edit',
		path: '/post/edit',
		component: () => import('../view/post/edit/edit'),
		meta: {
			title: '编辑内容',
			must_login: true
		},
	},
	{
		//分类管理
		name: 'category',
		path: '/category',
		component: () => import('../view/category/category'),
		meta: {
			title: '分类管理',
			must_login: true
		},
	},
	{
		//分类管理
		name: 'tag',
		path: '/tag',
		component: () => import('../view/tag/tag'),
		meta: {
			title: '标签管理',
			must_login: true
		},
	},
	{
		//链接管理
		name: 'link',
		path: '/link',
		component: () => import('../view/link/link'),
		meta: {
			title: '链接管理',
			must_login: true
		},
	},
	{
		//留言管理
		name: 'guestbook',
		path: '/guestbook',
		component: () => import('../view/guestbook/guestbook'),
		meta: {
			title: '留言管理',
			must_login: true
		},
	},
	{
		//评论管理
		name: 'comment',
		path: '/comment',
		component: () => import('../view/comment/comment'),
		meta: {
			title: '评论管理',
			must_login: true
		},
	},
	{
		//文件管理
		name: 'file',
		path: '/file',
		component: () => import('../view/file/file'),
		meta: {
			title: '文件管理',
			must_login: true
		},
	},
	{
		//轮播图管理
		name: 'banner',
		path: '/banner',
		component: () => import('../view/banner/banner'),
		meta: {
			title: '轮播图管理',
			must_login: true
		},
	},
	{
		//用户管理
		name: 'user',
		path: '/user',
		component: () => import('../view/user/user'),
		meta: {
			title: '用户列表',
			must_login: true
		},
	},
	{
		//当前用户资料
		name: 'my',
		path: '/my',
		component: () => import('../view/my/my'),
		meta: {
			title: '用户资料',
			must_login: true
		},
	},
	{
		//用户登录
		name: 'login',
		path: '/login',
		component: () => import('../view/user/login'),
		meta: {
			title: '用户登录',
		},
	},
	{
		//重置密码
		name: 'password-reset',
		path: '/password-reset',
		component: () => import('../view/user/password-reset'),
		meta: {
			title: '重置密码',
		},
	},
	{
		//角色管理
		name: 'role',
		path: '/role',
		component: () => import('../view/role/role'),
		meta: {
			title: '角色管理',
			must_login: true
		}
	},
	{
		//编辑角色
		name: 'role-edit',
		path: '/role/edit/:id',
		component: () => import('../view/role/role-edit/role-edit'),
		meta: {
			title: '编辑角色',
			must_login: true
		}
	}
]