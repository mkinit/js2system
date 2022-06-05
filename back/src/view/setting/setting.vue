<template>
	<div class="v-setting js2-box" v-if="setting_info">
		<el-form label-width="9em" :model="setting_info" :rules="rules" ref="form">
			<el-form-item label="网站logo：" class="logo">
				<img :src="base_url+setting_info.logo" />
				<el-button @click="$refs.media.show()">选择图片</el-button>
			</el-form-item>
			<el-form-item label="网站域名：">
				<el-input v-model="setting_info.site"></el-input>
				<span class="desc">（前台地址，不用加https协议）</span>
			</el-form-item>
			<el-form-item label="网站名称：">
				<el-input v-model="setting_info.site_name"></el-input>
			</el-form-item>
			<el-form-item label="网站标题：">
				<el-input v-model="setting_info.site_title"></el-input>
				<span class="desc">（SEO）</span>
			</el-form-item>
			<el-form-item label="网站关键词：">
				<el-input type="text" v-model="setting_info.site_keywords"></el-input>
				<span class="desc">（SEO，英文逗号分隔）</span>
			</el-form-item>
			<el-form-item label="网站描述：">
				<el-input type="textarea" :rows="3" v-model="setting_info.site_description"></el-input>
				<span class="desc">（SEO）</span>
			</el-form-item>
			<el-form-item label="备案号：">
				<el-input type="text" v-model="setting_info.icp"></el-input>
			</el-form-item>
			<el-form-item label="公网安备：">
				<el-input type="text" v-model="setting_info.police"></el-input>
			</el-form-item>
			<el-form-item label="缩略图（小）：" prop="thumbnail_small">
				<el-input type="text" v-model="setting_info.thumbnail_small"></el-input>
			</el-form-item>
			<el-form-item label="缩略图（中）：" prop="thumbnail_medium">
				<el-input type="text" v-model="setting_info.thumbnail_medium"></el-input>
			</el-form-item>
			<el-form-item label="缩略图（大）：" prop="thumbnail_large">
				<el-input type="text" v-model="setting_info.thumbnail_large"></el-input>
			</el-form-item>
			<el-form-item label="上传限制：" prop="upload_limit">
				<el-input type="text" v-model="setting_info.upload_limit"></el-input>
				<span class="desc">（M）</span>
			</el-form-item>
			<el-form-item label="百度统计ID：">
				<el-input type="text" v-model="setting_info.ba"></el-input>
			</el-form-item>
			<el-form-item label="产品栏目：">
				<el-select v-if="categories&&categories.length" v-model="setting_info.product" placeholder="请选择分类">
					<el-option v-for="category in categories" :key="category.id" :label="category.name" :value="category.id">
					</el-option>
				</el-select>
				<router-link v-else to="/category">请先创建分类</router-link>
			</el-form-item>
			<el-form-item label="新闻栏目：">
				<el-select v-if="categories&&categories.length" v-model="setting_info.news" placeholder="请选择分类">
					<el-option v-for="category in categories" :key="category.id" :label="category.name" :value="category.id">
					</el-option>
				</el-select>
				<router-link v-else to="/category">请先创建分类</router-link>
			</el-form-item>
			<el-form-item label="网站主题：">
				<el-select v-model="setting_info.theme" placeholder="请选择主题">
					<el-option v-for="theme in setting_info.theme_dir" :key="theme" :label="theme" :value="theme">
					</el-option>
				</el-select>
			</el-form-item>
		</el-form>
		<div class="footer js2-text-center">
			<c-permission permission="setting-update">
				<el-button size="middle" type="primary" @click="putSetting('form')">更新设置</el-button>
			</c-permission>
		</div>
		<c-media ref="media" @result="mediaResult" />
	</div>
</template>
<script>
import media from "@/components/media/media.vue";
import {
	apiSetting,
	apiSettingUpdate, //更新设置
	apiCategoryList, //分类列表
} from "@/request/api.js";
export default {
	name: "v-setting",
	data() {
		return {
			setting_info: null,
			categories: [{
				name: '无',
				id: ''
			}],
			rules: {
				thumbnail_small: [{
					required: true,
					message: '缩略图尺寸不能为空',
					trigger: 'blur'
				}],
				thumbnail_medium: [{
					required: true,
					message: '缩略图尺寸不能为空',
					trigger: 'blur'
				}],
				thumbnail_large: [{
					required: true,
					message: '缩略图尺寸不能为空',
					trigger: 'blur'
				}],
				upload_limit: [{
					required: true,
					message: '限制上传大小不能为空',
					trigger: 'blur'
				}],
			},
		}
	},
	methods: {
		mediaResult(data) {
			(data[0].type != 'image') ?
			this.$message.error('请选择图片类型文件'):
				this.setting_info.logo = data[0].name
		},
		putSetting(form) {
			this.$refs[form].validate(res => {
				if (res) {
					apiSettingUpdate(this.setting_info).then(() => {
						this.$message.success("更新成功")
					});
				}
			})

		},
	},
	components: {
		'c-media': media,
	},
	created() {
		apiCategoryList().then(res => {
			this.categories.push(...res.data)
		})
		apiSetting().then(res => {
			this.setting_info = res.data;
		});
	},
};
</script>
<style lang="less">
.v-setting {

	.el-input,
	.el-textarea {
		width: 320px;
	}

	.desc {
		line-height: 32px;
		padding: 0 .5em;
	}

	.logo {
		img {
			width: 36px;
			margin: 0 .5em;
		}
	}

	.footer {
		margin-top: 3em;
	}
}
</style>