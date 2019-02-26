 function setafter() {
            var set = new Object();
            set.kind = $("#setkind").val();
            set.id = $("#ornumber").val();
            set.details = $("#setdetails").val();
            // var formData = $("#setform").serialize();
            // $.post('{:url("ajax_setafter")}',{"kind":$("#setkind").val(),"afterid":$("#ornumber").val(),details:$("#setdetails").val()},function(data){
            // $('fd').serialize();
            // });
            console.log(set);
            $.ajax({
                type: "post",
                url: "{:url('searchid')}",
                data: set,
                dataType: "json",
                success: function (data) {
                    if (data == 0) {
                        $("#myForm1").css({ display: 'none' });
                        $(".modal-backdrop").css({ display: 'none' });
                        // alert("shibei");
                        alert("售后办理中，请勿重复提交");
                        window.location.reload();
                        //   $("#myForm1").css({ display: 'inline' });
                        // $(".modal-backdrop").css({ display: 'inline' });



                    } else if(data == 6){
                         $("#myForm1").css({ display: 'none' });
                        $(".modal-backdrop").css({ display: 'none' });
                        // alert("shibei");
                        alert("订单不存在");
                        window.location.reload();
                    }else {
                        // $("#myForm1").css({ display: 'inline' });
                        // $(".modal-backdrop").css({ display: 'inline' });
                        console.log(data);
                        $('#afterid').text(data.d[0].sv_id);
                        $('#orid').text(set.id);
                        $('#pdid').text(data.s[0].or_pro_id);
                        $('#userid').text(data.s[0].or_user_id);
                        $('#afterdate').text(data.d[0].sv_time);
                        $('#afterman').text(data.d[0].sv_wk_id);
                        $('#afterkind').text(set.kind);
                        $('#details').text(set.details);
                    }


                }

            })
    }

function setback(){
        
}