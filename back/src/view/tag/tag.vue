<template>
	<div class="v-tag js2-box">
		<el-table :data="tags" height="72vh">
			<el-table-column align="center" label="标签名">
				<template slot-scope="scope">
					<el-input class="cate-name" v-model="scope.row.name" placeholder="标签名称"></el-input>
				</template>
			</el-table-column>
			<el-table-column align="center" label="关键词">
				<template slot-scope="scope">
					<el-input class="cate-name" v-model="scope.row.keywords" placeholder="标签名称"></el-input>
				</template>
			</el-table-column>
			<el-table-column align="center" label="描述">
				<template slot-scope="scope">
					<el-input class="cate-name" v-model="scope.row.description" placeholder="标签名称"></el-input>
				</template>
			</el-table-column>
			<el-table-column align="center" label="操作">
				<template slot="header">
					<c-permission permission="tag-add">
						<el-button type="primary" @click="dialog_tag=true">添加标签</el-button>
					</c-permission>
				</template>
				<template slot-scope="scope">
					<c-permission permission="tag-update">
						<el-button type="primary" @click="tagUpdate(scope.row)">更新</el-button>
					</c-permission>
					<c-permission permission="tag-delete">
						<el-button type="danger" @click="tagDelete(scope.row.id)">删除</el-button>
					</c-permission>
				</template>
			</el-table-column>
		</el-table>
		<div class="pagination" v-if="pagination.page_total>1">
			<el-pagination layout="prev, pager, next" :current-page="pagination.current_page" :total="pagination.total" :page-count="pagination.page_total" @current-change="pageChange" hide-on-single-page>
			</el-pagination>
		</div>
		<el-dialog class="tag-add" title="添加标签" :visible.sync="dialog_tag" width="35%" center>
			<div class="item">
				<el-input placeholder="标签名称" v-model="name"></el-input>
			</div>
			<div class="item">
				<el-input placeholder="标签关键词（英文逗号分隔）" v-model="keywords"></el-input>
			</div>
			<div class="item">
				<el-input placeholder="标签描述" v-model="description"></el-input>
			</div>
			<div slot="footer">
				<el-button type="primary" @click="tagAdd">添加标签</el-button>
			</div>
		</el-dialog>
	</div>
</template>
<script>
import {
	apiTagList,
	apiTagUpdate,
	apiTagDelete,
	apiTagAdd
} from "@/request/api.js";
export default {
	name: 'v-tag',
	data() {
		return {
			dialog_tag: false,
			name: '', //当前要添加的标签名称
			keywords: '', //当前要添加的标签关键词
			description: '', //当前要添加的标签描述
			tags: [],
			pagination: {},
		}
	},
	computed: {},
	components: {},
	methods: {
		//分页
		pageChange(page) {
			this.getTagList(page)
			this.resetTableScrollTop()
		},

		getTagList(page = 1) {
			apiTagList(page).then(res => {
				this.tags = res.data
				this.pagination = res.pagination
			})
		},

		tagAdd() {
			const { name, keywords, description } = this
			const data = {
				name,
				keywords,
				description
			}
			apiTagAdd(data).then(res => {
				this.$message.success('添加成功')
				this.dialog_tag = false
				this.tags.unshift(res.data)
			})
		},
		//删除标签
		tagDelete(id) {
			this.$messageBox
				.confirm('是否要删除该标签', '提示', { type: 'warning' })
				.then(() => {
					apiTagDelete(id).then(() => {
						this.$message.success('更新成功')
						this.tags = this.tags.filter(tag => tag.id != id)
					})
				})
				.catch(() => {});

		},
		//更新标签
		tagUpdate(tag) {
			apiTagUpdate(tag).then(() => {
				this.$message.success('更新成功')
			})
		},
	},
	created() {
		this.getTagList()
	}
}
</script>
<style lang="less">
.v-tag {
	.tag-add{
		.item{
			margin:1em 0;
		}
	}
}
</style>