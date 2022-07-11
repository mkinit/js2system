<template>
	<div class="v-login">
		<div class="login-box js2-box">
			<div class="login-title">JS2-SYSTEM</div>
			<template v-if="!user">
				<div class="item">
					<el-input v-model="username" placeholder="请输入用户名" prefix-icon="el-icon-user"></el-input>
				</div>
				<div class="item">
					<el-input @keyup.enter.native="login" v-model="password" placeholder="请输入密码" prefix-icon="el-icon-key" show-password></el-input>
				</div>
				<div class="bototom">
					<el-button type="primary" class="login-btn" @click="login">登录</el-button>
					<el-button type="text" class="login-btn" @click="$router.push('/password-reset')">忘记密码</el-button>

				</div>
			</template>
			<div class="status js2-text-center" v-else>
				当前是已登陆状态，<el-button type="primary" @click="logoutConfirm">退出登陆</el-button>
			</div>
		</div>
	</div>
</template>
<script>
import { apiLogin } from "@/request/api.js";
import logout from '@/common/logout.js'
export default {
	name: "v-login",
	data() {
		return {
			username: '',
			password: '',
		};
	},
	methods: {
		login() {
			const { username, password } = this
			const redirect = this.$route.query.redirect
			apiLogin({ username, password }).then((res) => {
				localStorage.setItem('user', JSON.stringify(res.data))
				this.update({ key: 'user', data: res.data })
				if (redirect) {
					this.$router.push({ name: redirect })
				} else {
					this.$router.replace({ path: '/' })
				}
				this.update({ key: 'user', data: res.data })
				if (this.password == '123456') {
					this.$message.warning('为了系统安全，请尽快将初始密码修改（通过右上角用户名进入）！')
				}
			})
		},
		logoutConfirm() {
			this.$messageBox
				.confirm('是否要退出登录', '提示', { type: 'warning' })
				.then(() => {
					logout()
				})
				.catch(() => {})
		},
	},
};
</script>
<style lang="less">
.v-login {

	.login-box {
		margin: 15vh auto 0;
		min-width: 320px;
		max-width: 375px;
		box-shadow: 1px 1px 1em @font-second;
		padding: 1em 2em 2.5em;

		.item {
			margin: .5em auto;
			width: 80%;
		}
	}

	.login-title {
		font-size: 2em;
		margin-bottom: 1em;
		text-align: center;
	}

	.bototom {
		display: flex;
		justify-content: center;
		align-items: center;
		margin-top: 1em;
	}
}
</style>