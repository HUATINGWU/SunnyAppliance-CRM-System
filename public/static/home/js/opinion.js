/**
 * Created by Administrator on 2018/6/14 0014.
 */
//面包屑导航自适应改变\
var course = sessionStorage.getItem('course').split(' ');
$('.exam-content').html(sessionStorage.getItem('nav'));
$('.course-content').html(course[1]);
var test = sessionStorage.getItem('test').split(' ');
var degree = test[0].split('：')[1];
$('.test').html(test[1]);
//点击开始测试之后将意见提交到后台，并跳转页面到测试页面
$('.start-test').click(function () {
    $.ajax({
        url:'./php/opinion.php',
        type:'POST',
        data:{
            'user':sessionStorage.getItem('user'),
            'test':sessionStorage.getItem('test').split(' ')[1],
            'opinion_content':$('.opinion-content').val(),
        },
        contentType:'application/json,charset=utf-8',
        success:function (data) {
            data = JSON.parse(data);
            console.log(data);
            console.log(data.result);
            if(data.result){
                location.href = './test.html';//若不存在，则可以测验
            }else{
                $('.modal-body').html('您已经参加过该次考试了，去看看其他的吧~')
                $('#myModal').modal('show');
            }
        },
        error:function () {
            $('.modal-body').html('网络繁忙，走丢了...')
            $('#myModal').modal('hide');
        }
    })
    //
    $('.confirm').click(function () {
        $('#myModal').modal('hide');
    })
})