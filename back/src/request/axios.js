import axios from 'axios'
import config from '@/config.js'

const env = process.env.NODE_ENV
const dev = env !== 'production'
axios.defaults.baseURL = dev ? '' : config.api_url

import { Message, Loading } from 'element-ui'
let loadingInstance

//import logout from '@/common/logout.js'

//请求拦截
axios.interceptors.request.use(
	(request) => {
		loadingInstance = Loading.service()
		if (localStorage.getItem('user')) {
			request.headers.token = JSON.parse(localStorage.getItem('user')).token
		}
		return request
	},
	(error) => {
		return new Promise.reject(error)
	}
)

//响应拦截
axios.interceptors.response.use(
	(success) => {
		//成功，HTTP状态：200
		loadingInstance.close()
		return Promise.resolve(success.data)
	},
	(fail) => {
		//失败，HTTP状态：200以外的
		loadingInstance.close()
		const status = fail.response.status
		const msg = status == 500 ? '服务器错误' : fail.response.data.msg
		switch (status) {
			case 401:
				Message.error(msg)
				//logout()
				break
			default:
				Message.error(msg)
				break
		}
		return Promise.reject(fail.response.data.msg)
	}
)

export default axios