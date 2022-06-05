<template>
	<div class="v-menu">
		<div class="js2-box container">
			<h3>菜单结构</h3>
			<div class="menu-wrap">
				<div class="menu-action">
					<div class="item menu-action-btn">
						<el-select class="menu-type-select" v-model="current_menu_index" placeholder="请选择菜单">
							<template v-if="menu_list.length">
								<el-option v-for="(menu, index) in menu_list" :key="menu.id" :label="menu.name" :value="index"></el-option>
							</template>
							<!-- 没有菜单时占位提示 -->
							<template v-else>
								<el-option label="请先创建菜单" :value="0"></el-option>
							</template>
						</el-select>
						<c-permission permission="menu-add">
							<el-button class="add-menu-btn" @click="createMenu">创建新菜单</el-button>
						</c-permission>
						<c-permission permission="menu-delete">
							<el-button @click="deleteMenu">删除当前菜单</el-button>
						</c-permission>
					</div>
					<div class="item">
						<el-tabs v-model="type">
							<el-tab-pane label="分类目录" name="category">
								<el-input placeholder="输入关键字进行过滤" v-model="cate_filter_text">
								</el-input>
								<el-tree v-if="categories.length" ref="category-tree" class="category-select-tree" :filter-node-method="categoriesFilter" :data="categories" default-expand-all show-checkbox check-on-click-node check-strictly :expand-on-click-node="false" :props="{label:'name'}"></el-tree>
							</el-tab-pane>
							<el-tab-pane label="文章/页面" name="post">
								<el-input v-model="post_keywords" @input="postSearch" placeholder="输入关键词搜索文章"></el-input>
								<div class="post-list">
									<ul v-if="post_list.length">
										<li v-for="post in post_list" :key="post.id">
											<el-checkbox v-model="post.checked">{{post.title}}</el-checkbox>
										</li>
									</ul>
								</div>
							</el-tab-pane>
							<el-tab-pane label="自定义链接" name="custom">
								<label>链接地址：<el-input v-model="custom_url" placeholder="https://google.com"></el-input></label>
								<label>链接文本：<el-input v-model="custom_text"></el-input></label>
							</el-tab-pane>
						</el-tabs>
						<c-permission permission="menu-update">
							<el-button class="add-to-menu-btn" @click="addMenuItem">添加到菜单</el-button>
						</c-permission>
					</div>
				</div>
				<div class="menu-preview">
					<h3 class="menu-preview-title">菜单顺序<span class="tips">拖放菜单项可调整顺序</span></h3>
					<div class="menu-tree">
						<el-tree v-if="menu_list.length" draggable :data="current_menu_list" default-expand-all :expand-on-click-node="false">
							<div class="custom-tree-node" slot-scope="{node,data}">
								<div class="menu-item">
									<span class="menu-item-label">
										{{node.label}}
										<el-input class="menu-item-input" :value="node.label" @input="menuItemInput(node,$event)"></el-input>
									</span>
									<el-button size="mini" type="text" @click="removeMenuItem(node,data)">删除菜单</el-button>
								</div>
							</div>
						</el-tree>
					</div>
					<div class="menu-bottom" v-if="menu_list.length">
						<c-permission permission="menu-update">
							<el-button size="middle" @click="updateMenu" type="primary">保存菜单</el-button>
						</c-permission>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import {
	apiMenuList,
	apiMenuAdd,
	apiMenuUpdate,
	apiMenuDelete,
	apiCategoryList,
	apiPostSearch
} from "@/request/api.js"
import {
	recursion,
} from "@/common/tools.js"
import { nanoid } from 'nanoid'

/* 初始菜单项结构
{
	label: '',
	link: '',
	type: '',
	children: [],
}
*/

