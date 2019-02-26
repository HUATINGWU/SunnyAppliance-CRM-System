/**
 * Created by Administrator on 2018/6/10 0010.
 */
$(function () {
    ////手风琴菜单
    $('.col-div-exam').hide();
    $('.col-exam').click(function () {
        $('.col-div-exam').toggle(500);
    });
    $('.col-div-exercise').hide();
    $('.col-exercise').click(function () {
        $('.col-div-exercise').toggle(500);
    })
    //点击左侧导航栏记录点击的是哪一个
    $('.nav-left').on('click','li',function () {
        // sessionStorage.setItem('nav',$(this).)
        str = this.children[0].innerHTML;
        str = str.split('<')[0];
        sessionStorage.setItem('nav',str);
    })
    //记录考试科目
    $('.list-group-item').click(function () {
        sessionStorage.setItem('course',$(this).parent().attr('id')+' '+$(this).html());
        course = sessionStorage.getItem('course').split(' ');
        // console.log($('.exam-info'));
        $.ajax({
            url:'./php/operation_students.php',
            type:'POST',
            contentType:'application/json;charset=utf-8',
            data:{
                'user':sessionStorage.getItem('user'),
                'course':course[1],
                'type': sessionStorage.getItem('nav'),
            },
            success:function (data) {
                data = JSON.parse(data);
                // console.log(data);
                if(data.length){
                    $('.test_content').html('<h2>测验</h2> <hr>');
                }else{
                    $('.test_content').html('<h2>测验</h2> <hr><div class="col-md-12 "> <h3 class="text-center">您目前没有测试~</h3> </div>');
                }
                for(var i=0;i<data.length;i++){
                    $('.test_content').append('<div class="container-fluid col-xs-12 col-sm-6 col-md-4"><div class="exam-tab-div2 col-xs-12"> <img class="exam-tab-img" src="images/exam-default.png" alt=""> <h4 class="h4">PHP测验一</h4><p class="exam-info">难度：简单</p><li> <span class="glyphicon glyphicon-cloud-download"></span> <p class="exam-fre">上传时间：2018/6/12</p> </li> </div> </div>')
                }
                var num = ['一','二','三','四','五','六'];
                if(course[0]=='exam'){
                    $('h2').html(course[1]+'测验');
                    for(var i=0;i<$('.h4').length;i++){
                        $('.h4')[i].innerHTML = course[1]+'测验'+num[i];
                        $('.exam-info')[i].innerHTML = '难度：'+test_degree(data[i].test_difficulty);
                        $('.exam-fre')[i].innerHTML = '上传时间'+data[i].test_date;
                    }
                }else{
                    $('h2').html(course[1]+'练习');
                    for(var i=0;i<$('.h4').length;i++){
                        $('.h4')[i].innerHTML = course[1]+'练习'+num[i];
                        $('.exam-info')[i].innerHTML = '难度：'+test_degree(data[i].exercise_difficulty);
                        $('.exam-fre')[i].innerHTML = '上传时间'+data[i].exercise_date;
                    }
                }
                //点击测验题或者练习题进入测试页面
                $('.exam-tab-div2').click(function () {
                    sessionStorage.setItem('test',$(this).find('.exam-info').html()+' '+$(this).find('.h4').html());
                    if(sessionStorage.getItem('nav')=='考试题'){
                        location.href = 'opinion.html';
                    }else{
                        location.href = 'test.html';
                    }

                })
            },
            error:function () {
                $('.modal-body').html('网络繁忙，走丢了...');
                $('#myModal').modal('show');
            }
        })
    })
    //格式化试题难度
    function test_degree(arg) {
        switch (arg){
            case 'easy': return '简单';break;
            case 'normal': return '一般';break;
            case 'difficulty': return '难';break;
            default:break;
        }
    }


})

