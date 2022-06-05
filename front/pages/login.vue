<template>
	<div class="v-login">
		<div class="js2-center-wrap w828">
			<div class="center-box" v-if="!user">
				<div>
					<span class="item-title">用户名：</span>
					<input type="text" v-model="username">
				</div>
				<div>
					<span class="item-title">密码：</span>
					<input type="password" v-model="password">
				</div>
				<c-drag-verify @result="verifyResult"></c-drag-verify>
				<div class="align-center"><input type="button" value="登录" @click="login"></div>
			</div class="center-box">
			<div v-else>当前已是登录状态</div>
		</div>
	</div>
</template>
<script>
import { mapState } from 'vuex'
const Cookie = process.client ? require('js-cookie') : undefined
export default {
	name: 'v-login',
	data() {
		return {
			username: '',
			password: '',
			verify_result:false
		}
	},
	head() {
		const { setting_info: { site_name, site_keywords, site_description } } = this
		return {
			title: '登录 - ' + site_name,
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
		verifyResult(result){
			this.verify_result = result
		},
		login() {
			if(!this.verify_result) return confirm('请先拖动验证器')
			const { username, password } = this
			this.$axios.post('api/user/login', { username, password }).then(res => {
				Cookie.set('user', JSON.stringify(res.data.data))
				this.$store.commit('setData', {
					key: 'user',
					data: res.data.data
				})
				this.$router.back()
			}).catch(err => {
				confirm(err.response.data.msg)
			})
		}
	},
	async asyncData({ $axios }) {

	},
	computed: {
		...mapState(['setting_info', 'user'])
	},
	mounted() {},
}

</script>
<style lang="less">
.v-login {

}

</style>
