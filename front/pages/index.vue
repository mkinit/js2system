<template>
	<div class="v-index">
		<div class="swiper-container js2-center-wrap w828">
			<div class="swiper-wrapper">
				<div class="swiper-slide" v-for="banner in banners" :key="banner.id">
					<img :src="config.base_url+banner.image_url" alt="">
				</div>
			</div>
		</div>
		<div class="post-list js2-center-wrap w828">
			<article class="post" v-for="post in posts">
				<header>
					<h2 class="title">
						<NuxtLink :to="{name:'post-id',params:{id:post.id}}">
							{{post.title}}
						</NuxtLink>
					</h2>
				</header>
				<main>
					<div class="content">{{post.content | removeTag | cutString(180)}}</div>
				</main>
				<footer>
					<div>
						<span class="dashicons dashicons-calendar-alt"></span>
						<span>{{post.time_modify || post.time_add}}</span>
					</div>
					<div v-if="post.category">
						<span class="dashicons dashicons-category"></span>
						<NuxtLink class="link" :to="{name:'category-id',params:{id:post.category.id}}">
							{{post.category.name}}
						</NuxtLink>
					</div>
				</footer>
			</article>
		</div>
	</div>
</template>
<script>
import Swiper, { Autoplay, EffectFade } from 'swiper'
Swiper.use([Autoplay, EffectFade])
import 'swiper/swiper-bundle.min.css'
import config from '@/config.js'
import { mapState } from 'vuex'
export default {
	name: 'v-index',
	data() {
		return {
			swiper: null,
			config,
			posts: [],
			banners: []
		}
	},
	head() {
		const { setting_info: { site_name, site_keywords, site_description } } = this
		return {
			title: site_name + ' - ' + site_description,
			meta: [{
				name: 'keywords',
				content: site_keywords
			}, {
				name: 'description',
				content: site_description
			}]
		}
	},
	async asyncData({ $axios }) {
		const { data: posts } = await $axios.$get('api/posts')
		const { data: banners } = await $axios.$get('api/banners')
		return {
			posts,
			banners
		}
	},
	computed: {
		...mapState(['setting_info'])
	},
	mounted() {
		this.swiper = new Swiper('.swiper-container', {
			autoplay: {
				delay: 3000,
				disableOnInteraction: false,
			},
			effect: 'fade',
			fadeEffect: {
				crossFade: true,
			},
			speed: 1500,
			loop: true
		})
	}
}

</script>
<style lang="less">
.v-index {}

</style>
