/**
 * Created by Administrator on 2018/6/11 0011.
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
    //点击导航栏显示对应的内容
    $('.nav-left').on('click','li',function () {
        // sessionStorage.setItem('nav',$(this).)
        str = this.children[0].innerHTML;
        str = str.split('<')[0];
        sessionStorage.setItem('nav',str);
    })
    console.log($('#select_test_form'))
    $('a[href="#select_test_form"]').click(function () {
        console.log(sessionStorage.getItem('nav'))
        if(sessionStorage.getItem('nav')=='练习题'){
            $('tbody').html('<tr><th>练习号</th><th>练习科目</th><th>练习难度</th><th>练习学生</th><th>练习日期</th><th>修改日期</th><th>操作</th></tr>');
        }else{
            $('tbody').html('<tr><th>品牌</th><th>库存</th><th>价格</th><th>购买订单</th><th>反馈订单</th><th>售后订单</th><th>操作</th></tr>');
        }
    })
    $('a[href="#add_test_form"]').click(function () {
        if(sessionStorage.getItem('nav')=='练习题'){
            $('.label1').html('练习科目：');
            $('.label2').html('添加练习的用户：');
            $('#add').html('添加练习');
        }else{
            $('.label1').html('考试科目：');
            $('.label2').html('添加考试的用户：');
            $('#add').html('添加考试');
        }
    })
    //隐藏提示框
    $('.confirm').click(function(){
        $('#myModal').modal('hide');
    })
    //添加---记录生成测试的科目，试题难度，测试的学生
    $('#add').click(function () {
        $.ajax({
            url:'./php/add.php',
            type:'POST',
            // dataType:'json',
            contentType: 'application/json; charset=utf-8',
            data:{
                'type':sessionStorage.getItem('nav'),
                'test-course':$('#add-test-course').val(),
                'test-difficulty':$('#test-difficulty').val(),
                'start-num':$('#start-num').val(),
                'end-num':$('#end-num').val(),
            },
            success:function (data) {
                // console.log(data)
                data = JSON.parse(data);
                if(data.result){
                    $('.modal-body').html('添加成功~');
                    $('#myModal').modal('show');
                }else{
                    $('.modal-body').html('添加失败了~');
                    $('#myModal').modal('show');
                }
            },
            error:function () {
                $('.modal-body').html('网络繁忙，走丢了...');
                $('#myModal').modal('show');
            }
        })
    })


    $.validator.addMethod('stu_num',function (value) {
        var reg = /^20154070\d{4}$/;
        return reg.test(value);
    },'学号格式不正确！')
    var validate = $('.add_test_form').validate({
        errorClass:'err',
        errorElement:'span',
        wrapper:'div',
        rules:{
            start_num: {
                required:true,
                stu_num:true
            },
            end_num: {
                required:true,
                stu_num:true
            }
        },
        message:{
            start_num:{
                required:'请输入学号！',
            },
            end_num: {
                required:'请输入学号！'
            }
        }
    })
    //查询---
    $('.prompt').hide();
    $('#sel-test-course').change(function () {
        // console.log($('tbody'))
        // console.log($('#sel-test-course').val())
        $.ajax({
            url:'./php/select.php',
            type:'POST',
            contentType: 'application/json; charset=utf-8',
            data:{
                'type':sessionStorage.getItem('nav'),
                'sel-course':$('#sel-test-course').val()
            },
            success:function (data) {
                data = JSON.parse(data);
                //查询
                if(data==false){
                    $('.prompt').show();
                    if(sessionStorage.getItem('nav')=='练习题'){
                        $('.table1>tbody').html('<tr><th>练习号</th><th>练习科目</th><th>练习难度</th><th>练习学生</th><th>练习日期</th><th>修改日期</th><th>操作</th></tr>');
                    }else{
                        $('.table1>tbody').html('<tr><th>考试号</th><th>考试科目</th><th>考试难度</th><th>考试学生</th><th>考试日期</th><th>修改日期</th><th>操作</th></tr>');
                    }
                }else{
                    $('.prompt').hide();
                    if(sessionStorage.getItem('nav')=='练习题'){
                        $('.table1>tbody').html('<tr><th>练习号</th><th>练习科目</th><th>练习难度</th><th>练习学生</th><th>练习日期</th><th>修改日期</th><th>操作</th></tr>');
                    }else{
                        $('.table1>tbody').html('<tr><th>考试号</th><th>考试科目</th><th>考试难度</th><th>考试学生</th><th>考试日期</th><th>修改日期</th><th>操作</th></tr>');
                    }
                    for(var i=0;i<data.length;i++){
                        $('.table1>tbody').append('<tr><td>'+data[i].test_id+'</td><td>'+data[i].test_course+'</td><td>'+data[i].test_difficulty+'</td><td>'
                            +data[i].start_num+'-'+data[i].end_num+'</td><td>'+data[i].test_date+'</td><td>'+data[i].update_date+'</td><td><p class="btn btn-primary btn-xs delete">删除</p><p class="btn btn-primary btn-xs update">修改</p></td>');
                    }
                    //删除
                    $('.delete').click(function () {
                        // console.log($(this).parent().parent().find('td:first-child').html());
                        var tr = $(this).parent().parent();
                        $.ajax({
                            url:'./php/delete.php',
                            type:'POST',
                            contentType:'application/json; charset=utf-8',
                            data:{
                                'type':sessionStorage.getItem('nav'),
                                'del-id':$(this).parent().parent().find('td:first-child').html()
                            },
                            success:function (data) {
                                data = JSON.parse(data);
                                // console.log(data.del_result);
                                if(data.del_result==1){
                                    tr.remove();
                                }
                            },
                            error:function () {
                                $('.modal-body').html('网络繁忙，删除失败了...');
                                $('#myModal').modal('show');
                            }
                        })
                    })
                    //修改
                    $('.update').click(function () {
                        td = $(this).parent().parent().children();
                        if($(this).html()=='修改'){
                            $(this).html('确定');
                            td[1].innerHTML = '<select><option>PHP</option><option>HTML</option><option>jQuery</option><option>UML</option>option>MySQL</option></select>';
                            td[2].innerHTML = '<select><option>easy</option><option>normal</option><option>difficulty</option></select>';
                            td[3].innerHTML = '<input type="text" style="width:157px">';
                        }else{
                            var update = $(this);
                            for(var i=1;i<4;i++){
                                // console.log(td[i].children[0])
                                if(td[i].children[0]){
                                    td[i].innerHTML = td[i].children[0].value;
                                }
                            }
                            var reg = /^20154070\d{4}-20154070\d{4}$/;
                            if(!reg.test(td[3].innerHTML)){
                                td[3].innerHTML = '<input type="text" placeholder="开始学号-结束学号" style="color: red;;border: 1px solid red;;width:157px">';
                            }
                            var count = 0;
                            for(var j=0;j<4;j++){
                                if(td[j].children[0]){
                                    // console.log(td[j].children[0])
                                    count++;
                                }
                                if(count==0){
                                    // console.log(td[3].innerHTML.split('-'))
                                    $.ajax({
                                        url:'./php/update.php',
                                        type:'POST',
                                        contentType:'application/json; charset=utf-8',
                                        data:{
                                            'type':sessionStorage.getItem('nav'),
                                            'test-id':td[0].innerHTML,
                                            'test-course':td[1].innerHTML,
                                            'test-difficulty':td[2].innerHTML,
                                            'start-num':td[3].innerHTML.split('-')[0],
                                            'end-num':td[3].innerHTML.split('-')[1],
                                        },
                                        success:function (data) {
                                            data = JSON.parse(data);
                                            if(data.update_result==1){
                                                $('.modal-body').html('修改成功！');
                                                $('#myModal').modal('show');
                                                update.html('修改');
                                            }
                                        },
                                        error:function () {
                                            $('.modal-body').html('网络繁忙，删除失败了...');
                                            $('#myModal').modal('show');
                                        }
                                    })
                                }
                            }
                        }
                    })
                }

            },
            error:function () {
                $('.modal-body').html('网络繁忙，查询失败了...');
                $('#myModal').modal('show');
            }
        })
    })
    //考试统计
    $('#sel-result-course').change(function () {
        $.ajax({
            url:'./php/select.php',
            type:'POST',
            contentType: 'application/json; charset=utf-8',
            data:{
                'type':'考试题',
                'sel-course':$('#sel-result-course').val()
            },
            success:function (data) {
                data = JSON.parse(data);
                //查询
                if (data == false) {
                    $('.prompt').show();
                    $('.table2>tbody').html('<th><th>考试号</th><th>考试科目</th><th>考试难度</th><th>考试学生</th><th>考试日期</th><th>修改日期</th><th>操作</th></tr>');
                } else {
                    $('.prompt').hide();
                    $('.table2>tbody').html('<tr><th>考试号</th><th>考试科目</th><th>考试难度</th><th>考试学生</th><th>考试日期</th><th>修改日期</th><th>操作</th></tr>');
                    for (var i = 0; i < data.length; i++) {
                        $('.table2>tbody').append('<tr><td>' + data[i].test_id + '</td><td>' + data[i].test_course + '</td><td>' + data[i].test_difficulty + '</td><td>'
                            + data[i].start_num + '-' + data[i].end_num + '</td><td>' + data[i].test_date + '</td><td>' + data[i].update_date + '</td><td><p class="btn btn-primary btn-xs check">查看</p></td></tr>');
                    }
                }
                //显示该次考试的及格率
                var check = $('.check');
                for(var i=0;i<check.length;i++){
                    (function (i) {
                        check[i].onclick = function () {
                            $('#myEcharts').show();
                            console.log(i);
                            exam_id = $(this).parent().parent().children()[0].innerHTML;
                            exam_type = $(this).parent().parent().children()[1].innerHTML;
                            $.ajax({
                                url:'./php/check_teacher.php',
                                type:'POST',
                                contentType:'application/json;charset=utf-8',
                                data:{
                                    'exam_id':exam_id,
                                    'exam_type':exam_type,
                                    'order':i,
                                },
                                success:function (data) {
                                    // data = JSON.parse(data);
                                    var myCharts = echarts.init(document.querySelector('#myEcharts'));
                                    var options = {
                                        title:{
                                            text:'测验成绩汇总',
                                            subtext:'简单',
                                            x:'center',
                                        },
                                        toolbox:{
                                            feature:{
                                                saveAsImage:{}
                                            }
                                        },
                                        tooltip:{
                                            trigger:'item'
                                        },
                                        legend:{
                                            data:['不及格','60分-70分','70分-80分','80分-90分','90分-100分'],
                                            x:'left',
                                            orient:'vertical'
                                        },
                                        series:{
                                            name:'人数',
                                            type:'pie',
                                            // radius:'50%',
                                            radius:['20%','50%'],//空心圆
                                            center:['50%','60%'],
                                            // roseType:'true',//南丁格尔图
                                            data:[
                                                {value:2,name:'不及格'},
                                                {value:10,name:'60分-70分'},
                                                {value:12,name:'70分-80分'},
                                                {value:15,name:'80分-90分'},
                                                {value:5,name:'90分-100分'},
                                            ]
                                        }
                                    }
                                    myCharts.setOption(options);
                                },
                                error:function () {

                                }
                            })
                        }
                    })(i)
                }
            },
            error:function () {
                $('.modal-body').html('网络繁忙，查询失败了...');
                $('#myModal').modal('show');
            }
        })
    })
    //
    $('.see').click(function () {
        $('.prompt').hide();
    })
    $('#myEcharts').hide();
})
