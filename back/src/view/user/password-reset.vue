<template>
	<div class="v-password-reset">
		<div class="form-box js2-box">
			<div class="session-title">重置密码</div>
			<el-form label-position="right" ref="password_reset" :model="password_reset" :rules="rules">
				<el-form-item label-width="7em" label="邮箱：" prop="email" class="email-item">
					<el-input v-model="password_reset.email"></el-input>
					<el-button @click="getVerifyCode">获取验证码</el-button>
				</el-form-item>
				<el-form-item label-width="7em" label="验证码：" prop="verify_code">
					<el-input v-model="password_reset.verify_code"></el-input>
				</el-form-item>
				<el-form-item label-width="7em" label="密码：" prop="password">
					<el-input type="password" v-model="password_reset.password"></el-input>
				</el-form-item>
				<el-form-item label-width="7em" label="确认密码：" prop="password_confirm">
					<el-input type="password" v-model="password_reset.password_confirm"></el-input>
				</el-form-item>
			</el-form>
			<div class="bototom">
				<el-button type="primary" @click="confirm">确定</el-button>
			</div>
		</div>
	</div>
</template>
<script>
import { apiVerifyCode, apiPasswordReset } from '@/request/api.js'
export default {
	name: "v-password-reset",
	data() {
		return {
			password_reset: {
				email: '',
				verify_code: '',
				password: '',
				password_confirm: '',
			},
			rules: {
				email: [
					{ required: true, message: '请输入新邮箱', trigger: 'blur' }
				],
				verify_code: [
					{ required: true, message: '请输入验证码', trigger: 'blur' }
				],
				password: [
					{ required: true, message: '请输入密码', trigger: 'blur' }
				],
				password_confirm: [
					{ required: true, message: '请再次输入密码', trigger: 'blur' }
				]
			}
		};
	},
	methods: {
		confirm() {
			const { password_reset } = this
			if(password_reset.password!=password_reset.password_confirm){
				return this.$message.error('两次输入的密码不一致')
			}
			this.$refs.password_reset.validate(res => {
				if (res) {
					apiPasswordReset(password_reset).then(()=>{
						this.$message.success('密码已重置，请登录')
						this.$router.back()
					})
				}
			})
		},
		getVerifyCode() {
			const { password_reset } = this
			if (!password_reset.email) return this.$message.error('请输入新邮箱')
			apiVerifyCode(password_reset.email).then(() => {
				this.$message.success('验证码发送成功')
			})
		}
	},
};
</script>
<style lang="less">
.v-password-reset {

	.form-box {
		margin: 15vh auto 0;
		min-width: 320px;
		max-width: 420px;
		box-shadow: 1px 1px 10px @font-second;
		padding: 1.5em;

		.email-item {
			.el-form-item__content {
				display: flex;
			}
		}

		.el-form-item {
			margin-bottom: 1em;
		}
	}

	.session-title {
		font-size: 1.5em;
		margin-bottom: 0.5em;
		text-align: center;
	}

	.bototom {
		text-align: center;
		margin-top: 1em;
	}
}
</style>