<template>
	<div class="v-banner js2-box">
		<el-table :data="banners" height="81vh">
			<el-table-column align="center" label="图片">
				<template slot-scope="scope">
					<img class="banner-image" v-if="scope.row.image_url" :src="base_url+scope.row.image_url" @click="openMedia(scope.$index)" />
					<el-button v-else size="mini" @click="openMedia(scope.$index)">选择图片</el-button>
				</template>
			</el-table-column>
			<el-table-column align="center" label="链接">
				<template slot-scope="scope">
					<el-input v-model="scope.row.link"></el-input>
				</template>
			</el-table-column>
			<el-table-column align="center" label="新窗打开">
				<template slot-scope="scope">
					<el-switch v-model="scope.row.blank" :active-value="1" :inactive-value="0"></el-switch>
				</template>
			</el-table-column>
			<el-table-column align="center" label="排序">
				<template slot-scope="scope">
					<el-input type="number" v-model.number="scope.row.sort"></el-input>
				</template>
			</el-table-column>
			<el-table-column align="center">
				<template slot="header">
					<c-permission permission="banner-add">
						<el-button @click="addBanner">添加轮播图</el-button>
					</c-permission>
				</template>
				<template slot-scope="scope">
					<c-permission permission="banner-update">
						<el-button type="primary" @click="updateBanner(scope.row)">更新</el-button>
					</c-permission>
					<c-permission permission="banner-delete">
						<el-button type="danger" @click="deleteBanner(scope.row.id)">删除</el-button>
					</c-permission>
				</template>
			</el-table-column>
		</el-table>
		<c-media ref="media" @result="mediaResult" />
	</div>
</template>
<script>
import media from "@/components/media/media.vue";
import { apiBannerList, apiBannerAdd, apiBannerUpdate, apiBannerDelete } from '@/request/api.js'
export default {
	name: 'v-banner',
	data() {
		return {
			banners: [],
			index: -1, //当前正在设置图片的轮播图序号
		}
	},
	methods: {
		openMedia(index) {
			this.index = index
			this.$refs.media.show()
		},
		mediaResult(data) {
			(data[0].type != 'image') ?
			this.$message.error('请选择图片类型文件'):
				this.banners[this.index].image_url = data[0].name
		},
		deleteBanner(id) {
			this.$messageBox
				.confirm('是否要删除该轮播图', '提示', { type: 'warning' })
				.then(() => {
					apiBannerDelete(id).then(() => {
						const index = this.banners.findIndex(banner => banner.id == id)
						this.banners.splice(index, 1)
						this.$message.success('删除成功')
					})
				})
				.catch(() => {});

		},
		updateBanner(banner) {
			apiBannerUpdate(banner).then(() => {
				this.$message.success('更新成功')
			})
		},
		getBanners() {
			apiBannerList().then(res => {
				this.banners = res.data
			})
		},
		addBanner() {
			apiBannerAdd().then(res => {
				this.banners.unshift(res.data)
			})
		},
	},
	created() {
		this.getBanners()
	},
	components: {
		'c-media': media,
	}
}
</script>
<style lang="less">
.v-banner {
	.el-table {
		td {
			padding: 2em 0;
		}
	}

	.banner-image {
		max-width: 50%;
		margin-right: 1em;
	}
}
</style>