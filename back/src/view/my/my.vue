<template>
	<div class="v-my js2-box" v-if="user_info">
		<div class="user-item">
			<span class="title">用户名：</span>
			<el-input v-model="user_info.username" disabled></el-input>
		</div>
		<div class="user-item">
			<span class="title">昵称：</span>
			<el-input v-model="user.nickname"></el-input>
		</div>
		<div class="user-item">
			<span class="title">原密码：</span>
			<el-input type="password" v-model="password_old"></el-input>
			<span class="desc">（不修改密码时不填即可）</span>
		</div>
		<div class="user-item">
			<span class="title">新密码：</span>
			<el-input type="password" v-model="password_new"></el-input>
		</div>
		<div class="user-item">
			<span class="title">确认新密码：</span>
			<el-input type="password" v-model="password_new_confirm"></el-input>
		</div>
		<div class="bottom">
			<el-button type="primary" @click="save">保存</el-button>
		</div>
	</div>
</template>
<script>
import { apiUserUpdate } from '@/request/api.js'
export default {
	name: 'v-my',
	data() {
		return {
			user_info: null,
			password_old: '',
			password_new: '',
			password_new_confirm: '',
		}
	},
	methods: {
		save() {
			const data = {
				nickname: this.user_info.nickname
			}
			if(this.password_old){
				if(!this.password_new||!this.password_new_confirm){
					return this.$message.warning("请输入新密码和确认密码")
				}
				if(this.password_new!==this.password_new_confirm){
					return this.$message.warning("新密码和确认密码不一致")
				}
				data.password_old = this.password_old
				data.password_new = this.password_new
			}
			apiUserUpdate(data).then(res => {
				this.$message.success("保存成功")
				this.password_old = this.password_new = this.password_new_confirm = ''
				this.update({ key: "user", data: res.data })
			})
		}
	},
	mounted() {
		const user = this.$store.state.user
		this.user_info = JSON.parse(JSON.stringify(user))
	}
}
</script>
<style lang="less">
.v-my {
	.el-input {
		width: auto;
	}

	.user-item {
		margin: 1em;
		display: flex;
		line-height: 32px;
		.title{
			min-width:6em;
			text-align: right;
		}
	}

	.bottom {
		margin-top: 2em;
		text-align: center;
	}
}
</style>