<template>
	<main class="v-post">
		<div class="js2-center-wrap w828">
			<article class="post">
				<header>
					<h2 class="title">
						{{post.title}}
					</h2>
				</header>
				<main>
					<div class="content" v-html="post.content"></div>
				</main>
				<footer>
					<div>
						<span class="dashicons dashicons-calendar-alt"></span>
						<span>{{post.time_modify || post.time_add}}</span>
					</div>
					<div v-if="post.type=='post'">
						<span class="dashicons dashicons-category"></span>
						<NuxtLink class="link" :to="{name:'category-id',params:{id:post.category.id}}">
							{{post.category.name}}
						</NuxtLink>
					</div>
				</footer>
			</article>
			<div class="comment-on">
				<h3 class="comments-title">发表评论</h3>
				<div v-if="user">以 <span class="highlight-text">{{user.nickname}}</span> 的身份发表评论</div>
				<div v-else>
					<input type="text" v-model="nickname" placeholder="昵称">
					<input type="text" v-model="email" placeholder="电子邮件">
				</div>
				<textarea v-model="content" placeholder="来，一针见血"></textarea>
				<button @click="commentSubmit">发布评论</button>
			</div>
			<div class="comments">
				<h3>看看大家是怎么评论的：</h3>
				<div class="comments-list">
					<div class="comment" v-for="comment in comments" :key="comment.id">
						<div class="comment-head">
							<span class="nickname">{{comment.nickname}}</span>
							<span class="time">{{comment.time}}</span>
						</div>
						<div class="comment-main">
							{{comment.content}}
						</div>
					</div>
				</div>
				<div class="bottom-action" v-if="more">
					<div class="more-btn" @click="getComments">查看更多评论</div>
				</div>
			</div>
		</div>
	</main>
</template>
<script>
import { mapState } from 'vuex'
export default {
	name: 'v-post',
	data() {
		return {
			post: null,
			comments: [], //评论列表
			pagination: null, //评论分页信息
			more: true, //更多评论
			nickname: '',
			email: '',
			content: ''

		}
	},
	computed: {
		...mapState(['setting_info','user'])
	},
	head() {
		return {
			title: (this.post ? this.post.title : '') + ' - ' + this.setting_info.site_name,
			meta: [{
				name: 'description',
				content: this.post ? this.post.content : ''
			}]
		}
	},
	methods: {
		commentSubmit() {
			const { nickname, email, content, post } = this
			if(!this.user){
				if(!nickname||!email||!content){
					confirm('请填写昵称、邮箱和内容')
				}
			}
			this.$axios.post('api/comments/add', {
				post_id: post.id,
				nickname:this.user?this.user.nickname:nickname,
				email:this.user?this.user.email:email,
				content
			}).then(res => {
				this.content = ''
				this.comments.unshift(res.data.data)
			})
		},
		async getComments() {
			const { data: { data: comments, pagination } } = await this.$axios.get('api/comments', {
				params: {
					post_id: this.post.id,
					page: this.pagination.current_page + 1
				}
			})
			this.pagination = pagination
			this.more = pagination.total > pagination.page_size * pagination.current_page
			this.comments.push(...comments)
		}
	},
	async asyncData({ store, $axios, params }) {
		const { data: post } = await $axios.$get('api/posts/' + params.id)
		store.commit('setData', {
			key: 'post',
			data: post
		})
		const { data: comments, pagination } = await $axios.$get('api/comments', {
			params: {
				post_id: post.id
			}
		})
		const more = pagination.total > pagination.page_size * pagination.current_page
		return {
			post,
			comments,
			pagination,
			more
		}
	}
}

</script>
<style lang="less">
.v-post {
	.post {
		.title {
			text-align: center;
		}

		main {
			margin-top: 3em;
		}
	}

	.comments-title {
		margin-bottom: 1em;
	}

	.comments {
		margin-top:1em;
		.comment {
			border-bottom: 1px dashed @line;
			margin: 1.5em 0;
			padding-bottom: 1.5em;

			&:last-child {
				border-bottom: none;
			}
		}

		.comment-head {
			display: flex;
			justify-content: space-between;
		}

		.nickname {
			font-weight: bold;
			color: @link;
		}

		.time {
			font-size: .85em;
			opacity: .82;
		}

		.comment-main {
			margin-top: .5em;
		}
	}

	.bottom-action {
		text-align: center;
		padding-top: 1em;
	}

	.more-btn {
		display: inline-block;
		border: 1px solid @link;
		padding: 3px 9px;
		border-radius: 4px;

		&:hover {
			color: @link;
		}
	}

	.comment-on {
		margin-top: 2em;
		border-bottom: 1px solid @line;
		padding-bottom: 1em;

		input {
			margin-right: 1em;

			&:last-child {
				margin-right: 0;
			}
		}

		textarea {
			min-height: 8em;
			margin-top: 1em;
		}

		button {
			margin-top: 1em;
			line-height: 2;
		}
	}
}

</style>
