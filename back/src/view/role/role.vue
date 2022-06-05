<template>
	<div class="v-role js2-box">
		<el-table :data="roles" height="81vh">
			<el-table-column align="center" label="角色编号" prop="id"></el-table-column>
			<el-table-column align="center" label="角色名称" prop="name"></el-table-column>
			<el-table-column align="center" label="角色说明" prop="description"></el-table-column>
			<el-table-column align="center" label="添加时间" prop="time_add"></el-table-column>
			<el-table-column align="center" label="修改时间" prop="time_modify"></el-table-column>
			<el-table-column align="center" label="操作">
				<template slot="header">
					<el-button @click="role_add_modal=true">添加角色</el-button>
				</template>
				<template slot-scope="scope">
					<el-button type="primary" size="mini" @click="$router.push({name:'role-edit', params: {id:scope.row.id}})">编辑</el-button>
					<el-button type="danger" size="mini" @click="roleDelete(scope.row.id)">删除</el-button>
				</template>
			</el-table-column>
		</el-table>
		<el-dialog title="添加角色" :visible.sync="role_add_modal" width="320px">
			<p>角色名称：<el-input type="text" v-model="role_name"></el-input>
			</p>
			<p>角色描述：<el-input type="text" v-model="role_description"></el-input>
			</p>
			<span slot="footer" class="dialog-footer">
				<el-button @click="role_add_modal = false">取 消</el-button>
				<el-button type="primary" @click="roleAdd">确 定</el-button>
			</span>
		</el-dialog>
	</div>
</template>
<script>
import { apiRoleList, apiRoleAdd, apiRoleDelete } from '@/request/api.js'
export default {
	name: 'v-role',
	data() {
		return {
			role_add_modal: false, //添加角色窗口
			role_name: '', //正在添加的角色信息
			role_description: '', //正在添加的角色信息
			roles: [], //角色列表
		}
	},
	methods: {
		roleDelete(id) {
			this.$messageBox('是否要删除角色？', '提示', {
				type: 'warning'
			}).then(() => {
				apiRoleDelete(id).then(() => {
					this.roles.splice(this.roles.findIndex(role => role.id === id), 1)
					this.$message.success('角色已删除')
				})
			}).catch(() => {})
		},
		roleAdd() {
			apiRoleAdd(this.role_name, this.role_description).then(res => {
				this.$message.success('添加成功')
				this.role_name = this.role_description = ''
				this.roles.push(res.data)
				this.role_add_modal = false
			})
		},
		getRoleList() {
			apiRoleList().then(res => {
				this.roles = res.data
			})
		},
	},
	created() {
		this.getRoleList()
	}
}
</script>
<style lang="less">
.v-role {}
</style>