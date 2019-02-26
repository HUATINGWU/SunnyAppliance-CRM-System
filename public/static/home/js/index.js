
//用户登录显示欢迎
// console.log(sessionStorage.getItem('user'))
// console.log($('#username'))
$('#username').html(sessionStorage.getItem('user'));
//注销
$('.exit').click(function () {
    sessionStorage.removeItem('user');
    location.href = 'index.html';
})

//主页面判断是教师注册还是学生注册记录session
$(function () {
    console.log($('.teacher_btn'), $('.stucents_btn'))
    $('.teacher_btn').click(function () {
        sessionStorage.setItem('type', 'teacher');
    })
    $('.stucents_btn').click(function () {
        sessionStorage.setItem('type', 'students');
    })
})



