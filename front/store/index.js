const cookieparser = process.server ? require('cookieparser') : undefined
import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
const store = () => new Vuex.Store({
	state: () => ({
		user: null,
		menu_list: [], //菜单 []
		setting_info: {}, //系统信息 {}
		categories: [], //网站分类 []
		links: [], //友情连接 []
		post: {}, //当前正在浏览的文章
	}),
	getters: {},
	mutations: {
		//通用保存数据方法
		setData(state, data) {
			state[data.key] = data.data
		}
	},
	actions: {
		async nuxtServerInit({ state, commit }, { app, req }) { //自动执行的异步请求
			if (req.headers.cookie) {
				const parsed = cookieparser.parse(req.headers.cookie)
				commit('setData', {
					key: 'user',
					data: parsed.user ? JSON.parse(parsed.user) : null
				})
			}

			try {
				//菜单
				const menu = await app.$axios.get('api/menu')
				commit('setData', { key: 'menu_list', data: menu.data.data })

				//网站设置信息
				const setting = await app.$axios.get('api/setting')
				commit('setData', { key: 'setting_info', data: setting.data.data })

				//网站分类
				const categories = await app.$axios.get('api/categories')
				commit('setData', { key: 'categories', data: categories.data.data })

				//友情链接
				const links = await app.$axios.get('api/links')
				commit('setData', { key: 'links', data: links.data.data })
			} catch (err) {
				console.log(err.response)
			}
		}
	}
})

export default store
