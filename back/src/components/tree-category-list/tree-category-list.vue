<template>
	<div class="c-tree-category-list">
		<div class="cate-tree" v-for="cate in categories" :key="cate.id">
			<div class="cate-items">
				<div>
					<el-input class="cate-name" v-model="cate.name" placeholder="分类名称"></el-input>
				</div>
				<div>
					{{cate.id}}
				</div>
				<div>
					<el-input class="cate-keywords" v-model="cate.keywords" placeholder="分类关键词"></el-input>
				</div>
				<div>
					<el-input class="cate-description" v-model="cate.description" placeholder="分类描述"></el-input>
				</div>
				<div class="cate-cover">
					<img v-if="cate.cover" :src="base_url+cate.cover">
					<el-button @click="openMedia(cate.id)">封面</el-button>
				</div>
				<div>
					<el-button type="primary" @click="updateItem(cate)">更新</el-button>
				</div>
				<div>
					<el-button type="danger" @click="deleteItem(cate)">删除</el-button>
				</div>
			</div>
			<template v-if="cate.children&&cate.children.length">
				<c-tree-category-list :categories="cate.children" @updateItem="updateItem" @deleteItem="deleteItem" />
			</template>
		</div>
	</div>
</template>
<script>
//分类列表树
export default {
	name: 'c-tree-category-list',
	props: ['categories'],
	methods: {
		openMedia(cate_id){
			this.$emit('openMedia', cate_id)
		},
		updateItem(cate) {
			this.$emit('updateItem', cate)
		},
		deleteItem(cate) {
			this.$emit('deleteItem', cate)
		}
	}
}
</script>
<style lang="less">
.c-tree-category-list {
	.cate-cover {
		display: flex;
		justify-content: center;
		align-items: center;

		img {
			max-width: 60%;
			max-height:20px;
			margin-right:.5em;
		}
	}

	.cate-tree {
		.c-tree-category-list {
			.cate-tree {
				.cate-items {
					&>div:first-child {
						display: flex;
						align-items: center;

						&::before {
							content: "—"
						}
					}
				}

				.c-tree-category-list {
					.cate-tree {
						.cate-items {
							&>div:first-child {
								&::before {
									content: "——"
								}
							}
						}
					}
				}
			}
		}
	}
}
</style>