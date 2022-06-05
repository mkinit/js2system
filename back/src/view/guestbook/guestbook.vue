<template>
	<div class="v-guestbook js2-box">
		<el-table :data="guestbooks" height="72vh">
			<el-table-column align="center" label="留言者" prop="name"></el-table-column>
			<el-table-column align="center" label="留言内容" prop="content"></el-table-column>
			<el-table-column align="center" label="电话" prop="phone"></el-table-column>
			<el-table-column align="center" label="电子邮箱" prop="email"></el-table-column>
			<el-table-column align="center" label="留言时间" prop="time_add"></el-table-column>
		</el-table>
		<div class="pagination" v-if="pagination.page_total>1">
			<el-pagination layout="prev, pager, next" :current-page="pagination.current_page" :total="pagination.total" :page-count="pagination.page_total" @current-change="pageChange" hide-on-single-page>
			</el-pagination>
		</div>
	</div>
</template>
<script>
import { apiGuestbookList } from '@/request/api.js'
export default {
	name: 'v-guestbook',
	data() {
		return {
			guestbooks: [],
			pagination: {},
		}
	},
	methods: {
		//分页
		pageChange(page) {
			this.getGuestbook(page)
			this.resetTableScrollTop()
		},

		getGuestbook(page = 1) {
			apiGuestbookList(page).then(res => {
				this.guestbooks = res.data
				this.pagination = res.pagination
			})
		}
	},
	created() {
		this.getGuestbook()
	}
}
</script>
<style lang="less" scoped>
.v-guestbook {
	
}
</style>