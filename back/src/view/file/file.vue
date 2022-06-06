<template>
	<div class="v-file js2-box">
		<div class="file-header">
			<div>
				<input ref="file" type="file" multiple hidden @change="fileChange" />
				<el-button type="primary" @click="$refs['file'].click()">文件上传</el-button>
				<el-button @click="select_mode=!select_mode">{{select_mode?'取消选择':'批量选择'}}</el-button>
				<el-button v-if="select_mode" @click="deleteFiles">批量删除</el-button>
			</div>
			<div class="search-bar">
				<el-input v-model="keywords" @change="search" placeholder="回车搜索"></el-input>
			</div>
		</div>
		<div class="file-list">
			<div class="file-item" v-for="file in file_lsit" :key="file.id">
				<div class="file-box">
					<img v-if="file.type==='image'" :src="base_url+file.name">
					<i v-else :class="'el-icon el-icon-' + (file.type==='video'?'video-camera':file.type==='audio'?'headset':'document')"></i>
					<div class="file-name">{{file.name|fileName}}</div>
				</div>
				<label class="select-checkbox" v-if="select_mode">
					<el-checkbox v-model="file.checked"></el-checkbox>
				</label>
				<span v-if="!select_mode" class="detail-btn" @click="current_id=file.id">详细</span>
			</div>
		</div>
		<!-- 文件预览 -->
		<div class="file-detail" v-if="current_id" @click="current_id=0">
			<div class="js2-box" @click.stop="">
				<i class="el-icon el-icon-circle-close" @click="current_id=0"></i>
				<div class="file-main">
					<div class="file-preview">
						<div class="image-box">
							<img v-if="current_file.type==='image'" :src="base_url+current_file.name" alt="">
						</div>
						<audio v-if="current_file.type==='audio'" :src="base_url+current_file.name" controls></audio>
						<video v-if="current_file.type==='video'" :src="base_url+current_file.name" controls></video>
						<i v-if="current_file.type==='file'" class="el-icon el-icon-document"></i>
					</div>
					<div class="file-info">
						<div class="info-item">
							<span class="title">文件名：</span>
							<div>{{current_file.name|fileName}}</div>
						</div>
						<div class="info-item">
							<span class="title">文件地址：</span>
							<div>{{base_url+current_file.name}}</div>
						</div>
						<div class="info-item">
							<span class="title">文件描述：</span>
							<el-input v-model="current_file.desc"></el-input>
						</div>
						<div class="action-btns">
							<el-button type="primary" @click="updatefile">更新文件描述</el-button>
							<el-button type="danger" @click="deleteFile">删除文件</el-button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="pagination" v-if="pagination.page_total>1">
			<el-pagination layout="prev, pager, next" :current-page="pagination.current_page" :total="pagination.total" :page-count="pagination.page_total" @current-change="pageChange" :page-size="24" hide-on-single-page>
			</el-pagination>
		</div>
	</div>
</template>
<script>
import { apiFileList, apiUpload, apiFileDelete, apiFileUpdate } from '@/request/api.js'
export default {
	name: 'v-file',
	data() {
		return {
			select_mode: false, //批量选择模式
			file_lsit: [],
			pagination: {},
			current_id: 0, //当前选中的文件ID
			current_file: null, //当前选中的文件
			keywords: '',
		}
	},
	filters: {
		fileName(value) {
			return value.replace('/upload/', '')
		},
	},
	methods: {
		fileChange(event) {
			this.keywords = ''
			const files = event.target.files
			const formData = new FormData()
			files.forEach(file => {
				formData.append("file[]", file)
			})
			apiUpload(formData).then(() => {
				this.getFileList()
			})
			event.target.value = ''
		},
		search() {
			this.getFileList()
		},
		updatefile() {
			apiFileUpdate(this.current_file).then(() => {
				this.$message.success('更新成功')
			})
		},
		deleteFile() {
			this.$messageBox.confirm('此操作将永久删除该文件, 是否继续?', '提示', {
				type: 'warning'
			}).then(() => {
				apiFileDelete(this.current_id).then(() => {
					this.file_lsit = this.file_lsit.filter(file => file.id !== this.current_id)
					this.current_id = 0
					this.$message.success('文件删除成功')

				})
			}).catch(() => {})
		},
		deleteFiles() {
			const files = this.file_lsit.filter(item => item.checked)
			const file_ids = files.map(item => item.id)
			this.$messageBox.confirm('此操作将永久删除选中的文件, 是否继续?', '提示', {
				type: 'warning'
			}).then(() => {
				apiFileDelete(String(file_ids)).then(() => {
					this.file_lsit = this.file_lsit.filter(file => file_ids.indexOf(file.id) == -1)
					this.$message.success('文件删除成功')
					this.select_mode = false
				})
			}).catch(() => {})

		},
		//分页
		pageChange(page) {
			this.getFileList(page)
			this.resetTableScrollTop()
		},
		getFileList(page = 1) {
			apiFileList(page, this.keywords).then(res => {
				this.file_lsit = res.data
				this.pagination = res.pagination
			})
		}
	},
	watch: {
		current_id() {
			const file = this.file_lsit.find(file => file.id === this.current_id)
			this.current_file = file
		}
	},
	created() {
		this.getFileList()
	}
}
</script>
<style lang="less">
.v-file {
	.file-list {
		margin-top: 2em;
		height:62vh;
		overflow-y: auto;
	}

	.file-header {
		width: 100%;
		display: flex;
		justify-content: space-between;
	}

	.search-bar {
		display: flex;
		white-space: nowrap;
		align-items: center;

		.el-button {
			margin-left: .5em;
		}
	}

	.file-detail {
		position: fixed;
		z-index: 20;
		left: 0;
		top: 0;
		width: 100vw;
		height: 100vh;
		background-color: rgba(0, 0, 0, .73);
		display: flex;
		justify-content: center;
		align-items: center;

		.el-icon-circle-close {
			position: absolute;
			right: 10px;
			top: 8px;
			font-size: 30px;
			cursor: pointer;

			&:hover {
				color: @danger;
			}
		}

		.file-main {
			display: flex;
			justify-content: space-between;
		}

		.file-preview {
			width: 65%;
			display: flex;
			justify-content: center;
			align-items: center;

			img {
				max-height: 80vh
			}

			video {
				max-width: 100%;
			}

			.el-icon-document {
				font-size: 60px;
			}
		}

		.file-info {
			margin-left: 2em;
			width: 35%;
		}

		.info-item {
			margin-bottom: .5em;

			.title {
				font-weight: bold;
			}
		}

		.action-btns {
			margin-top: 2em;
			display: flex;
			justify-content: space-between;
		}
	}

	.file-item {
		float:left;
		padding: .5em;
		text-align: center;
		border: 1px solid @bg;
		position: relative;
		width:150px;
		height:120px;
		display: flex;
		justify-content: center;
		align-items: center;
		.file-box{

		}

		img {
		}

		.el-icon {
			font-size: 50px;
		}

		&:hover {
			.detail-btn {
				display: flex;
			}
		}

		.detail-btn,
		.select-checkbox {
			width:100%;
			height:100%;
			position: absolute;
			z-index: 10;
			left: 0;
			top: 0;
			cursor: pointer;
			background-color: rgba(0, 0, 0, .73);
			color: #FFF;
			display: none;
			justify-content: center;
			align-items: center;
		}
		.select-checkbox{
			display: flex;
		}

	}

	.file-name {
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		max-width: 80%;
		font-size: .85em;
		margin: 0 auto;
	}

	.pagination {
		text-align: center;
		margin-top: 2em;
	}
}
</style>