$(function () {
    // layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });

    //任务内容
    var ue = UE.getEditor('editor');

    $('.task-name input').on("keyup",function () {
        var task_name_len = $(this).val().length;
        $(this).siblings().children('.font-num').text(task_name_len);
    });

    $(".customer-account input").on("keyup",function() {
        numRestraint($(this));
        calculateTotalSum();
    });
    $(".task-unit-price input").on("keyup",function() {
        numRestraint($(this));
        calculateTotalSum();
    });

    $('.input-group input').on({
        focus:function(){
            $(this).siblings('i').fadeIn(100);
        },
        blur:function(){
            $(this).siblings('i').fadeOut(100);
            loginAble();
        },
        keyup:function(){
            loginAble();
        }
    });


    //限制输入数字
    function numRestraint(_this_con){
        _this_con.val( _this_con.val().replace(/[^\d\.]/g, ''));
    }

    //计算任务总额
    function calculateTotalSum() {
        var total_price = $(".total-price i"),
            num_of_people = Number($(".customer-account input").val()),
            unit_price = Number($(".task-unit-price input").val());
        total_price.text(Number(num_of_people * unit_price).toLocaleString());
    }

    // 任务配置唯一问题
    var unique_config = $(".unique-config input");
    unique_config.on("click",function () {
        if(this.checked){
            $(this).parents("label").siblings("label").find("input").prop("checked",true);
        }else {
            // $(this).parents("label").siblings("label").find("input").prop("checked",false);
        }
    });

    $('#task-publish-form').on('click',".submit",function() {
        $(this).parents(".btn-section").find("input").val($(this).attr("value"));
        var content = ue.getContent();
        // content.replace(/<img/g,'<img style="max-width:100%"');
        $(".task-content-detail").val(content.replace(/<img/g,'<img style="max-width:100%"'));
        var validateUrl = $(this).attr("validateUrl");
        var form = $(this).parents('form');
        var action = $(this).attr("url");
        if(validateUrl == undefined){
            $.post(action,form.serialize(),function (res) {
                var data = jQuery.parseJSON(res);
                if(data.code == 0){
                    form.attr('action', action);
                    form.submit();
                }else{
                    wom_alert.msg({
                        content:data.message,
                        delay_time:1000
                    });
                }
            });
            return ;

        }

        $.post(validateUrl,form.serialize(),function(res) {
            var data = jQuery.parseJSON(res);
            if(data.code == 0){
                form.attr('action', action);
                form.submit();
            }else if(data.code == 1){
                for(var i in data.message){
                    switch (i){
                        case 'start_getting_time':
                        case 'end_getting_time':
                        case "start_execute_time":
                        case "end_execute_time":
                        case "limit":
                        case "unit_money":
                        case "title":
                        case "content":
                        case "check_standard":
                        case "executor_information_config":
                            wom_alert.msg({
                                content:data.message[i]+'！',
                                delay_time:1500
                            });
                            break;

                    }

                }
            }
        });

    });

});