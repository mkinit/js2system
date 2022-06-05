<template>
	<div class="v-user">
		<div class="js2-center-wrap w828">
			<div class="center-box" v-if="user">
				<div>
					<span class="item-title">用户名：</span>
					{{user.username}}
				</div>
				<div>
					<span class="item-title">用户角色：</span>
					{{user.role.name}}
				</div>
				<div>
					<span class="item-title">昵称：</span>
					<input type="text" v-model='nickname' :placeholder="user.nickname">
				</div>
				<div>
					<span class="item-title">邮箱：</span>
					{{user.email}}
				</div>
				<div>
					<span class="item-title">手机：</span>
					{{user.phone}}
				</div>
				<div class="align-center"><input type="button" value="修改资料" @click="modify"></div>
			</div>
		</div>
	</div>
</template>
<script>
import { mapState } from 'vuex'
const Cookie = process.client ? require('js-cookie') : undefined
export default {
	middleware: 'login',
	name: 'v-user',
	data() {
		return {
			nickname: '',
		}
	},
	head() {
		const { setting_info: { site_name, site_keywords, site_description } } = this
		return {
			title: '用户中心 - ' + site_name,
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
		modify(){
			const {nickname} = this
			const data = {}
			if(nickname){
				data.nickname = nickname
			}
			this.$axios.put('api/user/update',data).then(res=>{
				Cookie.set('user', JSON.stringify(res.data.data))
				this.$store.commit('setData', {
					key: 'user',
					data: res.data.data
				})
				confirm('资料修改成功')
			}).catch(err=>{
				confirm(err.response.data.msg)
			})
		}
	},
	async asyncData({ $axios }) {

	},
	computed: {
		...mapState(['setting_info', 'user'])
	},
	mounted() {

	}
}

</script>
<style lang="less">
.v-user {
}

</style>
