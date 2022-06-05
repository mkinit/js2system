<template>
	<div class="v-link js2-box">
		<el-table :data="links" height="72vh">
			<el-table-column align="center" label="链接名称">
				<template slot-scope="scope">
					<el-input v-model="scope.row.name"></el-input>
				</template>
			</el-table-column>
			<el-table-column align="center" label="链接地址">
				<template slot-scope="scope">
					<el-input v-model="scope.row.link"></el-input>
				</template>
			</el-table-column>
			<el-table-column align="center" label="操作">
				<template slot="header">
					<c-permission permission="link-add">
						<el-button type="primary" @click="dialog_link=true">添加链接</el-button>
					</c-permission>
				</template>
				<template slot-scope="scope">
					<c-permission permission="link-update">
						<el-button type="primary" @click="updateLink(scope.row)">更新</el-button>
					</c-permission>
					<c-permission permission="link-delete">
						<el-button type="danger" @click="deleteLink(scope.row.id)">删除</el-button>
					</c-permission>
				</template>
			</el-table-column>
		</el-table>
		<div class="pagination" v-if="pagination.page_total>1">
			<el-pagination layout="prev, pager, next" :current-page="pagination.current_page" :total="pagination.total" :page-count="pagination.page_total" @current-change="pageChange" hide-on-single-page>
			</el-pagination>
		</div>
		<el-dialog class="link-add" title="添加链接" :visible.sync="dialog_link" width="35%" center>
			<div class="item">
				<el-input placeholder="链接名称" v-model="name"></el-input>
			</div>
			<div class="item">
				<el-input placeholder="链接地址" v-model="link"></el-input>
			</div>
			<div slot="footer">
				<el-button type="primary" @click="addLink">添加链接</el-button>
			</div>
		</el-dialog>
	</div>
</template>
<script>
import { apiLinkList, apiLinkUpdate, apiLinkDelete, apiLinkAdd } from '@/request/api.js'
export default {
	name: 'v-link',
	data() {
		return {
			dialog_link: false,
			name: '',
			link: '',
			links: [], //链接列表
			pagination: {},
		}
	},
	methods: {
		addLink() {
			const { name, link } = this
			apiLinkAdd({
				name,
				link
			}).then(res => {
				this.links.unshift(res.data)
				this.name = this.link = ''
				this.$message.success('添加成功')
				this.dialog_link = false
			})
		},
		updateLink(link) {
			apiLinkUpdate(link).then(() => {
				this.$message.success('更新成功')
			})
		},
		deleteLink(id) {
			this.$messageBox
				.confirm('是否要删除该链接', '提示', { type: 'warning' })
				.then(() => {
					apiLinkDelete(id).then(() => {
						this.links.splice(this.links.findIndex(link => link.id === id), 1)
						this.$message.success('删除成功')
					})
				})
				.catch(() => {})
		},
		getLinks(page = 1) {
			apiLinkList(page).then(res => {
				this.pagination = res.pagination
				this.links = res.data
			})
		},
		//分页
		pageChange(page) {
			this.getLinks(page)
			this.resetTableScrollTop()
		},
	},
	created() {
		this.getLinks()
	},
}
</script>
<style lang="less">
.v-link {
	.link-add {
		.item {
			margin: 1em 0;
		}
	}
}
</style>