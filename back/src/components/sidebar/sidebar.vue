<template>
	<div class="v-sidebar">
		<el-menu background-color="#606266" text-color="#fff" active-text-color="#ffd04b" :router="true" :default-active="current_link" :collapse="sidebar_collapse" :unique-opened="true">
			<template v-for="(menu, menu_index) in menu_list">
				<el-submenu v-if="menu.children&&menu.children.length" :index="String(menu_index)" :key="menu_index">
					<template slot="title">
						<i :class="'el-icon-' + menu.icon"></i>
						<span slot="title">{{ menu.title }}</span>
					</template>
					<el-menu-item v-for="(child, child_index) in menu.children" :index="child.link" :key="child_index">{{ child.title }}</el-menu-item>
				</el-submenu>
				<el-menu-item v-else :index="menu.link" :key="menu_index">
					<i :class="'el-icon-' + menu.icon"></i>
					<span slot="title">{{ menu.title }}</span>
				</el-menu-item>
			</template>
		</el-menu>
	</div>
</template>
<script>
import { mapState } from 'vuex'
import { recursion } from '@/common/tools.js'
export default {
	name: "v-sidebar",
	data() {
		return {
			current_link: '/', //当前的地址
			//menu_list: [],
			menu_list_origin: [
				//菜单列表
				{
					title:'系统首页',
					icon: "s-home",
					link: "/",
				},
				{
					title: "网站设置",
					icon: "setting",
					children: [{
						title: "系统设置",
						link: "/setting",
					}, {
						title: "公司信息",
						link: "/company",
					}, {
						title: "轮播图",
						link: "/banner",
					}, {
						title: "菜单设置",
						link: "/menu",
					}, ]
				}, {
					title: "分类管理",
					icon: "folder",
					link: "/category",
				}, {
					title: "标签管理",
					icon: "folder-opened",
					link: "/tag",
				}, {
					title: "文章管理",
					icon: "document-copy",
					link: "/post?type=post",
				}, {
					title: "页面管理",
					icon: "document",
					link: "/post?type=single",
				}, {
					title: "文件管理",
					link: "/file",
					icon: "files",
				}, {
					title: "链接管理",
					icon: "link",
					link: "/link",
				}, {
					title: "留言管理",
					link: "/guestbook",
					icon: "reading",
				}, {
					title: "评论管理",
					link: "/comment",
					icon: "s-comment",
				}, {
					title: "用户管理",
					icon: "user",
					link: "/user",
					action: 'user-index'
				}, {
					title: "角色管理",
					link: "/role",
					icon: "s-custom",
					action: 'role-index'
				}
			],
		};
	},
	computed: {
		...mapState(['sidebar_collapse', 'user']),
		menu_list() {
			const menu_list = JSON.parse(JSON.stringify(this.menu_list_origin))

			//拼接权限列表的controller和action
			let action_list = this.user.role.action_list.map(item => {
				item.controller_action = item.controller.toLowerCase() + '-' + item.action.toLowerCase()
				return item
			})

			//递归处理没有权限的菜单
			recursion(menu_list, 'children', menu => {
				menu.children = menu.children ? menu.children.filter(item => {
					//action属性表示需要权限
					if (item.action) {
						return action_list.find(action => action.controller_action == item.action)
					} else {
						return true
					}
				}) : []
			})

			//过滤没有子菜单的数据
			return menu_list.filter(item => {
				//如果没有link并且有children，是没有链接的一级菜单，过滤没有子菜单的一级菜单
				if (!item.link && item.children) {
					return item.children.length ? true : false
				} else {
					//否则，是有链接的一级菜单，需要过滤权限
					//如果有action
					if (item.action) {
						return action_list.find(action => action.controller_action == item.action)
					}
					return true
				}
			})
		}
	},
	watch: {
		$route(route) {
			this.current_link = route.fullPath
		}
	}
};
</script>
<style lang="less">
.v-sidebar {
	white-space: nowrap;
	text-align: center;
	padding: 20px 0;

	.router-link-exact-active {
		color: @link;
	}

	.el-menu {
		border-right: none;
		background-color: @font-second;

		&:not(.el-menu--collapse) {
			width: 160px;
			margin-right:20px;
		}

		.el-menu {
			background-color: #4d4e52 !important;

			.el-menu-item {
				background-color: #4d4e52 !important;
			}
		}

		.el-submenu {
			.el-menu {
				padding-left: 30px;
			}
		}

		.el-menu-item {
			min-width: unset;

			&.is-active {
				background-color: #4d4e52 !important;
			}
		}

	}
}
</style>