import Vue from 'vue'
import App from './App.vue'
import router from './router/router.js'
import store from './store'
import './assets/less/style.less' //全局样式

Vue.config.productionTip = false

//全局混入
import mixins from '@/mixins/mixins.js'
Vue.mixin(mixins)

//element-ui 组件
import {
	Select,
	Option,
	Button,
	Menu,
	Submenu,
	MenuItem,
	Checkbox,
	Input,
	Radio,
	Cascader,
	Tree,
	Pagination,
	Image,
	Switch,
	Dialog,
	Divider,
	DatePicker,
	TimePicker,
	Tag,
	Autocomplete,
	Tabs,
	TabPane,
	Table,
	TableColumn,
	Form,
	FormItem
} from 'element-ui'
Vue.use(Select).use(Option).use(Button).use(Menu).use(Submenu).use(MenuItem).use(Checkbox).use(Input).use(Radio).use(Cascader).use(Tree).use(Pagination).use(Image).use(Switch).use(Dialog).use(Divider).use(DatePicker).use(TimePicker).use(Tag).use(Autocomplete).use(Tabs).use(TabPane).use(Table).use(TableColumn).use(Form).use(FormItem)
Vue.prototype.$ELEMENT = { size: 'small' }

//element-ui API
import { Message, MessageBox } from 'element-ui'
Vue.prototype.$message = Message
Vue.prototype.$messageBox = MessageBox

//全局组件
import permission from '@/components/permission/permission.vue'
Vue.component('c-permission', permission)

//路由拦截
router.beforeEach((to, from, next) => {
	//路由跳转之前
	document.title = to.meta.title + ' - JS2system'
	if (
		to.meta.must_login &&
		!(localStorage.getItem('token') || sessionStorage.getItem('token'))
	) {
		//登录拦截
		next({
			path: '/login',
			query: {
				redirect: to.name,
			},
		})
	} else {
		next()
	}
})
router.afterEach(() => {
	//路由跳转之后
	window.scrollTo(0, 0) //跳转页面返回顶部
})

new Vue({
	router,
	store,
	render: h => h(App),
}).$mount('#app')