<template>
	<div class="js2-drag-verify" :style="{backgroundColor:verify_bg,borderColor:verify_bg}">
		{{verify?'验证成功':'按住开关，滑动到右边'}}
		<div class="drag-verify-btn" ref="js2-drag-verify-btn" @mousedown="move" :style="{left:left+'px'}"></div>
	</div>
</template>
<script>
export default {
	name: 'c-drag-verify',
	data() {
		return {
			left: 0, //拖动按钮当前的偏移量
			max_left: 0, //拖动按钮能移动的最大偏移量
			verify: false,
			text: ''
		}
	},
	mounted() {
		const drag_verify_btn = this.$refs['js2-drag-verify-btn'] //拖动按钮
		const parent_node = drag_verify_btn.parentNode //父元素
		const btn_width = parseInt(getComputedStyle(drag_verify_btn).width) //拖动按钮宽度
		const parent_border_width = parseInt(getComputedStyle(parent_node).borderWidth) * 2 //父元素边框宽度
		const parent_width = parseInt(getComputedStyle(parent_node).width) - parent_border_width //父元素宽度
		this.max_left = parent_width - btn_width //最大宽度
	},
	computed:{
		verify_bg(){
			return this.verify?'#99CC66':'#FF6666'
		}
	},
	methods: {
		move(btn) {
			const x = btn.clientX - btn.target.offsetLeft //鼠标按下的位置

			document.onmousemove = (event) => { //鼠标按下并移动的事件
				if (this.verify) return
				const left = event.clientX - x
				if (left <= 0) {
					this.left = 0
					btn.target.style.left = 0
					return
				}
				if (left >= this.max_left) {
					this.left = this.max_left
					return
				}

				this.left = left
			};
			document.onmouseup = () => {
				if (this.left == this.max_left) {
					this.verify = true
					this.$emit('result',true)
				} else {
					this.left = 0
					this.$emit('result',false)
				}
				document.onmousemove = null;
				document.onmouseup = null;
			};
		}
	},
}

</script>
<style lang="less">
.js2-drag-verify {
	border-radius: 100px;
	text-align: center;
	line-height: 36px;
	color: #FFF;
	position: relative;
	user-select: none;
	border-width: 2px;
	border-style: solid;
	transition: all .5s linear;

	.drag-verify-btn {
		height: 36px;
		width: 36px;
		position: absolute;
		z-index: 10;
		left: 0;
		top: 0;
		background-color: #FFF;
		border-radius: 50%;
	}
}

</style>
