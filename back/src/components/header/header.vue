<template>
	<div class="v-header">
		<header class="header js2-center-wrap w1920">
			<div class="left">
				<a :href="home_url">
					<span class="name">JS2-SYSTEM</span>
				</a>
				<span class="sidebar-toggle-btn" @click="sidebarToggle">
					<i :class="'el-icon-s-' + (sidebar_collapse ? 'unfold' : 'fold')"></i>
				</span>
			</div>
			<div class="right">
				<span v-if="user">当前用户：<router-link to="/my">{{user.nickname}}</router-link></span>
				<a href="javascript:void(0)" @click="clearCache">清除缓存</a>
				<a :href="setting_info&&setting_info.site?('https://'+setting_info.site):'/'" target="_blank">浏览网站</a>
				<a href="javascript:void(0)" class="logout-btn" @click="logoutConfirm">
					退出登陆
				</a>
			</div>
		</header>
	</div>
</template>
<script>
import logout from "@/common/logout.js"
import {apiClearCache,apiSetting} from "@/request/api.js"
import { mapState } from 'vuex'
export default {
	name: "v-header",
	data(){
		return {
			home_url:'/',
			setting_info:null,
		}
	},
	methods: {
		clearCache(){
			apiClearCache().then(()=>{
				this.$message.success('缓存已清理')
			})
		},
		logoutConfirm() {
			this.$messageBox
				.confirm('是否要退出登录', '提示', { type: 'warning' })
				.then(() => {
					logout()
				})
				.catch(() => {})
		},
		sidebarToggle() {
			this.$store.commit('update', {
				key: 'sidebar_collapse',
				data: !this.sidebar_collapse
			})
		}
	},
	computed: {
		...mapState(['sidebar_collapse', 'user'])
	},
	created(){
		this.home_url = location.origin + location.pathname

		apiSetting().then(res=>{
			this.setting_info = res.data
		})
	}
};
</script>
<style lang="less">
.v-header {
	font-size: 13px;
	position: fixed;
	left: 0;
	top: 0;
	width: 100%;
	z-index: 10;
	background-color: @font;
	color: #FFF;
	display: flex;
	align-items: center;
	height: @headerHeight;

	.header {
		width: 100%;
	}

	.right{
		span,a{
			margin:1.5em;
		}
	}

	a {
		color: #FFF;
	}

	.sidebar-toggle-btn {
		margin: 0 1em;
	}

	[class*=" el-icon-"],
	[class^=el-icon-] {
		font-size: 2em;
		cursor: pointer;
	}

	.left {
		display: flex;
		align-items: center;
	}

	.header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 0 2em;
	}

	.logo {
		width: 30px;
		border-radius: 50%;
	}

	.name {
		margin-left: 0.5em;
		font-size: 1.5em;
		line-height: 30px;
	}

}
</style>