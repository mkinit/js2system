<template>
	<div class="c-media" v-show="display">
		<div class="c-media-box">
			<div class="file-header">
				<input ref="file" type="file" multiple hidden @change="upload" />
				<el-button type="primary" @click="$refs['file'].click()">文件上传</el-button>
				<h3>请选择文件</h3>
				<i class="el-icon-close" @click="hide"></i>
			</div>
			<div class="file-list">
				<div class="file-item" :class="{current:file.selected}" v-for="(file,index) in file_list" :key="file.id" @click="select(index)">
					<div class="file-box">
						<img v-if="file.type==='image'" :src="base_url+file.name">
						<i v-else :class="'el-icon el-icon-' + (file.type==='video'?'video-camera':file.type==='audio'?'headset':'document')"></i>
					</div>
				</div>
			</div>
			<div class="pagination" v-if="pagination&&pagination.page_total>1">
				<el-pagination layout="prev, pager, next" :current-page="pagination.current_page" :total="pagination.total" :page-count="pagination.page_total" @current-change="pageChange" :page-size="20" hide-on-single-page>
				</el-pagination>
			</div>
			<div class="bottom">
				<el-button type="primary" @click="selectConfirm">选好了</el-button>
			</div>
		</div>
	</div>
</template>
<script>
/*
		媒体库组件，所有需要上传的页面使用该组件，防止上传重复的文件，同页面有多处上传需自行处理标识
		打开媒体库：this.$refs['媒体库ref']show()
		关闭媒体库：this.$refs['媒体库ref']hide()
		@props result 自定义事件函数 接收选中的文件列表
		@return Array 选中的文件列表
	*/
import { apiUpload } from '@/request/api.js'
import { mapActions, mapState } from 'vuex'
export default {
	name: 'c-media',
	data() {
		return {
			file_list: [],
			pagination: null,
			display: false,
			current: -1, //当前选中的文件序号
		}
	},
	methods: {
		...mapActions(['actionFileList']),
		select(index) {
			const file_list = this.file_list
			file_list.forEach((item, index2) => {
				if (index2 == index) {
					if (item.selected) {
						item.selected = false
					} else {
						item.selected = true
					}
				}
			})
			this.file_list = []
			this.file_list = file_list
		},
		selectConfirm() {
			const data = this.file_list.filter(item => item.selected)
			if (data.length) {
				this.$emit('result', data)
			}
			this.hide()
			this.file_list.forEach(item => item.selected = false)
		},
		upload(event) {
			const files = event.target.files
			const formData = new FormData();
			files.forEach(file => {
				formData.append("file[]", file);
			})
			apiUpload(formData).then(() => {
				this.actionFileList()
			});
		},
		//分页
		pageChange(page) {
			this.current = -1
			this.actionFileList(page)
		},
		show() {
			this.display = true
		},
		hide() {
			this.display = false
		},
	},
	computed: {
		...mapState(['files']),
	},
	watch: {
		files: {
			deep: true,
			immediate: true,
			handler(files) {
				if (files) {
					this.pagination = files.pagination
					this.file_list = files.data
				}
			}
		}
	}
}
</script>
<style lang="less">
.c-media {
	position: fixed;
	z-index: 100;
	left: 0;
	top: 0;
	width: 100vw;
	height: 100vh;
	background-color: rgba(0, 0, 0, .8);
	display: flex;
	justify-content: center;
	align-items: center;

	.c-media-box {
		width: 80vw;
		height: 80vh;
		background-color: #FFF;
		padding: 1em;
	}

	.file-list {
		margin-top: 1em;
		max-height: 50vh;
		overflow-y: auto;
		padding-bottom: 1em;
	}

	.file-header {
		width: 100%;
		display: flex;
		justify-content: space-between;

		.el-icon-close {
			font-size: 2em;
			cursor: pointer;

			&:hover {
				color: @link;
			}
		}
	}

	.file-item {
		float: left;
		width: 150px;
		height: 120px;
		text-align: center;
		border: 2px solid @bg;
		position: relative;
		opacity: .8;
		display: flex;
		justify-content: center;
		align-items: center;
		margin:.25em;

		&.current,
		&:hover {
			border-color: @link;
			opacity: 1;
		}

		.file-box {
			max-height: 120px;
			display: flex;
			justify-content: center;
			align-items: center;
		}

		img {
			max-height: 120px;
			height: 100%;
			width: auto;
		}

		.el-icon {
			font-size: 50px;
		}
	}

	.pagination {
		margin-top: 0;
	}

	.bottom {
		margin-top: 1em;
		text-align: center;
	}
}
</style>