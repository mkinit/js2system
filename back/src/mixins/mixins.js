import config from '@/config.js'
import { mapState, mapMutations } from 'vuex'
export default {
	data() {
		return {
			...config
		}
	},
	methods: {
		...mapMutations(['update']),
		resetTableScrollTop() { //表格滚动到顶
			document.querySelector('.el-table__body-wrapper').scrollTop = 0
		}
	},
	computed: {
		...mapState(['user']),
	}
}