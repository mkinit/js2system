<template>
	<div class="v-my js2-box" v-if="user_info">
		<div class="item">
			<span class="title">用户名：</span>
			<el-input v-model="user_info.username" disabled></el-input>
		</div>
		<div class="item">
			<span class="title">邮箱：</span>
			<el-input v-model="user_info.email" disabled></el-input>
			<el-button type="text" class="tip" @click="dialog_email=true">修改邮箱</el-button>
		</div>
		<div class="item">
			<span class="title">昵称：</span>
			<el-input v-model="user.nickname"></el-input>
		</div>
		<div class="item">
			<span class="title">原密码：</span>
			<el-input type="password" v-model="password_old"></el-input>
			<span class="tip">（不修改密码时不填即可）</span>
		</div>
		<div class="item">
			<span class="title">新密码：</span>
			<el-input type="password" v-model="password_new"></el-input>
		</div>
		<div class="item">
			<span class="title">确认新密码：</span>
			<el-input type="password" v-model="password_new_confirm"></el-input>
		</div>
		<div class="bottom">
			<el-button type="primary" @click="save">保存</el-button>
		</div>
		<el-dialog :visible.sync="dialog_email">
			<el-form label-position="right" ref="email_modify" :model="email_modify" :rules="rules">
				<el-form-item label="新邮箱：" prop="email">
					<el-input v-model="email_modify.email"></el-input>
					<el-button @click="getVerifyCode">获取验证码</el-button>
				</el-form-item>
				<el-form-item label="验证码：" prop="verify_code">
					<el-input v-model="email_modify.verify_code"></el-input>
				</el-form-item>
			</el-form>
			<div class="bottom">
				<el-button type="primary" @click="emailModify">确定</el-button>
			</div>
		</el-dialog>
	</div>
</template>
<script>
import { apiUserUpdate, apiEmailUpdate, apiVerifyCode } from '@/request/api.js'
export default {
	name: 'v-my',
	data() {
		return {
			dialog_email: false,
			user_info: null,
			password_old: '',
			password_new: '',
			password_new_confirm: '',
			email_modify: {
				email: '',
				verify_code: ''
			},
			rules: {
				email: [
					{ required: true, message: '请输入新邮箱', trigger: 'blur' }
				],
				verify_code: [
					{ required: true, message: '请输入验证码', trigger: 'blur' }
				]
			}
		}
	},
	methods: {
		emailModify() {
			this.$refs.email_modify.validate(res => {
				if (res) {
					const { email_modify, user_info } = this
					apiEmailUpdate(email_modify).then(() => {
						this.$message.success('修改成功')
						this.dialog_email = false
						user_info.email = email_modify.email
						this.update({
							key: 'user',
							data: user_info
						})
					})
				}
			})
		},
		save() {
			const {user_info,password_old,password_new,password_new_confirm} = this
			const data = {
				nickname:user_info.nickname
			}
			if (password_old) {
				if (!password_new || !password_new_confirm) {
					return this.$message.warning('请输入新密码和确认密码')
				}
				if (password_new !== password_new_confirm) {
					return this.$message.warning('新密码和确认密码不一致')
				}
				data.password_old = password_old
				data.password_new = password_new
			}
			apiUserUpdate(data).then(res => {
				password_old?this.$message.success('保存成功，下次登录使用新密码'):this.$message.success('保存成功')
				this.password_old = this.password_new = this.password_new_confirm = ''
				const data = Object.assign(user_info, res.data)
				this.user_info = data
				this.update({ key: 'user', data })
			})
		},
		getVerifyCode() {
			const {email_modify,user_info}=this
			if(!email_modify.email) return this.$message.error('请输入新邮箱')
			if(email_modify.email==user_info.email) return this.$message.error('新邮箱和原邮箱一致，不需要修改')
			apiVerifyCode(email_modify.email).then(() => {
				this.$message.success('验证码发送成功')
			})
		}
	},
	watch: {
		'$store.state.user': {
			immediate: true,
			handler(user) {
				this.user_info = JSON.parse(JSON.stringify(user))
			}
		}
	},
	mounted() {}
}
</script>
<style lang="less">
.v-my {
	.el-input {
		width: auto;
	}

	.item {
		margin: 1em;
		display: flex;
		line-height: 32px;

		.title {
			min-width: 6em;
			text-align: right;
		}

		.tip {
			font-size: .875em;
			margin-left: .5em;
		}
	}

	.bottom {
		margin-top: 2em;
		text-align: center;
	}
}
</style>