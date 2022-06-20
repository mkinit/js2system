<template>
	<div class="v-post-edit js2-box" v-if="post">
		<div class="post-main">
			<h3 class="js2-text-center">{{is_edit?'编辑':'发布'}}{{post.type=='single'?'页面':'文章'}}</h3>
			<div class="post-title">
				<el-input size="medium" placeholder="添加标题" v-model="post.title"></el-input>
			</div>
			<tinymce ref="editor" v-model="post.content" :init="editor_init" :tinymce-script-src="tinymce_script_src" />
			<div class="custom-meta">
				<el-button @click="metaAdd">添加自定义属性</el-button>
				<div class="meta-list">
					<div class="item" v-for="(meta,index) in post.meta" :key="meta.id">
						<i class="el-icon-sort"></i>
						<el-autocomplete class="meta-key" v-model="meta.key" :fetch-suggestions="queryMetaKey" placeholder="请输入内容"></el-autocomplete>
						<el-input class="meta-value" v-model="meta.value" placeholder="属性值"></el-input>
						<el-button type="danger" size="mini" @click="metaDelete(index)">删除</el-button>
					</div>
				</div>
			</div>
		</div>
		<div class="post-action js2-box">
			<div class="section-block">
				<h3>发布时间</h3>
				<el-date-picker class="date-input" value-format="yyyy-MM-dd HH:mm:ss" align="right" v-model="post.time_add" type="datetime" placeholder="选择日期时间">
				</el-date-picker>
			</div>
			<div class="section-block category-box" v-if="post.type=='post'">
				<h3>选择分类</h3>
				<el-input class="cate-filter-text" placeholder="搜索分类" v-model="cate_filter_text">
				</el-input>
				<el-tree @check="categoryTreeCheck" class="category-tree" :filter-node-method="cateFilter" ref="cate-tree" :data="categories" node-key="id" :props="{label:'name'}" :expand-on-click-node="false" :default-expand-all="true" :check-on-click-node="true" :accordion="false" :show-checkbox="true" :check-strictly="true">
				</el-tree>
				<el-button type="text" @click="dialog_category=true">添加新分类</el-button>
			</div>
			<div class="section-block thumbnail-box">
				<h3>缩略图</h3>
				<div class="thumb">
					<img v-if="post.thumbnail" :src="base_url+post.thumbnail" />
					<el-button type="danger" icon="el-icon-delete" circle @click="post.thumbnail=''"></el-button>
				</div>
				<el-button class="thumb-upload-btn" @click="openMedia('thumb')">选择缩略图</el-button>
			</div>
			<div class="section-block cover-box" v-if="post.type!='post'">
				<h3>顶部封面图</h3>
				<div class="thumb">
					<img v-if="post.cover" :src="base_url+post.cover" />
					<el-button type="danger" icon="el-icon-delete" circle @click="post.cover=''"></el-button>
				</div>
				<el-button class="thumb-upload-btn" @click="openMedia('cover')">选择封面图</el-button>
			</div>
			<div class="section-block" v-if="post.type!='single'">
				<h3>内容标签</h3>
				<el-tag :key="tag" v-for="tag in post.tags" closable :disable-transitions="false" @close="tagDelete(tag)">
					{{tag}}
				</el-tag>
				<el-input class="input-new-tag" v-if="tag_input_visible" v-model="tag_input" ref="tag_input" size="small" @keyup.enter.native="tagInputConfirm" @blur="tagInputConfirm" />
				<el-button v-else class="button-new-tag" size="small" @click="showTagInput">+添加标签</el-button>
			</div>
			<div class="section-block">
				<el-switch v-model="post.top" inactive-text="置顶内容" :active-value="1" :inactive-value="0"></el-switch>
			</div>
			<div class="section-block">
				<el-button size="middle" type="primary" @click="addPost()">
					{{is_edit?'更新':'发布'}}{{post.type=='single'?'页面':'文章'}}
				</el-button>
			</div>
		</div>
		<!-- 添加新分类窗口 -->
		<el-dialog title="创建分类" :visible.sync="dialog_category" width="30%" center>
			<div class="category-add">
				<el-cascader :options="categories" :props="{expandTrigger:'hover', checkStrictly: false, value: 'id', label: 'name' }" v-model="category_option" placeholder="选择父级（不选为一级）" clearable></el-cascader>
				<el-input placeholder="分类名称" v-model="category_name"></el-input>
			</div>
			<div slot="footer">
				<el-button type="primary" @click="addCategory">添加分类</el-button>
			</div>
		</el-dialog>
		<c-media ref="media" @result="mediaResult"></c-media>
	</div>
