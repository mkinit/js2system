<template>
	<div class="v-user js2-box">
		<el-table :data="users" height="72vh">
			<el-table-column align="center" label="用户编号" prop="id" width="100"></el-table-column>
			<el-table-column align="center" label="用户名" prop="username"></el-table-column>
			<el-table-column align="center" label="电子邮箱" prop="email"></el-table-column>
			<el-table-column align="center" label="用户角色">
				<template slot-scope="scope">
					{{scope.row.role.name}}
				</template>
			</el-table-column>
			<el-table-column align="center" label="注册时间" prop="time_add"></el-table-column>
			<el-table-column align="center" label="操作">
				<template slot-scope="scope">
					<el-button type="primary" size="mini" @click="userModify(scope.row.id)">编辑</el-button>
				</template>
			</el-table-column>
		</el-table>
		<el-dialog title="修改用户信息" :visible.sync="show_user" width="460px">
			<div class="user-info">
				<div class="info-item">
					<span class="item-title">用户编号：</span>
					<el-input disabled v-model="user_modify.id"></el-input>
				</div>
				<div class="info-item">
					<span class="item-title">用户名：</span>
					<el-input disabled v-model="user_modify.username"></el-input>
				</div>
				<div class="info-item">
					<span class="item-title">昵称：</span>
					<el-input v-model="user_modify.nickname"></el-input>
				</div>
				<div class="info-item">
					<span class="item-title">用户角色：</span>
					<el-select v-if="roles.length" v-model="user_modify.role_id" @change="$forceUpdate()">
						<el-option v-for="role in roles" :key="role.id" :label="role.name" :value="role.id"></el-option>
					</el-select>
				</div>
			</div>
			<div slot="footer" class="dialog-footer">
				<el-button @click="show_user=false">取 消</el-button>
				<el-button type="primary" @click="userModifyConfirm">确 定</el-button>
			</div>
		</el-dialog>
		<div class="pagination" v-if="pagination.page_total>1">
			<el-pagination layout="prev, pager, next" :current-page="pagination.current_page" :total="pagination.total" :page-count="pagination.page_total" @current-change="pageChange" hide-on-single-page>
			</el-pagination>
		</div>
	</div>
</template>
<script>
import { apiUserList, apiRoleList, apiUserModify } from '@/request/api.js'
export default {
	name: 'v-user',
	data() {
		return {
			show_user: false, //修改用户信息窗口
			user_modify: {}, //当前正在修改的用户信息
			users: [], //用户列表
			roles: [], //角色列表
			pagination: {},
		}
	},
	methods: {
		//分页
		pageChange(page) {
			this.getUserList(page)
			this.resetTableScrollTop()
		},

		getUserList(page = 1) {
			apiUserList(page).then(res => {
				this.users = res.data
				this.pagination = res.pagination
			})
		},
		userModify(id) {
			const user = JSON.parse(JSON.stringify(this.users.find(user => user.id == id)))
			user.role_id = user.role.id
			this.show_user = true
			this.user_modify = user
		},
		userModifyConfirm() {
			apiUserModify(this.user_modify).then(res => {
				this.users.forEach(user => {
					if (user.id == this.user_modify.id) {
						user = Object.assign(user, res.data)
					}
				})
				this.show_user = false
				this.user_modify = {}
				this.$message.success('用户信息已修改')
			})
		},
	},
	created() {
		this.getUserList()
		apiRoleList().then(res => {
			this.roles = res.data
		})
	}
}
</script>
<style lang="less">
.v-user {
	.user-info {
		&>div {
			margin: 10px 0;
		}

		.item-title {
			display: inline-block;
			min-width: 6em;
			text-align:right;
		}

		.info-item {
			display: flex;
			align-items: center;
		}
		.el-input{
			width: 200px;
		}
	}
}
</style>