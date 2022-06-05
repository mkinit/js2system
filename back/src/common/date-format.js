function _getDate(param) { //私有获取时间方法
	if (param == undefined) { //没有构造参数时返回空值
		return this.private_date = ''
	}

	if (param instanceof Date) {
		return this.private_date = param
	}

	if (typeof param == 'string') {
		if (new Date(param) == 'Invalid Date') { //如果时间字符串无法new出时间，返回错误信息
			return this.private_date = 'error'
		} else {
			return this.private_date = new Date(param)
		}
	}

	if (typeof param == 'number' && /\d{10,13}/.test(param)) {
		if (!/\d{13}/.test(param)) { //如果不是13位数字，需要乘以1000变成毫秒
			param *= 1000
		}
		return this.private_date = new Date(param)
	} else {
		return this.private_date = 'error'
	}
}

class TimeStamp {
	constructor(param) { //接收构造参数
		this.param = param
		this.private_date = ''
	}

	getDate() { //获取时间方法
		_getDate.call(this, this.param)
	}

	format(format_string) { //格式化时间
		if (format_string == undefined) return '请传入格式参数'
		this.getDate() //获取时间
		if (this.private_date == '') return '必须传递时间戳或时间字符串作为构造参数'
		if (this.private_date == 'error') return '请传入10或13位时间戳或正确的时间字符串作为构造参数'

		const date = this.private_date //通过私有方法获取的时间
		const year = date.getFullYear() //年
		const month = date.getMonth() + 1 //月
		const day = date.getDate() //日
		const week = date.getDay() //星期
		const week_cn = '星期' + ['日','一','二','三','四','五','六'][week]
		const hour = date.getHours() //时
		const minute = date.getMinutes() //分
		const second = date.getSeconds() //秒
		let format_date
		if (typeof format_string === 'string') { //格式参数必须是字符串
			format_date = format_string
			if (/[Y]{4}/.test(format_date)) { //年
				format_date = format_date.replace(/[Y]{4}/, year)
			}

			if (/(week)/.test(format_date)) {
				format_date = format_date.replace(/(week)/, week_cn)
			}

			[
				[/[M]{2}/, /[M]/, month],
				[/[D]{2}/, /[D]/, day],
				[/[h]{2}/, /[h]/, hour],
				[/[m]{2}/, /[m]/, minute],
				[/[s]{2}/, /[s]/, second]
			].forEach(item => {
				if (eval(item[0]).test(format_date)) { //月
					format_date = format_date.replace(eval(item[0]), eval(item[2]) < 10 ? '0' + eval(item[2]) : eval(item[2]))
				} else {
					format_date = format_date.replace(eval(item[1]), eval(item[2]))
				}
			})
		} else {
			return '请传入正确的格式化字符串！'
		}
		return format_date
	}
}

export default TimeStamp