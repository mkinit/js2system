<template>
	<main class="v-category">
		<header class="child-category js2-center-wrap w828" v-if="current_category&&current_category.children">
			<NuxtLink class="link" v-for="category in current_category.children" :to="{name:'category-id',params:{id:category.id}}" :key="category.id">{{category.name}}</NuxtLink>
		</header>
		<main class="js2-center-wrap w828">
			<article class="post" v-for="post in posts">
				<header>
					<h3 class="title">
						<NuxtLink :to="{name:'post-id',params:{id:post.id}}">
							{{post.title}}
						</NuxtLink>
					</h3>
				</header>
				<main>
					<div class="content">{{post.content | removeTag | cutString(180)}}</div>
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
		</main>
	</main>
</template>
<script>
import { mapState } from 'vuex'
import { recursion } from '@/common/tools.js'
export default {
	name: 'v-category',
	data() {
		return {
			posts: [],
		}
	},
	computed: {
		...mapState(['setting_info', 'categories']),
		current_category() {
			let category
			const id = this.$route.params.id
			recursion(this.categories, 'children', item => {
				if (item.id == id) {
					category = item
				}
			})
			return category
		}
	},
	head() {
		const id = this.$route.params.id
		let category
		recursion(this.categories, 'children', item => {
			if (item.id == id) {
				category = item
			}
		})
		return {
			title: category.name + ' - ' + this.setting_info.site_name,
			meta: [{
				name: 'description',
				content: category.description
			}]
		}
	},
	async asyncData({ $axios, params }) {
		const id = params.id
		const { data: posts } = await $axios.$get('api/posts', { params: { category_id: id } })
		return {
			posts
		}
	}
}

</script>
<style lang="less">
.child-category {
	margin-top: 2em;

	.link {
		display: inline-block;
		background-color: @link;
		color: #FFF;
		padding: 0 1em;
		line-height: 2;
		margin-right: 2em;
		border-radius: 4px;
	}
}

</style>
