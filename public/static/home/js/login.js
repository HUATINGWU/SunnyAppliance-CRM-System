/**
 * Created by Administrator on 2018/12/30 0008.
 */
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
    });
    //但从数据库验证的数据已存在时，操作之后再次提交消除提示
    $('.div1').hide();
    $('#password').blur(function () {
        $('#username').css('color','#000');
        $('#password').css('color','#000');
        $('.div1').hide();
    })
    //后台验证信息是否准确，返回结果
    $.validator.setDefaults({
        submitHandler:function () {
            $.ajax({
                url:"./php/login.php",
                type:"POST",
                contentType: 'application/json; charset=utf-8',
                data:{
                    username:$('#username').val(),
                    password:$('#password').val(),
                    type:sessionStorage.getItem('type'),
                },
                success:function (data) {
                    console.log(('1:'+data));
                    data = JSON.parse(data);
                    console.log('2:'+data);
                    if(data.index==0){
                        $('#username').css('color','red');
                        $('#password').css('color','red');
                        $('.div1').show();
                    }else{
                        sessionStorage.setItem('user',$('#username').val());
                        if(sessionStorage.getItem('type')=='students'){
                            location.href = 'operation_student.html';
                        }else{
                            location.href = 'operation_teacher.html';
                        }
                    }
                },
                error:function () {
                    $('.modal-body').html('网络繁忙，走丢了...');
                    $('.confirm').attr('href','login.html');
                    $('#myModal').modal('show');
                }
            })
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
    $.validator.addMethod('code',function(value){
        return $('.qrcode_div>p').html()==value;
    },'验证码不正确！');
    //验证数据格式是否正确
    var validate = $('.login-form').validate({
        errorClass:'err',
        errorElment:'span',
        wrapper:'div',
        rules: {
            username: {
                required: true,
                userformat:true
            },
            password: {
                required: true
            },
            verification_code: {
                required: true,
                code: true,
            }
        },
        messages: {
            username: {
                required: '请输入用户名！',
            },
            password: {
                required: '请输入密码！'
            },
            verification_code: {
                required: '请输入验证码！'
            },
        }
    })
})