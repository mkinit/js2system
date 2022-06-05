<template>
	<header class="c-top">
		<div class="js2-center-wrap w828">
			<main>
				<div class="logo">
					<NuxtLink class="link" :to="{name:'index'}">
						<img :src="config.base_url+setting_info.logo" :alt="setting_info.site_name" />
					</NuxtLink>
					<div class="blog-title">
						<div class="blog-name"><a href="/">{{setting_info.site_name}}</a></div>
						<div class="blog-description">{{setting_info.site_description}}</div>
					</div>
				</div>
				<div v-if="user">欢迎，<NuxtLink class="link" :to="{name:'user'}">{{user.nickname||user.username}}</NuxtLink>，<span class="js2-link" @click="logout">退出</span></div>
				<div class="register-login" v-else>
					<NuxtLink class="link" :to="{name:'register'}">注册</NuxtLink>
					<NuxtLink class="link" :to="{name:'login'}">登录</NuxtLink>
				</div>
			</main>
			<footer>
				<div class="breadcrumbs">
					当前位置：
					<template v-for="(breadcrumb,index) in breadcrumbs">
						<template v-if="index<breadcrumbs.length-1">
							<NuxtLink class="link" :to="{name:breadcrumb.name,params:{id:breadcrumb.id}}">{{breadcrumb.title}}</NuxtLink>
							/
						</template>
						<template v-else>{{breadcrumb.title}}</template>
					</template>
				</div>
			</footer>
		</div>
	</header>
</template>
<script>
import { mapState } from 'vuex'
import { recursion } from '@/common/tools.js'
import config from '@/config.js'
const Cookie = process.client ? require('js-cookie') : undefined
export default {
	name: 'c-top',
	data() {
		return {
			config,
			breadcrumbs: []
		}
	},
	methods: {
		logout() {
			const result = confirm('是否要退出登录？')
			if (result) {
				Cookie.remove('user')
				this.$store.commit('setData', {
					key: 'user',
					data: null
				})
				this.$router.replace('/')
			}
		},
		//面包屑导航生成器
		crumbGenetator(route) {
			const { name, params } = route

			//面包屑导航路由，用于动态渲染
			const breadcrumbs = [{
				name: 'index',
				title: '首页'
			}]

			switch (name) {
				case 'login':
					breadcrumbs.push({
						title: '用户登录',
					})
					this.breadcrumbs = breadcrumbs
					break
				case 'user':
					breadcrumbs.push({
						title: '用户中心',
					})
					this.breadcrumbs = breadcrumbs
					break
				case 'register':
					breadcrumbs.push({
						title: '注册账号',
					})
					this.breadcrumbs = breadcrumbs
					break
				case 'category-id':
					//分类路由
					recursion(this.categories, 'children', item => {
						if (item.id == params.id) {
							breadcrumbs.push({
								name: 'category-id',
								title: item.name,
								id: item.id
							})
							this.breadcrumbs = breadcrumbs
						}
					})
					break
				case 'post-id':
					//文章路由
					const post_route = {
						name: 'post-id',
						title: '内容详情',
						id: params.id
					}
					if (this.post.type == 'post') {
						//分类路由
						let category_route
						recursion(this.categories, 'children', item => {
							if (item.id == this.post.category.id) {
								category_route = {
									name: 'category-id',
									title: item.name,
									id: item.id
								}
							}
						})
						breadcrumbs.push(category_route, post_route)
					}
					this.breadcrumbs = breadcrumbs
					break
				default:
					this.breadcrumbs = breadcrumbs
			}

		}
	},
	computed: {
		...mapState(['setting_info', 'categories', 'post', 'user'])
	},
	watch: {
		'$route'(route) {
			this.crumbGenetator(route)
		}
	},
	created() {
		this.crumbGenetator(this.$route)
	}
}

</script>
<style lang="less">
.c-top {
	padding: 1em 0;

	main {
		border-bottom: 1px solid @line;
		padding-bottom: 1.5em;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.logo {
		display: flex;
		align-items: center;

		img {
			max-height: 70px;
			width: auto;
			border-radius: 50%;
		}

		.blog-title {
			margin-left: 1em;
			line-height: 1;
		}

		.blog-name {
			font-size: 2rem;
			font-weight: bold;

			a {
				color: @font;

				&:hover {
					color: @danger;
				}
			}
		}

		.blog-description {
			margin-top: .5em;
			font-size: 1rem;
		}
	}

	.register-login {
		.link {
			margin-left: 1em;
		}
	}

	.breadcrumbs {
		line-height: 2;
		font-size: 13px;
	}
}

</style>
