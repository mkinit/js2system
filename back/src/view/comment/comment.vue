<template>
	<div class="v-comments js2-box">
		<el-table :data="comments" height="72vh">
			<el-table-column align="center" label="评论者" prop="nickname"></el-table-column>
			<el-table-column align="center" label="评论内容" prop="content"></el-table-column>
			<el-table-column align="center" label="被评论内容" prop="post.title"></el-table-column>
			<el-table-column align="center" label="评论者邮箱" prop="email"></el-table-column>
			<el-table-column align="center" label="评论时间" prop="time_add"></el-table-column>
		</el-table>
		<div class="pagination" v-if="pagination.page_total>1">
			<el-pagination layout="prev, pager, next" :current-page="pagination.current_page" :total="pagination.total" :page-count="pagination.page_total" @current-change="pageChange" hide-on-single-page>
			</el-pagination>
		</div>
	</div>
</template>
<script>
import { apiCommentList } from '@/request/api.js'
export default {
	name: 'v-comments',
	data() {
		return {
			comments: [],
			pagination: {},
		}
	},
	methods: {
		//分页
		pageChange(page) {
			this.getComments(page)
			this.resetTableScrollTop()
		},

		getComments(page = 1) {
			apiCommentList(page).then(res => {
				this.comments = res.data
				this.pagination = res.pagination
			})
		}
	},
	created() {
		this.getComments()
	}
}
</script>
<style lang="less" scoped>
.v-comments {
	
}
</style>