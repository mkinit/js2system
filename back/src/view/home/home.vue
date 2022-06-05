<template>
	<div class="v-home js2-box" v-if="system_info">
		<div class="system-info">
			<div class="info-item">
				<span class="title">主机地址</span>
				{{system_info.host}}
			</div>
			<div class="info-item">
				<span class="title">服务器</span>
				{{system_info.server}}
			</div>
			<div class="info-item">
				<span class="title">PHP版本</span>
				{{system_info.php_version}}
			</div>
			<div class="info-item">
				<span class="title">数据库</span>
				{{system_info.db_version}}
			</div>
			<div class="info-item">
				<span class="title">框架版本</span>
				{{system_info.think_version}}
			</div>
		</div>
		<div class="content-quantity">
			<div class="info-item">
				<span class="title">分类数量</span>
				<router-link to="/category">{{system_info.category_count}}</router-link>
			</div>
			<div class="info-item">
				<span class="title">文章数量</span>
				<router-link to="/post?type=post">{{system_info.post_count}}</router-link>
			</div>
			<div class="info-item">
				<span class="title">评论数量</span>
				<router-link to="/comment">{{system_info.comment_count}}</router-link>
			</div>
			<div class="info-item">
				<span class="title">单页数量</span>
				<router-link to="/post?type=single">{{system_info.single_count}}</router-link>
			</div>
			<div class="info-item">
				<span class="title">留言数量</span>
				<router-link to="/guestbook">{{system_info.guestbook_count}}</router-link>
			</div>
			<div class="info-item">
				<span class="title">标签数量</span>
				<router-link to="/tag">{{system_info.tag_count}}</router-link>
			</div>
		</div>
	</div>
</template>
<script>
import { apiSystem } from '@/request/api.js'
export default {
	name: 'v-home',
	data() {
		return {
			system_info: null
		}
	},
	methods: {
		getSystem() {
			apiSystem().then(res => {
				this.system_info = res.data
			})
		}
	},
	created() {
		this.getSystem()
	}
}
</script>
<style lang="less">
.v-home {
	display: flex;
	justify-content: space-around;
	margin-top: 2em;

	.system-info,
	.content-quantity {
		width: 40%;
		font-size: 1.05em;
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;

		.info-item {
			border: 1px solid @border;
			box-shadow: 0 0 3px 0 @border;
			border-radius: .5em;
			padding: 1em;
			margin: .5em 0;
			width: 45%;
			color: @link;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			font-size: 1.25em;

			.title {
				line-height: 2;
				color: @font-second;
				font-size: 1rem;
			}
		}
	}
}
</style>