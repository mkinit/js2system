<template>
	<div class="v-category js2-box">
		<el-table :data="categories" height="81vh" row-key="id" default-expand-all :tree-props="{children: 'children'}">
			<el-table-column label="分类名称">
				<template slot-scope="scope">
					<el-input class="cate-name" v-model="scope.row.name" placeholder="分类名称"></el-input>
				</template>
			</el-table-column>
			<el-table-column label="分类关键词">
				<template slot-scope="scope">
					<el-input class="cate-name" v-model="scope.row.keywords" placeholder="关键词"></el-input>
				</template>
			</el-table-column>
			<el-table-column label="分类描述">
				<template slot-scope="scope">
					<el-input class="cate-name" v-model="scope.row.description" placeholder="描述"></el-input>
				</template>
			</el-table-column>
			<el-table-column label="分类封面">
				<template slot-scope="scope">
					<img class="cover" v-if="scope.row.cover" :src="base_url+scope.row.cover">
					<el-button v-else @click="openMedia(scope.row.id)">选择封面</el-button>
				</template>
			</el-table-column>
			<el-table-column label="操作">
				<template slot="header">
					<c-permission permission="category-add">
						<el-button type="primary" @click="dialog_category=true">添加分类</el-button>
					</c-permission>
				</template>
				<template slot-scope="scope">
					<c-permission permission="category-update">
						<el-button type="primary" @click="updateItem(scope.row)">更新</el-button>
					</c-permission>
					<c-permission permission="category-delete">
						<el-button type="danger" @click="deleteItem(scope.row)">删除</el-button>
					</c-permission>
				</template>
			</el-table-column>
		</el-table>
		<el-dialog class="category-add" title="添加分类" :visible.sync="dialog_category" width="50%" center>
			<div class="item">
				<el-cascader :options="categories" :props="{expandTrigger:'hover', checkStrictly: false, value: 'id', label: 'name' }" v-model="option" placeholder="选择父级（不选为一级）" title="选择父级（不选为一级）" clearable></el-cascader>
				<el-input placeholder="分类名称" v-model="name"></el-input>
			</div>
			<div class="item">
				<el-input placeholder="分类关键词（英文逗号分隔）" v-model="keywords"></el-input>
			</div>
			<div class="item">
				<el-input placeholder="分类描述" v-model="description"></el-input>
			</div>
			<div slot="footer">
				<el-button type="primary" @click="addCategory">添加分类</el-button>
			</div>
		</el-dialog>
		<c-media ref="media" @result="mediaResult" />
	</div>
</template>
<script>
import {
	apiCategoryList, //获取分类列表
	apiCategoryAdd, //添加分类
	apiCategoryUpdate, //更新分类
	apiCategoryDelete, //删除分类
} from "@/request/api.js";
import { recursion } from '@/common/tools.js'
//import treeCategoryList from '@/components/tree-category-list/tree-category-list.vue'
import media from '@/components/media/media.vue'
export default {
	name: 'v-category',
	data() {
		return {
			dialog_category: false,
			name: '', //当前要添加的分类名称
			keywords: '', //当前要添加的分类关键词
			description: '', //当前要添加的分类描述
			option: [], //已选择的上级分类ID
			categories: [],
			set_cate_cover_id: 0, //当前正在设置封面图的分类ID
		}
	},
	computed: {},
	components: {
		//treeCategoryList,
		'c-media': media
	},
	methods: {
		openMedia(cate_id) {
			this.set_cate_cover_id = cate_id
			this.$refs.media.show()
		},
		mediaResult(data) {
			(data[0].type != 'image') ?
			this.$message.error('请选择图片类型文件'):
				recursion(this.categories, 'children', current_item => {
					if (current_item.id === this.set_cate_cover_id) {
						current_item.cover = data[0].name
					}
				})
		},

		//更新分类
		updateItem(cate) {
			recursion(this.categories, 'children', current_item => {
				if (current_item.id === cate.id) {
					apiCategoryUpdate(current_item).then(() => {
						this.$message.success('更新成功')
					})
				}
			})

		},

		//删除分类
		deleteItem(cate) {
			if (cate.children && cate.children.length) {
				this.$message.error('请先删除下级分类')
				return
			}
			this.$messageBox
				.confirm('是否要删除该分类', '提示', { type: 'warning' })
				.then(() => {
					apiCategoryDelete(cate.id).then(() => {
						recursion(this.categories, 'children', (current, parent, index) => {
							if (current.id === cate.id) {
								parent.splice(index, 1)
							}
						})
						this.$message.success('删除成功')
					})
				})
				.catch(() => {});

		},

		//添加分类
		addCategory() {
			const { name, keywords, description, option } = this
			const category = {
				name,
				keywords,
				description,
				parent_id: option[option.length - 1]
			}
			apiCategoryAdd(category).then(res => {
				//更新本地数据
				if (!option[option.length - 1]) {
					this.categories.push(res.data)
				} else {
					recursion(this.categories, 'children', current_item => {
						if (current_item.id === option[option.length - 1]) {
							if (!current_item.children) {
								current_item.children = []
							}
							current_item.children.push(res.data)
						}
					})
				}
				this.name = this.keywords = this.description = ''
				this.option = []
				this.$message.success('添加成功')
				this.dialog_category = false
				this.categories = JSON.parse(JSON.stringify(this.categories))
			})
		},
	},
	created() {
		apiCategoryList().then(res => {
			this.categories = res.data
		})
	}
}
</script>
<style lang="less">
.v-category {
	.el-table {

		.el-table__header-wrapper,
		.el-table__body-wrapper {
			padding-bottom: 1em;
		}

		td {
			padding: 1em 0;
			border-bottom: none;
		}

		.cell {
			display: flex;
			align-items: center;
			justify-content: center;

			.el-table__expand-icon {
				display: none;
			}
		}

		.el-table__row--level-1 {
			td {
				&:first-child {
					.cell {
						.el-table__indent {
							margin-left: -16px;

							&::before {
								content: '—— '
							}
						}
					}
				}
			}
		}

		.el-table__row--level-2 {
			td {
				&:first-child {
					.cell {
						.el-table__indent {
							margin-left: -32px;

							&::before {
								content: '———— '
							}
						}
					}
				}
			}
		}

		.el-table__row--level-3 {
			td {
				&:first-child {
					.cell {
						.el-table__indent {
							margin-left: -48px;

							&::before {
								content: '—————— '
							}
						}
					}
				}
			}
		}
	}


	.cover {
		max-height: 50px;
		margin-right: .5em;
	}

	.category-add {
		.item {
			display: flex;
			align-items: center;
			margin: .5em 0;

			.el-cascader {
				line-height: 28px;
				margin-right: 1em;
			}
		}
	}

}
</style>