export default {
	name: "v-menu",
	data() {
		return {
			//当前正在编辑的菜单序号
			current_menu_index: 0,
			categories: [],
			//菜单列表
			menu_list: [],
			type: "category", //准备添加的菜单项类型（category|post|custom）
			cate_filter_text: '', //分类查找
			post_keywords: '', //内容搜索
			post_list: [], //搜索的内容列表
			custom_url: '', //自定义链接地址
			custom_text: '', //自定义链接文本
		}
	},
	watch: {
		cate_filter_text(val) {
			this.$refs['category-tree'].filter(val);
		}
	},
	methods: {
		//添加菜单项
		addMenuItem() {
			if (!this.menu_list.length) {
				this.$message.error("请先创建菜单")
				return
			}
			const current_menu = this.menu_list[this.current_menu_index].list
			const checked_nodes = this.$refs['category-tree'].getCheckedNodes()
			const checked_posts = this.post_list.filter(item => item.checked)
			switch (this.type) {
				case 'category':
					if (!checked_nodes.length) {
						this.$message.warning('请选择分类')
						return
					}
					checked_nodes.forEach(item => {
						current_menu.push({
							id: nanoid(),
							label: item.name,
							link: item.id,
							type: 'category',
							children: []
						})
					})
					break
				case 'post':
					if (!checked_posts.length) {
						this.$message.warning('请选择内容')
						return
					}
					checked_posts.forEach(item => {
						current_menu.push({
							id: nanoid(),
							label: item.title.slice(0, 6),
							link: item.id,
							type: 'post',
							children: []
						})
					})
					checked_posts.forEach(item => item.checked = false)
					break
				case 'custom':
					if (!this.custom_url && !this.custom_text) {
						this.$message.warning('请输入链接地址和文本')
						return
					}
					current_menu.push({
						id: nanoid(),
						label: this.custom_text,
						link: this.custom_url,
						type: 'custom',
						children: [],
					})
					this.custom_text = this.custom_url = ''
					break
			}
			this.$message.success('添加成功')

		},
		postSearch() {
			apiPostSearch(this.post_keywords).then(res => {
				this.post_list = res.data
			})
		},

		//分类查找
		categoriesFilter(value, data) {
			if (!value) return true;
			return data.label.indexOf(value) !== -1;
		},
		//修改菜单项
		menuItemInput(node, value) {
			recursion(this.current_menu_list, "children", (item) => {
				if (item.id == node.data.id) {
					item.label = value
				}
			})
		},
		//删除菜单项
		removeMenuItem(node, data) {
			this.$messageBox
				.confirm('删除菜单后子菜单也会删除', '提示', { type: 'warning' })
				.then(() => {
					const parent = node.parent;
					const children = parent.data.children || parent.data;
					const index = children.findIndex(d => d.id === data.id);
					children.splice(index, 1);
					this.$message.success('删除成功')

				})
				.catch(() => {});
		},
		//保存菜单
		updateMenu() {
			apiMenuUpdate(this.menu_list[this.current_menu_index]).then(() => {
				this.$message.success('保存成功')
			})
		},

		//创建新菜单
		createMenu() {
			this.$messageBox
				.prompt('请输入菜单名称', '创建新菜单')
				.then(({ value }) => {
					apiMenuAdd({
						name: value,
					}).then((res) => {
						this.menu_list.push(res.data)
						this.$message.success('菜单添加成功')
						this.current_menu_index = this.menu_list.length - 1
					})
				})
				.catch(() => {})
		},

		//删除当前菜单
		deleteMenu() {
			if (!this.menu_list.length) {
				this.$message.error('请先创建菜单')
				return
			}
			this.$messageBox
				.confirm('是否要删除菜单', '提示', {
					type: 'warning',
				})
				.then(() => {
					const current_menu = this.menu_list[
						this.current_menu_index
					]
					apiMenuDelete(current_menu.id).then(() => {
						this.menu_list.splice(this.current_menu_index, 1)
						this.current_menu_index = 0
						this.$message.success('删除成功')
					})
				})
				.catch(() => {})
		},
	},
	computed: {
		//当前菜单列表
		current_menu_list() {
			const { menu_list, current_menu_index } = this
			recursion(menu_list[current_menu_index].list, 'children', item => {
				item.id = nanoid()
			})
			return menu_list[current_menu_index].list
		},
		category_all() {
			if (!this.categories || !this.categories.length) {
				return []
			}
			const category_all = []
			recursion(this.categories, "children", item => {
				category_all.push(item)
			})
			return category_all
		}
	},
	created() {
		apiCategoryList().then(res => {
			this.categories = res.data
		})
		apiMenuList().then((res) => {
			this.menu_list = res.data
		})
	},
}
</script>
<style lang="less">
.v-menu {
	.container {
		.tips {
			font-size: 0.75em;
			font-weight: normal;
		}

		.menu-wrap {
			display: flex;
			justify-content: space-between;
		}

		.menu-bottom {
			text-align: center;
			margin-top: 30px;
		}

		.menu-action {
			margin-top: 1.5em;
			width: 35%;

			.item {
				margin-bottom: 1em;

				&.menu-action-btn {
					display: flex;
					align-items: center;
					justify-content: space-between;
				}

				.add-menu-btn {
					margin: 0 .5em;
				}

				.add-to-menu-btn {
					display: block;
					margin: 2em auto 0;
				}

				button {
					min-width: 8em;
					margin: .5em 0;
				}

				.category-select-tree {
					height: 40vh;
					overflow-y: auto;
				}
			}

			.el-tab-pane {
				border: 1px solid @border;
				padding: 1em;

				.post-list {
					margin-top: .5em;

					li {
						padding: .25em;

						.el-checkbox__label:hover {
							color: @link;
						}
					}
				}

				&#pane-custom {
					label {
						display: flex;
						white-space: nowrap;
						align-items: center;
						margin-bottom: .5em;
					}
				}

			}
		}

		.menu-preview {
			width: 60%;
			margin-top: 1.5em;
			padding: 1em;
			border: 1px solid @border;

			.menu-preview-title {
				.tips {
					margin-left: 1em;
				}
			}

			.menu-tree {
				margin-top: 1em;
				border-top: 1px solid @border;
				padding-top: 1em;
				height: 55vh;
				overflow-y: auto;
			}

			//自定义树节点
			.custom-tree-node {
				width: 300px;
				padding: 0 1em;
				line-height: 36px;
				border: 1px solid @border;
				margin: .25em 0;
				background-color: @bg;

				&:hover {
					border-color: @link;
				}

				.menu-item {
					display: flex;
					justify-content: space-between;
					align-items: center;

					.menu-item-label {
						position: relative;

						&:hover {
							.menu-item-input {
								display: flex;
							}
						}
					}
				}

				.menu-item-input {
					display: none;
					position: absolute;
					align-items: center;
					top: 0;
					left: -.65em;
					z-index: 10;
					min-width: 6em;
					width: 200%;
					height: 36px;
				}

			}


			.el-tree {
				background-color: unset;

				.el-tree-node__content {
					height: auto;
					background: none;
					display: flex;

				}

				.el-tree-node__children {
					.el-tree-node__content {
						padding-left: 2em !important;
					}

					.el-tree-node__children {
						.el-tree-node__content {
							padding-left: 4em !important;
						}
					}
				}

				.el-tree-node__expand-icon {
					padding: 13px;
				}

			}

		}
	}



}
</style>