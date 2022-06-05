//轮播图
// const swiper = new Swiper('.swiper', {
// 	speed: 1000,
// 	loop: true,
// 	effect: 'fade',
// 	autoplay: {
// 		delay: 3000,
// 		disableOnInteraction: false,
// 		pauseOnMouseEnter: true
// 	},
// 	pagination: {
// 		el: '.swiper-pagination',
// 		clickable: true
// 	},
// 	navigation: {
// 		nextEl: '.swiper-button-next',
// 		prevEl: '.swiper-button-prev',
// 	},
// })

// //网址二维码
// new QRCode(document.querySelector('.site-qrcode'), {
// 	text: location.origin,
// 	width: 300,
// 	height: 300,
// })


// 菜单按钮
const menu = document.querySelector('.c-menu')
const menu_btn = menu.querySelector('.menu-btn')
const menu_close_btn = menu.querySelector('.close-btn')
menu_btn.onclick = ()=>{
	menu.classList.add('show')
}
menu_close_btn.onclick = ()=>{
	menu.classList.remove('show')
}
