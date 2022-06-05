import Vue from "vue"

//过滤html标签
Vue.filter('removeTag', value => {
	return value.replace(/<\/?.+?>/g, '')
})

//截取字数
Vue.filter('cutString', (value, length) => {
	return value.slice(0, length) + '……'
})
