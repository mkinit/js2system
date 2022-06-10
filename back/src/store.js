import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
import { apiFileList } from '@/request/api.js'
const user = localStorage.getItem('user')
export default new Vuex.Store({
	state: {
		user: user ? JSON.parse(user) : null, //当前用户信息
		sidebar_collapse: false, //边栏展开状态
		files: null, //文件列表响应数据
	},
	mutations: {
		update(state, data) { //更新数据 data={key:key,data:data}
			state[data.key] = data.data
		}
	},
	actions: {
		actionFileList({ commit }, page = 1) {
			apiFileList(page).then(res => {
				commit('update', {
					key: 'files',
					data: res
				})
			})
		}
	}
})