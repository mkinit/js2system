export default ({ app, store }) => {
	var _hmt = _hmt || [];
	(function() {
		var hm = document.createElement("script");
		hm.src = "https://hm.baidu.com/hm.js?"+store.state.setting_info.ba;
		var s = document.getElementsByTagName("script")[0];
		s.parentNode.insertBefore(hm, s);
	})();
	app.router.afterEach((to, from) => {
		try {
			window._hmt = window._hmt || []
			window._hmt.push(['_trackPageview', to.fullPath])
		} catch (e) {}
	})
}
