<template>
	<div class="v-post js2-box">
		<div class="filters page-head">
			<div>
				<c-permission permission="post-add">
					<el-button @click="$router.push({name:'post-edit',query:{type}})">
						新建{{type=='post'?'文章':'页面'}}
					</el-button>
				</c-permission>
				<el-button :type="!is_trashed?'primary':''" @click="is_trashed=0">已发布</el-button>
				<el-button :type="is_trashed?'primary':''" @click="is_trashed=1">回收站</el-button>
				<span class="category-selector" v-if="type=='post'">
					选择分类：
					<el-select @change="categoryChange" v-model="category_id" filterable placeholder="请选择">
						<el-option :key="0" label="全部" :value="0"></el-option>
						<el-option v-for="item in category_options" :key="item.id" :label="item.name" :value="item.id">
						</el-option>
					</el-select>
				</span>
			</div>
			<div class="search-box">
				<el-input v-model="keywords"></el-input>
				<el-button class="search-btn" type="primary" @click="search">搜索内容</el-button>
			</div>
		</div>
		<el-table :data="posts" height="66vh">
			<el-table-column align="center" label="标题" prop="title"></el-table-column>
			<el-table-column align="center" label="作者">
				<template slot-scope="scope">
					{{scope.row.author.nickname}}
				</template>
			</el-table-column>
			<el-table-column align="center" label="分类目录" v-if="type=='post'">
				<template slot-scope="scope">
					{{scope.row.category?scope.row.category.name:'全部'}}
				</template>
			</el-table-column>
			<el-table-column align="center" label="发布日期" prop="time_add"></el-table-column>
			<el-table-column align="center" label="置顶" prop="top">
				<template slot-scope="scope">
					<el-switch v-model="scope.row.top" :active-value="1" :inactive-value="0" @change="postTopChange($event,scope.row.id)"></el-switch>
				</template>
			</el-table-column>
			<el-table-column align="center" label="操作">
				<template slot-scope="scope">
					<template v-if="is_trashed">
						<c-permission permission="post-restore">
							<el-button type="primary" size="mini" @click="restorePost(scope.row.id)">还原</el-button>
						</c-permission>
						<c-permission permission="post-delete">
							<el-button type="danger" size="mini" @click="delPost(scope.row.id,1)">永久删除</el-button>
						</c-permission>
					</template>
					<template v-else>
						<c-permission permission="post-update">
							<el-button type="primary" size="mini" @click="editPost(scope.row.id)">编辑</el-button>
						</c-permission>
						<c-permission permission="post-delete">
							<el-button type="danger" size="mini" @click="delPost(scope.row.id)">删除</el-button>
						</c-permission>
					</template>
				</template>
			</el-table-column>
		</el-table>
		<div class="pagination" v-if="pagination.page_total>1">
			<el-pagination layout="prev, pager, next" :current-page="pagination.current_page" :total="pagination.total" :page-count="pagination.page_total" @current-change="pageChange" hide-on-single-page>
			</el-pagination>
		</div>
	</div>
</template>
<script>
import {
	apiPostsList,
	apiPostSearch,
	apiPostDelete,
	apiPostRestore,
	apiPostSetTop,
	apiCategoryList, //获取分类列表
} from '@/request/api.js'
import { recursion } from '@/common/tools.js'
export default {
	name: 'v-post',
	data() {
		return {
			type: 'post', //内容类型：post、single
			is_trashed: 0, //是否为回收站
			keywords: '',
			posts: [],
			pagination: {},
			category_id: null, //当前过滤分类ID
			categories: [], //分类
		}
	},
	methods: {

		categoryChange() {
			this.keywords = ''
			this.getPosts()
		},

		//内容置顶
		postTopChange(status, id) {
			apiPostSetTop(id, status).then(() => {
				this.$message.success('更新成功')
			})
		},

		//还原内容
		restorePost(id) {
			this.$messageBox('是否要恢复内容？', '提示', {
				type: 'warning'
			}).then(() => {
				apiPostRestore(id).then(() => {
					this.posts.splice(this.posts.findIndex(post => post.id === id), 1)
					this.$message.success('内容已恢复')
				})
			}).catch(() => {})

		},

		//编辑内容
		editPost(id) {
			this.$router.push(`/post/edit?type=${this.type}&id=${id}`)
		},

		//删除内容
		delPost(id, force = 0) {
			const msg = force ? '删除后内容将会彻底消失, 是否继续?' : '删除后内容将会被放入回收站, 是否继续?'
			this.$messageBox(msg, '提示', {
				type: 'warning'
			}).then(() => {
				apiPostDelete(id, force).then(() => {
					this.posts.splice(this.posts.findIndex(post => post.id === id), 1)
					this.$message.success('删除成功')
				})
			}).catch(() => {})

		},

		//分页
		pageChange(page) {
			if (this.keywords) {
				this.searchPosts(page)
			} else {
				this.getPosts(page)
			}
			this.resetTableScrollTop()
		},

		//搜索
		search() {
			//如果有关键词使用搜索模式
			if (this.keywords) {
				this.category_id = null
				this.searchPosts()
			} else {
				//没有关键词使用普通模式获取最新内容列表
				this.getPosts()
			}
		},

		//获取搜索内容列表
		searchPosts(page = 1) {
			apiPostSearch(this.keywords, this.type, this.is_trashed ? 1 : 0, page).then(res => {
				this.postsHandle(res)
			})
		},

		//获取内容列表
		getPosts(page = 1) {
			apiPostsList(page, this.type, this.category_id, this.is_trashed).then(res => {
				this.postsHandle(res)
			})
		},

		//统一处理数据
		postsHandle(res) {
			this.pagination = res.pagination
			this.posts = res.data
		}
	},
	created() {
		const { query } = this.$route
		this.type = query.type ? query.type : 'post'
		this.getPosts()
		//获取分类列表
		apiCategoryList().then(res => {
			this.categories = res.data
		})
	},
	computed: {
		category_options() {
			const arr = []
			recursion(this.categories, 'children', (category) => {
				arr.push(category)
			})
			return arr
		}
	},
	watch: {
		$route(to, from) {
			this.posts = []
			this.type = to.query.type
			if (from.name == 'post-edit' && from.query.id) {
				//从编辑页返回加载当前分页数据
				this.getPosts(this.pagination.current_page)
			} else {
				this.getPosts()
			}
		},

		is_trashed() {
			if (this.keywords) {
				this.searchPosts()
			} else {
				this.getPosts()
			}
		}
	}
};
</script>
<style lang="less">
.v-post {
	.filters {
		display: flex;
		justify-content: space-between;

		.category-selector {
			margin-left: 10px;
		}

		.search-box {
			display: flex;
		}

		.search-btn {
			margin-left: .5em;
		}
	}
}
</style>