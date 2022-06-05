const config = require('./config.js')
export default {
	// Global page headers: https://go.nuxtjs.dev/config-head
	head: {
		htmlAttrs: {
			lang: 'zh'
		},
		meta: [
			{ charset: 'utf-8' },
			{ name: 'webkit', content: 'webkit' },
			{ 'http-equiv': 'X-UA-Compatible', content: 'IE=edge,chrome=1' },
			{ name: 'viewport', content: 'width=device-width, initial-scale=1, user-scalable=no' },
		],
		script: [{
			src: '/js/prism.js'
		}],
		link: [
			{ rel: 'shortcut icon', type: 'image/png', href: '/favicon.png' },
			{ rel: 'stylesheet', href: '/css/prism.css' }
		]
	},

	// Global CSS: https://go.nuxtjs.dev/config-css
	css: [

	],

	// Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
	plugins: [
		'~/plugins/axios.js',
		'~/plugins/filters.js',
		{ src: '~/plugins/c-drag-verify.js', ssr: false },
		{ src: '~/plugins/ba.js', ssr: false }
	],

	// Auto import components: https://go.nuxtjs.dev/config-components
	components: true,

	// Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
	buildModules: [],

	// Modules: https://go.nuxtjs.dev/config-modules
	modules: [
		'@nuxtjs/style-resources', //配置全局less插件
		'@nuxtjs/axios',
		'@nuxtjs/proxy',
	],
	css: ['~assets/style/dashicons.min.css'],
	styleResources: {
		less: [ //全局less资源
			'~assets/style/color.less',
			'~assets/style/reset.less',
			'~assets/style/public.less'
		]
	},

	// Axios module configuration: https://go.nuxtjs.dev/config-axios
	axios: {
		proxy: true,
	},
	proxy: {
		'/api': {
			target: config.api_url,
			pathRewrite: {
				'^/api': ''
			},
			changeOrigin: true,
			secure: false
		}
	},

	// Build Configuration: https://go.nuxtjs.dev/config-build
	build: {
		extractCSS: true
	}
}
