/**
 * Created by Administrator on 2018/12/31.
 */
//用户注册
$(function () {
    //判断用户类型以显示不同的欢迎标题
    if(sessionStorage.getItem('type')=='teacher'){
        $('.login-headline').html('登录使用-销售员');
        $('.register-headline').html('立即注册-销售员');
    }else{
        $('.login-headline').html('登录使用-售后员');
        $('.register-headline').html('立即注册-售后员');
    }
    //生成随机验证码
    function verification(){
        $('.qrcode_div>p').html(Math.random().toString(36).substr(2, 4));
    };
    verification();
    $('.qrcode_div>p').click(function(){
        verification();
    })
    //但从数据库验证的数据已存在时，操作之后再次提交消除提示
    $('.div1').hide();
    $('.div2').hide();
    $('#username').blur(function () {
        $('#username').css('color','#000');
        $('.div1').hide();
    })
    $('#phone').blur(function () {
        $('#phone').css('color','#000');
        $('.div2').hide();
    })
    //数据格式正确，点击提交按钮的时候把数据传到后台，返回验证信息
    // $('#myModal').hide();
    $.validator.setDefaults({
        submitHandler:function(){
            $.ajax({
                url:"./php/register.php",
                type:"POST",
                contentType: 'application/json; charset=utf-8',
                data:{
                    username:$('#username').val(),
                    password:$('#password').val(),
                    phone:$('#phone').val(),
                    type:sessionStorage.getItem('type'),
                },
                success:function (data) {
                    if(data){
                        // console.log('1:'+data);
                        data = JSON.parse(data);
                        // console.log(('2:'+data))
                        if(data.username==0){//用户名已存在
                            $('#username').css('color','red');
                            $('.div1').show();
                            // $('.div1').focus();
                        }
                        if(data.phone==0){
                            $('#phone').css('color','red');
                            $('.div2').show();
                        }
                    }else{
                        sessionStorage.setItem('user',$('#username').val());
                        if(sessionStorage.getItem('type')=='students'){
                            $('.confirm').attr('href','operation_student.html');
                        }else{
                            $('.confirm').attr('href','operation_teacher.html');
                        }
                        $('#myModal').modal('show');
                    }
                },
                error:function(){
                    $('.modal-body').html('很遗憾，注册失败了...');
                    $('.confirm').attr('href','register.html');
                    $('#myModal').modal('show');
                }
            });
        }
    })
    //添加验证格式方法
    if(sessionStorage.getItem('type')=='students'){
        $.validator.addMethod('userformat', function (value) {
            var reg = /^20154070\d{4}$/;
            return reg.test(value);
        }, '用户名格式不正确！');
    }else if(sessionStorage.getItem('type')=='teacher'){
        $.validator.addMethod('userformat', function (value) {
            var reg = /^20150407\d{4}$/;
            return reg.test(value);
        }, '用户名格式不正确！')
    }
    $.validator.addMethod('pwdformat',function (value) {
        var reg = /^\w{8,16}$/;
        return reg.test(value);
    },'请输入8-16位密码！')
    $.validator.addMethod('telformat', function (value) {
        var reg = /^1[3-9]\d{9}$/;
        return reg.test(value);
    }, '手机号格式不正确！');
    $.validator.addMethod('code',function(value){
        return $('.qrcode_div>p').html()==value;
    },'验证码不正确！');
    //验证数据
    var validate = $('.register-form').validate({
        errorClass: 'err',
        errorElement: 'span',
        wrapper: 'div',
        rules: {
            username: {
                required: true,
                userformat: true,
            },
            password: {
                required: true,
                pwdformat: true,
            },
            phone: {
                required: true,
                telformat: true,
            },
            verification_code: {
                required:true,
                code:true,
            },
            confirm_password:{
                equalTo: '#password',
            }
        },
        messages: {
            username: {
                required: '请输入用户名！',
            },
            password: {
                required: '请输入密码！'
            },
            phone: {
                required: '请输入手机号！'
            },
            verification_code:{
                required:'请输入验证码！'
            },
            confirm_password:{
                equalTo:'两次密码不一致！'
            }
        }
    });

})