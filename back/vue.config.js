const api_url = 'https://js2api.com/js2-json/v1'//本地环境代理接口地址
module.exports = {
	outputDir:'../js2api/public/admin',//项目打包路径
	productionSourceMap: false,
	publicPath: process.env.NODE_ENV == 'development' ? '/' : './', // 资源打包路径
	devServer: {
		port: 208,
		proxy: api_url,
	},
	css: {
		loaderOptions: {
			less: {
				additionalData: `@import '@/assets/less/color.less';`,
			},
		},
	},
};
