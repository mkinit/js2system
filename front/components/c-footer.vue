<template>
	<footer class="c-footer">
		<div class="js2-center-wrap w1024">
			<div class="links" v-if="links.length">
				<h3>友情链接</h3>
				<ul>
					<li v-for="link in links">
						<a :href="link.link" target="_blank">{{link.name}}</a>
					</li>
				</ul>
			</div>
			<div class="copyright">
				<span>©</span>
				<span class="date">{{date}}</span>
				<span class="site-name">{{setting_info.site_name}}</span>
				<span>版权所有</span>
				<a href="http://beian.miit.gov.cn/" target="_blank">{{setting_info.icp}}</a>
			</div>
		</div>
	</footer>
</template>
<script>
import { mapState } from 'vuex'
export default {
	name: 'c-footer',
	data() {
		return {
			date: new Date().getFullYear()
		}
	},
	computed: {
		...mapState(['links', 'setting_info'])
	}
}

</script>
<style lang="less">
.c-footer {
	margin-top:2em;
	.links {
		ul {
			display: flex;
			flex-wrap: wrap;
		}

		li {
			margin-right: 1em;
		}
	}

	.copyright {
		text-align: center;
		padding: 2em 0;
	}
}

</style>
