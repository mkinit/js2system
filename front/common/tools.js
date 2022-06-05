/*
    递归函数
    @parent 原始数组数据
    @next 下一个需要递归的子数据属性名
    @cb 回调函数 后续处理 传递当前数据项、上级数据、当前序号
*/
export const recursion = (arr, next = 'children', cb = () => {}) => {
	arr.forEach((child, index) => {
		cb(child, arr, index);
		if (child[next] && child[next].length) {
			recursion(child[next], next, cb);
		}
	});
};