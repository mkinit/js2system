<template>
	<div id="app" :class="'page-'+$route.name">
		<Header v-if="user"></Header>
		<main class="js2-center-wrap w1920">
			<aside v-if="user" class="aside js2-scroll-wrap">
				<Sidebar></Sidebar>
			</aside>
			<div class="router-view js2-scroll-wrap">
				<!-- <h2 class="page-title">{{ $route.meta.title }}</h2> -->
				<keep-alive>
					<router-view v-if="$route.meta.keep_alive"></router-view>
				</keep-alive>
				<router-view v-if="!$route.meta.keep_alive"></router-view>
			</div>
		</main>
	</div>
</template>
<script>
import Header from "@/components/header/header.vue";
import Sidebar from "@/components/sidebar/sidebar.vue";
export default {
	name: "App",
	components: {
		Header,
		Sidebar,
	},
	created() {
		this.$store.dispatch('actionFileList')
	},
	mounted() {
		if (window.innerWidth <= 1024) {
			this.$store.commit('update', {
				key: 'sidebar_collapse',
				data: true
			})
		}
	}
};
</script>
<style lang="less">
#app {}

.v-header {}

main {
	display: flex;
	background-color: @bg-second;

	.aside {
		border-right: 1px solid @bg;
		height: 100vh;
		padding-top: @headerHeight;
		background-color: @font-second;
	}

	.router-view {
		width: 100%;
		position: relative;
		padding-top: @headerHeight;
		height: 100vh;
		font-size: 14px;

		.page-title {
			margin-top: 1em;
			text-align: center;
			padding-bottom: .5em;
		}
	}
}

.pagination {
	text-align: center;
	margin-top: 1em;
	background-color: #FFF;
	padding: 1em 0;
}

.page-head {
	display: flex;
	padding-bottom: 1em;

	&>* {
		margin-right: 1em;

		&:last-child {
			margin-right: 0;
		}
	}
}
</style>