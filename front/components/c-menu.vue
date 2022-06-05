<template>
	<div class="c-menu" :class="{show}">
		<span @click="menuToggle" v-if="show" class="menu-btn dashicons dashicons-no-alt"></span>
		<span @click="menuToggle" v-else class="menu-btn dashicons dashicons-menu-alt"></span>
		<ul class="menu-list">
			<li>
				<NuxtLink to="/">首页</NuxtLink>
			</li>
			<template v-if="menu_list.length">
				<li v-for="menu in menu_list[0].list">
					<a v-if="menu.type==='custom'" href="menu.link" target="_blank">
						{{menu.label}}
					</a>
					<NuxtLink v-else :to="{name:menu.type+'-id',params:{id:menu.link}}">
						{{menu.label}}
					</NuxtLink>
					<ul class="sub-menu" v-if="menu.children&&menu.children.length">
						<li v-for="sub_menu in menu.children">
							<NuxtLink :to="{name:(sub_menu.type==='post'||sub_menu.type==='single')?'post-id':sub_menu.type+'-id',params:{id:sub_menu.link}}">
								{{sub_menu.label}}
							</NuxtLink>
						</li>
					</ul>
				</li>
			</template>
		</ul>
	</div>
</template>
<script>
import { mapState } from 'vuex'
export default {
	name: 'c-menu',
	data() {
		return {
			show: false
		}
	},
	methods: {
		menuToggle() {
			this.show = !this.show
		}
	},
	computed: {
		...mapState(['menu_list'])
	}
}

</script>
<style lang="less">
.c-menu {
	font-size: 1.05em;
	position: fixed;
	left: -150px;
	top: 0;
	height: 100vh;
	min-width: 200px;
	text-align: center;
	border-right: 1px solid @line;
	padding: 4em 0;
	transition: all .3s linear;
	background-color: #FFF;
	z-index:1000;

	.dashicons {
		width: 40px;
		height: 40px;
		font-size: 40px;
	}

	&.show {
		left: 0;
	}

	.menu-btn {
		position: absolute;
		top: 5px;
		right: 5px;
		z-index: 10;

		&:hover {
			color: @danger;
		}
	}

	.menu-list {

		li {
			transition: all .1s linear;
			position: relative;

			&:hover {

				&>.sub-menu {
					animation: duang linear 1s;
					transform: translateX(-50%) scaleY(1);
				}
			}
		}

		a {
			transition: all .5s linear;
			line-height: 2.5;
			padding: 0 1em;

			&:hover {
				text-shadow: 0 -1.1em 0 @font-light;
			}
		}
	}

	/*二级菜单*/
	.sub-menu {
		min-width: 80%;
		font-size: .95em;
		position: absolute;
		left: 50%;
		top: 100%;
		box-shadow: 0 0 5px 0 @font-light;
		border-radius: 5px;
		transform: translateX(-50%) scaleY(0);
		transform-origin: center top;
		z-index: 10;
		text-align: center;
		padding: 1em 0;
		background-color: #FFF;

		&::after {
			content: "";
			display: block;
			position: absolute;
			left: 50%;
			margin-left: -4px;
			top: -9px;
			width: 1px;
			border-bottom: 8px solid @font-light;
			border-left: 5px solid transparent;
			border-right: 5px solid transparent;
		}

		li {
			white-space: nowrap;
		}
	}

	@keyframes duang {
		25% {
			transform: translateX(-50%) scaleY(.9);
		}

		45% {
			transform: translateX(-50%) scaleY(1);
		}

		60% {
			transform: translateX(-50%) scaleY(.95);
		}

		75% {
			transform: translateX(-50%) scaleY(1);
		}

		85% {
			transform: translateX(-50%) scaleY(.98);
		}

		100% {
			transform: translateX(-50%) scaleY(1);
		}
	}
}

</style>
