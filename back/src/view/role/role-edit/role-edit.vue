<template>
	<div class="v-role-edit js2-box" v-if="role&&role.modify">
		<div class="page-head">
			<div>角色名称：<el-input type="text" v-model="role.name"></el-input></div>
			<div>角色描述：<el-input type="text" v-model="role.description"></el-input></div>
			<el-button type="primary" size="mini" @click="roleUpdate">修改角色信息</el-button>
		</div>
		<el-divider>权限</el-divider>
		<div>
			<div class="action-group" v-for="item in role.action_list" :key="item.group">
				<div class="group-title">{{item.group}}</div>
				<div class="group-list">
					<el-checkbox v-for="action in item.action" :key="action.id" v-model="action.checked">{{action.name}}</el-checkbox>
				</div>
			</div>
			<div class="action-bottom">
				<el-button type="primary" @click="roleUpdateAction">更新权限</el-button>
			</div>
		</div>
	</div>
	<div v-else style="text-align:center;padding-top:3em;">系统默认角色不允许修改</div>
</template>
<script>
import { apiActionList, apiRole, apiRoleUpdate, apiRoleUpdateAction } from '@/request/api.js'
export default {
	name: 'v-role-edit',
	data() {
		return {
			role: null,
			action_list: [], //权限列表
		}
	},
	watch: {
		action_list() {
			const {role} = this
			const action_list = JSON.parse(JSON.stringify(this.action_list))
			for (let key in action_list) {
				action_list[key].action.forEach(action_origin => {
					action_origin.checked = false
					if (role.action_list.find(action => action_origin.id == action.id)) {
						action_origin.checked = true
					}
				})
			}
			this.role.action_list = action_list
		}
	},
	methods: {
		roleUpdateAction() {
			const {id,action_list} = this.role
			const action_ids = []
			for (let key in action_list) {
				action_list[key].action.forEach(action => {
					if (action.checked) {
						action_ids.push(action.id)
					}
				})
			}
			apiRoleUpdateAction(id,action_ids.toString()).then(() => {
				this.$message.success('角色权限已更新')
			})
		},
		roleUpdate() {
			apiRoleUpdate(this.role).then(() => {
				this.$message.success('角色信息已更新')
			})
		},
		getActionList() {
			apiActionList().then(res => {
				this.action_list = res.data
			})
		},
		getRole(id) {
			apiRole(id).then(res => {
				this.role = res.data
				this.getActionList()
			})
		},
	},
	created() {
		const { id } = this.$route.params
		this.getRole(id)
	}
}
</script>
<style lang="less">
.v-role-edit {
	.page-head {
		&>div {
			display: flex;
			align-items: center;
			margin-right: 2em;
			white-space: nowrap;
		}
	}

	.action-group {
		display: flex;
		margin-bottom: 1em;
		border-bottom: 1px dashed @bg;
		padding-bottom: .75em;

		.group-title {
			min-width: 6em;
		}
	}

	.action-bottom {
		text-align: center;
	}
}
</style>