</template>
<script>
import tinymce from "@tinymce/tinymce-vue"
import dateFormat from '@/common/date-format.js'
import media from '@/components/media/media.vue'
import Sortable from 'sortablejs';
import {
	apiCategoryList, //获取分类列表
	apiCategoryAdd, //添加分类
	apiPostAdd, //添加内容
	apiPost, //获取内容
	apiPostUpdate, //更新内容
	apiMeteKey, //自定义字段键列表
} from "@/request/api.js";
const post_data = {
	type: 'post',
	title: '',
	content: '',
	thumbnail: '',
	meta: [], //自定义字段
	tags: [], //标签
	category_id: '', //当前选择的内容分类ID
	time_add: new dateFormat(new Date()).format('YYYY-MM-DD hh:mm:ss'), //发布时间
	top: 0, //置顶
}
export default {
	name: "v-post-edit",
	data() {
		return {
			dialog_category: false, //创建新分类窗口
			category_name: '', //分类名
			category_option: [], //已选择分类ID数组
			meta_keys: [], //自定义字段键列表
			tag_input_visible: false,
			tag_input: '',
			cate_filter_text: '', //分类搜索
			editor: null, //当前编辑器实例
			action: '', //上传动作：添加缩略图（thumb）或详情（detail）
			post: null,
			is_edit: false, //当前模式是否为编辑内容
			categories: [], //分类
			tinymce_script_src: "tinymce/tinymce.min.js",
			editor_init: {
				extended_valid_elements: 'script,style', //允许使用标签
				custom_elements: 'script,style', //允许使用标签
				height: 560,
				language: "zh_CN",
				language_url: "tinymce/langs/zh_CN.js",
				theme: "silver",
				theme_url: "tinymce/themes/silver/theme.min.js",
				base_url: "tinymce",
				suffix: ".min",
				plugins: "table code link charmap lists hr wordcount preview help codesample autosave",
				autosave_prefix: 'post-',
				autosave_interval: '10s',
				autosave_restore_when_empty: true,
				menubar: "",
				toolbar: [
					"code | undo redo removeformat | subscript superscript | table numlist bullist | alignleft aligncenter alignright alignjustify alignnone | outdent indent | help codesample", "fontsizeselect | formatselect | bold italic hr forecolor backcolor charmap blockquote | link unlink | multimedia-custom"
				],
				content_style: 'img{max-width:100%;height:auto;object-fit:scale-down;}', //自定义样式
				setup: editor => { //设置选项
					// 注册多图图标
					editor.ui.registry.addIcon(
						"icon-multimedia-custom",
						`<svg t="1645890977189" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="8630" width="30" height="30"><path d="M296.96 645.12V317.44c-33.792 0-61.44 27.648-61.44 61.44v389.12c0 33.792 27.648 61.44 61.44 61.44h307.2c33.792 0 61.44-27.648 61.44-61.44H419.84c-67.584 0-122.88 0-122.88-122.88z m471.04-266.24H665.6c-33.792 0-61.44-27.648-61.44-61.44V215.04c0-11.264-9.216-20.48-20.48-20.48H419.84c-33.792 0-61.44 27.648-61.44 61.44v389.12c0 33.792 27.648 61.44 61.44 61.44h307.2c33.792 0 61.44-27.648 61.44-61.44V399.36c0-11.264-9.216-20.48-20.48-20.48z m16.384-86.016l-94.208-94.208c-3.072-3.072-6.144-4.096-10.24-4.096-8.192 0-14.336 6.144-14.336 14.336V276.48c0 22.528 18.432 40.96 40.96 40.96h67.584c8.192 0 14.336-6.144 14.336-14.336 0-4.096-1.024-7.168-4.096-10.24z" p-id="8631" fill="#222f3e"></path></svg>`
					)
					// 注册多图上传按钮
					editor.ui.registry.addButton("multimedia-custom", {
						tooltip: '插入附件/媒体文件',
						icon: `icon-multimedia-custom`,
						onAction: () => {
							this.editor = editor
							this.action = 'detail'
							this.$refs.media.show()
						}
					})
				}
			},
		};
	},
	methods: {
		queryMetaKey(keyword, cb) {
			var meta_keys = this.meta_keys;
			var results = keyword ? meta_keys.filter(key => {
				return key.value.toLowerCase().indexOf(keyword.toLowerCase()) === 0
			}) : meta_keys;
			cb(results);
		},
		//添加分类
		addCategory() {
			const { category_name, category_option } = this
			const category = {
				name: category_name,
				parent_id: category_option[category_option.length - 1]
			}
			apiCategoryAdd(category).then(() => {
				this.getCategories()
				this.category_name = ''
				this.category_option = []
				this.dialog_category = false
				this.$message.success('添加成功')
			})
		},
		//分类树过滤
		cateFilter(value, data) {
			if (!value) return true;
			return data.name.indexOf(value) !== -1;
		},

		//删除标签
		tagDelete(tag) {
			this.post.tags.splice(this.post.tags.indexOf(tag), 1);
		},

		//显示标签输入框
		showTagInput() {
			this.tag_input_visible = true;
			this.$nextTick(() => {
				this.$refs.tag_input.$refs.input.focus();
			});
		},

		//添加标签确认
		tagInputConfirm() {
			let tag_input = this.tag_input;
			if (tag_input) {
				this.post.tags.push(tag_input);
			}
			this.tag_input_visible = false;
			this.tag_input = '';
		},

		//媒体库返回结果
		mediaResult(data) {
			switch (this.action) {
				case 'thumb':
					(data[0].type != 'image') ?
					this.$message.error('请选择图片类型文件'):
						this.post.thumbnail = data[0].thumbnail_small
					break
				case 'cover':
					(data[0].type != 'image') ?
					this.$message.error('请选择图片类型文件'):
						this.post.cover = data[0].name
					break
				case 'detail':
					data.forEach(item => {
						let html = ''
						switch (item.type) {
							case 'image':
								html += `<img data-origin="${this.base_url+item.name}" src="${this.base_url+item.thumbnail_large}" alt="${item.name}" />`
								break
							case 'audio':
								html += `<p><audio src="${this.base_url+item.name}" controls /></p>`
								break
							case 'video':
								html += `<p><video src="${this.base_url+item.name}" controls /></p>`
								break
							case 'file':
								html += `<p><a href="${this.base_url+item.name}" target="_blank">${this.base_url+item.name}</a></p>`
								break
						}
						this.editor.insertContent(html)

					})
					break
			}
		},

		//打开媒体库
		openMedia(type) {
			this.action = type
			this.$refs.media.show()
		},

		//添加自定义属性
		metaAdd() {
			this.post.meta.unshift({
				key: '',
				value: ''
			})
			Sortable.create(document.querySelector('.meta-list'))
		},

		//删除自定义属性
		metaDelete(index) {
			this.$messageBox('确认删除自定义属性', '提示', {
				type: 'warning'
			}).then(() => {
				this.post.meta.splice(index, 1)
				this.$message.success('删除成功')
			}).catch(() => {})
		},
		categoryTreeCheck(node) {
			this.$refs['cate-tree'].setCheckedKeys([])
			this.$refs['cate-tree'].setCheckedKeys([node.id])
		},

		//页面初始化
		init() {
			const { query } = this.$route

			if (query.id) { //有ID是编辑页面
				this.is_edit = true
				apiPost(query.id).then(res => {

					//设置标签
					const tags = []
					res.data.tags.forEach(item => {
						tags.push(item.name)
					})
					res.data.tags = tags
					this.post = res.data
					if (res.data.type == 'post') {
						//选择对应的分类树
						this.$nextTick(() => {
							this.$refs['cate-tree'].setCheckedKeys([res.data.category.id])
						})
					}

				})
			} else {
				this.is_edit = false
				const post = JSON.parse(JSON.stringify(post_data))
				post.type = query.type ? query.type : 'post'
				this.post = post

				if (this.post.type == 'post') {
					//清空分类树的选择
					this.$nextTick(() => {
						this.$refs['cate-tree'].setCheckedKeys([])
					})
				}
			}
			this.$forceUpdate()
		},

		//添加内容
		addPost() {
			if (this.post.title === '') {
				this.$message.warning('请输入内容标题')
				return
			}
			const post = JSON.parse(JSON.stringify(this.post))

			//获取选中的分类
			if (this.post.type == 'post') {
				post.category_id = this.$refs['cate-tree'].getCheckedKeys()[0]
			}
			//删除空字段
			post.meta.forEach((meta, index) => {
				if (!meta.key && !meta.value) {
					post.meta.splice(index, 1)
				}
			})

			if (post.id) {
				apiPostUpdate(post).then(() => {
					this.$message.success('更新成功')
					this.$router.back()
				})
			} else {
				apiPostAdd(post).then(() => {
					this.$message.success('发布成功')
					this.$router.back()
				})
			}

		},
		getCategories() {
			//获取分类列表
			apiCategoryList().then(res => {
				this.categories = res.data
			})
		}

	},
	components: {
		tinymce,
		'c-media': media
	},
	created() {
		this.getCategories()
	},
	mounted() {
		this.init()
		apiMeteKey().then(res => {
			const meta_keys = []
			res.data.forEach(item => {
				meta_keys.push({
					value: item
				})
			})
			this.meta_keys = meta_keys
		})
	},
	watch: {
		$route() {
			this.init()
		},
		cate_filter_text(val) {
			this.$refs['cate-tree'].filter(val);
		}
	}
};
</script>
<style lang="less">
.v-post-edit {
	display: flex;

	.date-input {
		max-width: 100%;

		.el-input__inner {
			font-size: 1em;
		}
	}

	.el-tag {
		margin-bottom: 10px;
		margin-right: 10px;
	}

	.button-new-tag,
	.input-new-tag input {
		width: 6em;
		height: 26px;
		line-height: 24px;
		padding: 0 5px;
	}

	.tox .tox-editor-header {
		z-index: 0;
	}

	.el-tree .el-tree-node__label {
		font-size: 1.125em;
	}

	.tox .tox-tbtn--bespoke .tox-tbtn__select-label {
		width: 5em;
	}

	.post-main {
		width: 100%;
	}

	.post-title {
		margin: 1em 0;
	}

	.custom-meta {
		margin-top: 1em;

		.meta-list {
			.item {
				display: flex;
				justify-content: space-between;
				margin: 1em 0;

				.meta-key {
					width: 30%;
				}

				.meta-value {
					width: 60%;
				}

				.el-icon-sort{
					display: flex;
					justify-content: center;
					align-items: center;
					font-size:1.25em;
					margin-right:.5em;
					cursor:move;
				}
			}
		}
	}

	.post-action {
		min-width: 280px;
		max-width: 280px;
		margin-left: 30px;
		text-align: center;
		border: 1px solid @border;
	}

	.section-block {
		margin-bottom: 2em;

		h3 {
			margin-bottom: .5em;
		}
	}

	.cate-filter-text {
		input {
			font-size: 1em;
		}
	}

	.category-tree {
		max-height: 200px;
		overflow-y: auto;
		border: 1px solid @border;
		font-size: .875em;
		border-top:none;
		border-radius: 4px;
	}

	.category-add {
		display: flex;
		.el-cascader{
			line-height:28px;
			margin-right:1em;
		}
	}

	.thumb {
		position: relative;

		img {
			margin-bottom: 1em;
		}

		&:hover {
			.el-button--danger {
				display: block;
			}
		}

		.el-button--danger {
			display: none;
			position: absolute;
			left: 50%;
			top: 50%;
			transform: translate(-50%, -50%);
			z-index: 10;
		}
	}

	.tox-tinymce{
		border-radius:4px;
	}
}
</style>