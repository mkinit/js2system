//退出登陆公共方法
import store from '@/store.js'
import router from '@/router/router.js'
export default () => {

	//清除token
	localStorage.removeItem('user')

	//清除用户信息
	store.commit('update', {
		key: 'user',
		data: null,
	})
	
	router.go(0)
}