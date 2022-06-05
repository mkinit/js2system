<template>
	<div class="v-setting js2-box" v-if="company_info">
		<el-form label-width="9em" :model="company_info">
		<el-form-item label="公司名称：">
			<el-input v-model="company_info.name"></el-input>
		</el-form-item>
		<el-form-item label="营业执照代码：">
			<el-input v-model="company_info.company_code"></el-input>
		</el-form-item>
		<el-form-item label="电话号码：">
			<el-input v-model="company_info.tel"></el-input>
		</el-form-item>
		<el-form-item label="传真号码：">
			<el-input v-model="company_info.fax"></el-input>
		</el-form-item>
		<el-form-item label="公司地址：">
			<el-input type="textarea" v-model="company_info.address"></el-input>
		</el-form-item>
		<el-form-item label="邮政编码：">
			<el-input v-model="company_info.zip"></el-input>
		</el-form-item>
		<el-form-item label="电子邮箱：">
			<el-input v-model="company_info.email"></el-input>
		</el-form-item>
		<el-form-item label="手机号码：">
			<el-input v-model="company_info.phone"></el-input>
		</el-form-item>
		<el-form-item label="联系微信：" class="logo">
			<img :src="base_url+company_info.wechat" />
			<el-button @click="$refs.media.show()">选择图片</el-button>
		</el-form-item>
		<el-form-item label="联系QQ：">
			<el-input v-model="company_info.qq"></el-input>
		</el-form-item>
	</el-form>
		<div class="footer js2-text-center">
			<c-permission permission="company-update">
				<el-button size="middle" type="primary" @click="putCompany">更新设置</el-button>
			</c-permission>
		</div>
		<c-media ref="media" @result="mediaResult" />
	</div>
</template>
<script>
import media from "@/components/media/media.vue";
import {
	apiCompany,
	apiCompanyUpdate, //更新设置
} from "@/request/api.js";
export default {
	name: "v-company",
	data() {
		return {
			company_info: null,
		};
	},
	methods: {
		mediaResult(data) {
			(data[0].type != 'image') ?
			this.$message.error('请选择图片类型文件'):
				this.company_info.wechat = data[0].name
		},
		putCompany() {
			apiCompanyUpdate(this.company_info).then(() => {
				this.$message.success("更新成功");
			});
		}
	},
	components: {
		'c-media': media,
	},
	created() {
		apiCompany().then((res) => {
			this.company_info = res.data;
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