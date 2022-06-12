import axios from './axios.js'

//获取系统环境和统计
export const apiSystem = () => {
	return axios.get('/system')
}

//清理缓存
export const apiClearCache = () => {
	return axios.get('/clear-cache')
}

//获取设置
export const apiSetting = () => {
	return axios.get('/setting')
}

//更新设置
export const apiSettingUpdate = data => {
	return axios.put('/setting', data)
}

//获取公司信息
export const apiCompany = () => {
	return axios.get('/company')
}

//更新公司信息
export const apiCompanyUpdate = data => {
	return axios.put('/company', data)
}

//登录
export const apiLogin = ({ username, password }) => {
	return axios.post('/user/login', {
		username,
		password,
	})
}

//用户列表
export const apiUserList = (page = 1) => {
	return axios.get('/users', { params: { page } })
}

//修改用户信息
export const apiUserModify = data => {
	return axios.put('/users/' + data.id, data)
}

//修改当前用户信息
export const apiUserUpdate = data => {
	return axios.put('/user/update', data)
}

//重置密码
export const apiPasswordReset = data => {
	return axios.put('/user/password-reset', data)
}

//修改当前用户邮箱
export const apiEmailUpdate = data => {
	return axios.put('/user/email-modify', data)
}

//角色列表
export const apiRoleList = () => {
	return axios.get('/role')
}

//角色信息
export const apiRole = id => {
	return axios.get('/role/' + id)
}

//添加角色
export const apiRoleAdd = (name, description) => {
	return axios.post('/role/add', { name, description })
}

//修改角色信息
export const apiRoleUpdate = ({ id, name, description }) => {
	return axios.put('/role/' + id, { name, description })
}

//修改角色权限
export const apiRoleUpdateAction = (id, action) => {
	return axios.post('/role/action', { id, action })
}

//删除角色
export const apiRoleDelete = (ids) => {
	return axios.delete('/role?ids=' + ids)
}

//权限列表
export const apiActionList = () => {
	return axios.get('/action')
}

//文件列表
export const apiFileList = (page = 1, keywords = '', page_size = 24) => {
	return axios.get('/files', { params: { keywords, page, page_size } })
}

//文件上传
export const apiUpload = (data) => {
	return axios.post('/files', data, { headers: { timeout: 60000 } })
}

//文件删除
export const apiFileDelete = ids => {
	return axios.delete('/files', { params: { ids } })
}

//更新文件描述
export const apiFileUpdate = file => {
	return axios.put('/files/' + file.id, { desc: file.desc })
}

//获取菜单
export const apiMenuList = () => {
	return axios.get('/menu')
}

//添加菜单
export const apiMenuAdd = data => {
	return axios.post('/menu', data)
}

//更新菜单
export const apiMenuUpdate = (data) => {
	return axios.put('/menu/' + data.id, data)
}

//删除菜单
export const apiMenuDelete = id => {
	return axios.delete('/menu/' + id)
}

//分类列表
export const apiCategoryList = () => {
	return axios.get('/categories')
}

//添加分类
export const apiCategoryAdd = data => {
	return axios.post('/categories/add', data)
}

//更新分类
export const apiCategoryUpdate = data => {
	return axios.put('/categories/' + data.id, data)
}

//删除分类
export const apiCategoryDelete = ids => {
	return axios.delete('/categories', { params: { ids } })
}

//标签列表
export const apiTagList = (page = 1) => {
	return axios.get('/tags', { params: { page } })
}

//添加标签
export const apiTagAdd = data => {
	return axios.post('/tags/add', data)
}

//更新标签
export const apiTagUpdate = data => {
	return axios.put('/tags/' + data.id, data)
}

//删除标签
export const apiTagDelete = ids => {
	return axios.delete('/tags', { params: { ids } })
}

//自定义字段键列表
export const apiMeteKey = () => {
	return axios.get('/meta/key')
}

/*
	内容列表
	@param page Number 分页
	@param type String 类型 post：文章；single：单页
	@param category_id Number 分类ID
	@param trashed Number 回收站 0||1
*/
export const apiPostsList = (page = 1, type = 'post', category_id = null, trashed = 0) => {
	return axios.get('/posts', { params: { page, type, category_id, trashed } })
}

//内容详情
export const apiPost = id => {
	return axios.get('/posts/' + id)
}

/*
	添加内容
*/
export const apiPostAdd = (data) => {
	return axios.post('/posts/add', data)
}

//更新内容
export const apiPostUpdate = data => {
	return axios.put('/posts/' + data.id, data)
}

/*
	内容置顶
	top 0||1
*/
export const apiPostSetTop = (id, top) => {
	return axios.put('/posts/set-top/' + id, { top })
}

/*
	删除内容
	force 真实删除 0||1
*/
export const apiPostDelete = (ids, force = 0) => {
	return axios.delete('/posts', { params: { ids, force } })
}

//还原文章
export const apiPostRestore = ids => {
	return axios.post('/posts/restore', { ids })
}

/*
	搜索文章
	type 类型 post：文章；single：单页
	trashed 回收站 0||1
*/
export const apiPostSearch = (keywords, type = '', trashed = 0, page = 1) => {
	return axios.get('/search', { params: { keywords, type, trashed, page } })
}

//链接列表
export const apiLinkList = page => {
	return axios.get('/links', { params: { page } })
}

//更新链接
export const apiLinkUpdate = data => {
	return axios.put('/links/' + data.id, data)
}

//删除链接
export const apiLinkDelete = ids => {
	return axios.delete('/links', { params: { ids } })
}

//添加链接
export const apiLinkAdd = data => {
	return axios.post('/links/add', data)
}

//留言列表
export const apiGuestbookList = (page = 1) => {
	return axios.get('/guestbook', { params: { page } })
}

//评论列表
export const apiCommentList = (page = 1) => {
	return axios.get('/comments', { params: { page } })
}

//获取轮播图列表
export const apiBannerList = () => {
	return axios.get('/banners')
}

//更新轮播图
export const apiBannerUpdate = data => {
	return axios.put('/banners/' + data.id, data)
}

//添加轮播图
export const apiBannerAdd = data => {
	return axios.post('/banners', data)
}

//删除轮播图
export const apiBannerDelete = ids => {
	return axios.delete('/banners', { params: { ids } })
}

//获取验证码
export const apiVerifyCode = email => {
	return axios.get('/mail/verify-code', { params: { email } })
}