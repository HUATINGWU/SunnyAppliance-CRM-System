/**
 * Created by Administrator on 2018/6/10 0010.
 */
$(function () {
    //面包屑导航自适应改变\
    var course = sessionStorage.getItem('course').split(' ');
    $('.exam-content').html(sessionStorage.getItem('nav'));
    $('.course-content').html(course[1]);
    var test = sessionStorage.getItem('test').split(' ');
    var degree = test[0].split('：')[1];
    $('.test').html(test[1]);
    //点开始测试之后获取一份试卷生成一个题库表
    $.ajax({
        type: 'post',
        url: './php/test.php',
        data: {
            'difficulty': degree,
            'course': course[1],
            'type': sessionStorage.getItem('nav')
        },
        success: function (data) {
            data = JSON.parse(data);
            // console.log(data);
            //从所有题目中随机抽取5道题
            var num = [];
            var new_num = [];
            for (var i = 0; i < data.length ; i++) {
                num[i] = i;
            }
            // console.log(num)
            if(sessionStorage.getItem('nav')=='考试题'){
                for (var i = 0; i < 5; i++) {
                    var x = Math.floor(Math.random() * (num.length - 1));
                    // console.log(x)
                    new_num[i] = data[num.splice(x, 1)];
                }//0-7  0 1 2 3 4 5 6 7
            }else{
                for (var i = 0; i < 3; i++) {
                    var x = Math.floor(Math.random() * (num.length - 1));
                    new_num[i] = data[num.splice(x, 1)];
                }//0-7  0 1 2 3 4 5 6 7
            }

            // console.log(new_num);
            //选项卡
            for (var i = 0; i < new_num.length; i++) {
                $('.question-allnum').append('<div class="col-sm-1 col-xs-2"> <a class="btn bg-success question-num" data-toggle="tab" href="#question' + i + '">' + (i + 1) + '</a> </div>')
                $('.exam-div').append('<form action="" class="exam-form tab-pane" id="question' + i + '"> <h3></h3> <div class="col-xs-12"> <input type="checkbox" class="exam-choose" value="A"> <p class="A"></p> </div> <div class="col-xs-12"> <input type="checkbox" class="exam-choose" value="B"> <p class="B"></p> </div> <div class="col-xs-12"> <input type="checkbox" class="exam-choose" value="C"> <p class="C"></p> </div> <div class="col-xs-12"> <input type="checkbox" class="exam-choose" value="D"> <p class="D"></p> </div> </form>')

                //将题目渲染到页面
                $('#question' + i).find('h3').html(i + 1 + '、' + new_num[i].question);
                $('#question' + i).find('p')[0].innerHTML = new_num[i].A;
                $('#question' + i).find('p')[1].innerHTML = new_num[i].B;
                $('#question' + i).find('p')[2].innerHTML = new_num[i].C;
                $('#question' + i).find('p')[3].innerHTML = new_num[i].D;

                var aList = document.querySelectorAll('.question-num');
                (function (i) {
                    aList[i].onclick = function () {
                        //每次换题的时候便利所有题目选项，做过的着色
                        for (var k = 0; k < new_num.length; k++) {
                            var count = 0;
                            for (var m = 0; m < $('#question' + k).find('p').length; m++) {
                                if ($('#question' + k).find('input')[m].checked) {
                                    count = 1;
                                }
                            }
                            if (count) {
                                $(".question-num")[k].className = 'btn btn-primary question-num';
                            } else {
                                $(".question-num")[k].className = 'btn bg-success question-num';
                            }
                        }
                    }
                })(i)
            }
            //让第一题显示
            $('#question0')[0].className += ' active';
            //题目前面的选择框
            for (var j = 0; j < new_num.length; j++) {
                // console.log(j)
                // new_num[j].A?$('#question' + j).find('input')[0].hidden=true:$('#question' + j).find('input')[0].hidden=false;
                // new_num[j].B?$('#question' + j).find('input')[1].hidden=true:$('#question' + j).find('input')[1].hidden=false;
                new_num[j].C ? $('#question' + j).find('input')[2].hidden = false : $('#question' + j).find('input')[2].hidden = true;
                new_num[j].D ? $('#question' + j).find('input')[3].hidden = false : $('#question' + j).find('input')[3].hidden = true;
            }

            var form = $('.exam-form');
            //上一题
            $('.prev').click(function () {
                for (var i = 0; i < form.length; i++) {
                    if (form[i].className == 'exam-form tab-pane active' && i != 0) {
                        form[i].className = 'exam-form tab-pane';
                        form[i - 1].className = 'exam-form tab-pane active';
                    }
                }
                //每次换题的时候便利所有题目选项，做过的着色
                for (var k = 0; k < new_num.length; k++) {
                    var count = 0;
                    for (var m = 0; m < $('#question' + k).find('p').length; m++) {
                        if ($('#question' + k).find('input')[m].checked) {
                            count = 1;
                        }
                    }
                    if (count) {
                        $(".question-num")[k].className = 'btn btn-primary question-num';
                    } else {
                        $(".question-num")[k].className = 'btn bg-success question-num';
                    }
                }
            })
            //下一题
            $('.next').click(function () {
                for (var i = form.length - 1; i >= 0; i--) {
                    if (form[i].className == 'exam-form tab-pane active' && i != form.length - 1) {
                        form[i].className = 'exam-form tab-pane';
                        form[i + 1].className = 'exam-form tab-pane active';
                    }
                }
                //每次换题的时候便利所有题目选项，做过的着色
                for (var k = 0; k < new_num.length; k++) {
                    var count = 0;
                    for (var m = 0; m < $('#question' + k).find('p').length; m++) {
                        if ($('#question' + k).find('input')[m].checked) {
                            count = 1;
                        }
                    }
                    if (count) {
                        $(".question-num")[k].className = 'btn btn-primary question-num';
                    } else {
                        $(".question-num")[k].className = 'btn bg-success question-num';
                    }
                }
            })
            //计算用户得分
            function get_score() {
                for (var k = 0; k < new_num.length; k++) {
                    // console.log(k)
                    var count = '';
                    for (var m = 0; m < $('#question' + k).find('p').length; m++) {
                        // console.log(m);
                        if ($('#question' + k).find('input')[m].checked) {
                            count += $('#question' + k).find('input')[m].value;
                        }
                    }
                    arr[k] = count;
                    // console.log(arr,arr[k],k)
                    if (count == new_num[k].is_true) {
                        score++;
                    }
                    console.log('score:' + score, 'count:' + count,'new_num[k]:' + new_num[k].is_true,'k:'+k);
                }
                score = (score / new_num.length) * 100;
                score = score.toFixed();
                // console.log(score);
            }
            //将用户答案与正确答案比较
            function compare() {
                $('.content2').hide();
                $('.cancel').hide();
                var str1 = '';
                var str2 = '';
                for (var i = 0; i < arr.length; i++) {
                    str1 += i + 1 + ':' + arr[i] + ' ';
                    str2 += i + 1 + ':' + new_num[i].is_true + ' ';
                }
                $('.user-answers').html('您的答案：'+str1);
                $('.correct-answers').html('正确答案：'+str2);
                $('.modal-title').html('您的分数：' + score);
                $('.content1').show();
            }
            //交卷
            var score = 0;
            var arr = [];
            //若未在规定时间内交卷，将自动交卷
            if (!time) {
                get_score();
                compare();
                $('.content1').hide();
                $('.content2').html('您确定要提交吗？').show();
                $('#myModal').modal('show');
            } else {
                $('.submit').click(function () {
                    get_score();
                    // console.log('score:' + score);
                    $('.content1').hide();
                    $('.content2').html('您确定要提交吗？').show();
                    $('#myModal').modal('show');
                })
                $('.confirm').click(function () {
                    if ($('.content2').html() == '您确定要提交吗？'&&sessionStorage.getItem('nav')=='考试题') {
                        $.ajax({
                            url: './php/result.php',
                            type: 'POST',
                            contentType: 'application/json;charset=utf-8',
                            data: {
                                'user': sessionStorage.getItem('user'),
                                'test': test[1],
                                'score': score,
                            },
                            success: function () {
                                compare();
                                $('.confirm').click(function () {
                                    location.href = './operation_student.html';
                                })
                            },
                            error: function () {
                                $('.content1').hide();
                                $('.content2').html('网络繁忙，走丢了...').show();
                                $('#myModal').modal('show');
                                $('.confirm').click(function () {
                                    $('#myModal').modal('hide');
                                    location.reload();
                                })
                            }
                        })
                    }else{
                        compare();
                        $('.confirm').click(function () {
                            location.href = './operation_student.html';
                        })
                    }
                })
            }
        },
        error: function () {
            $('.content1').hide();
            $('.content2').html('网络繁忙，走丢了...').show();
            $('#myModal').modal('show');
            $('.confirm').click(function () {
                $('#myModal').modal('hide');
                location.reload();
            })
        }
    })
    $('.cancel').click(function () {
        $('#myModal').modal('hide');
    })
    //时间
    var time = 60 * 15;
    var minute = '00', second = '00';
    if (sessionStorage.getItem('nav') == '考试题') {
        time = 60 * 15;
        $('.rest_time').html('00:15:00');
    } else {
        time = 60 * 10;
        $('.rest_time').html('00:10:00');
    }

    var s1 = setInterval(function () {
        time--;
        second = Math.floor(time % 60);
        minute = Math.floor(time / 60);
        if (minute == 0) {
            minute = '00';
        } else if (minute > 0 && minute < 10) {
            minute = '0' + minute;
        }
        if (second == 0) {
            second = '00';
        } else if (second > 0 && second < 10) {
            second = '0' + second;
        }
        $('.rest_time').html('00' + ':' + minute + ':' + second);
    }, 1000);



})