<template>
	<div class="v-register">
		<div class="js2-center-wrap w828">
			<div class="center-box">
				<div>
					<span class="item-title">电子邮箱：</span>
					<input type="text" v-model="email">
					<input type="button" value="发送验证码" @click="getVerifyCode">
				</div>
				<div>
					<span class="item-title">验证码：</span>
					<input type="text" v-model="verify_code">
				</div>
				<div>
					<span class="item-title">用户名：</span>
					<input type="text" v-model="username">
				</div>
				<div>
					<span class="item-title">密码：</span>
					<input type="password" v-model="password">
				</div>
				<div>
					<span class="item-title">确认密码：</span>
					<input type="password" v-model="password">
				</div>
				<div class="align-center"><input type="button" value="注册" @click="register"></div>
			</div>
		</div>
	</div>
</template>
<script>
import { mapState } from 'vuex'
const Cookie = process.client ? require('js-cookie') : undefined
export default {
	name: 'v-register',
	data() {
		return {
			username: '',
			email: '',
			verify_code: '',
			password: '',
			password_confirm: ''
		}
	},
	head() {
		const { setting_info: { site_name, site_keywords, site_description } } = this
		return {
			title: '注册 - ' + site_name,
			meta: [{
				name: 'keywords',
				content: site_keywords
			}, {
				name: 'description',
				content: site_description
			}]
		}
	},
	methods: {
		getVerifyCode() {
			const { email } = this
			if (!email) return confirm('请输入电子邮箱')
			this.$axios.get('api/mail/verify-code', { params: { email } }).then(res => {
				confirm('验证码发送成功')
			}).catch(err => {
				confirm(err.response.data.msg)
			})
		},
		register() {
			const { username, email, verify_code, password, password_confirm } = this
			if (!username) {
				return confirm('请输入用户名')
			}
			if (!email) {
				return confirm('请输入电子邮箱')
			}
			if (!verify_code) {
				return confirm('请输入验证码')
			}
			if (!password || password_confirm) {
				return confirm('请输入密码')
			}
			this.$axios.post('api/user/register', { username, password, email, verify_code }).then(res => {
				Cookie.set('user', JSON.stringify(res.data.data))
				this.$store.commit('setData', {
					key: 'user',
					data: res.data.data
				})
				this.$router.replace('/')
			}).catch(err => {
				confirm(err.response.data.msg)
			})
		}
	},
	async asyncData({ $axios }) {

	},
	computed: {
		...mapState(['setting_info'])
	},
	mounted() {

	}
}

</script>
<style lang="less">
.v-register {}

</style>
