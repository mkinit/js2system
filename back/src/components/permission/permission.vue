<template>
	<div class="c-permission" :style="{display}" v-if="show">
		<slot></slot>
	</div>
</template>
<script>
//权限组件，用于包裹需要权限的按钮或元素
import { mapState } from 'vuex'
export default {
	name: 'c-permission',
	props: {
		//display样式，默认行内块元素
		display: {
			default: 'block-inline',
			type: String
		},
		//权限标识，控制器和方法的组合形式：controll-action
		permission: {
			default: '',
			type: String,
			require: true
		},
	},
	computed: {
		...mapState(['user']),
		show() {
			const user = JSON.parse(JSON.stringify(this.user))
			const res = user.role.action_list.some(item => (item.controller.toLowerCase() + '-' + item.action.toLowerCase()) == this.permission.toLowerCase())

			return res
		}
	}
}
</script>
<style lang="less">
.c-permission{
	display: inline-block;
	&+.el-button,&+.c-permission{
		margin-left:10px;
	}
}
</style>