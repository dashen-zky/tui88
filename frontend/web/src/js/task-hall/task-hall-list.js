$(function () {
    //layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });
    //选择下拉单
    $('.dropdown .dropdown-menu').on('click','li',selectedOption);
    //下拉单选择某一个
    function selectedOption(){
        var _text = $(this).text();
        var _value = $(this).attr("value");
        $(this).parent().prev().find('span:eq(0)').text(_text);
        $(this).parents(".dropdown").find("div:eq(0)").find("input:eq(0)").val(_value);
    }

    // 搜索分类
    $(".category li").on("click",function(){
        var sort_up = $(this).find(".up");
        var sort_down = $(this).find(".down");
        var other_sort_up = $(this).siblings().find(".up");
        var other_sort_down = $(this).siblings().find(".down");

        $(this).addClass("choosed").siblings().removeClass("choosed");
        if($(this).hasClass("surplus")){
            if(sort_up.hasClass('up-choosed')){
                $(this).siblings("input").attr("value",$(this).attr("value"));
                sort_up.removeClass("up-choosed").addClass("up-hover");
                sort_down.removeClass("down-hover").addClass("down-choosed");
                other_sort_up.removeClass("up-hover").removeClass("up-choosed");
                other_sort_down.removeClass("down-hover").removeClass("down-choosed");
            }else{
                $(this).siblings("input").attr("value",20-$(this).attr("value"));
                sort_up.removeClass("up-hover").addClass("up-choosed");
                sort_down.removeClass("down-choosed").addClass("down-hover");
                other_sort_up.removeClass("up-hover").removeClass("up-choosed");
                other_sort_down.removeClass("down-hover").removeClass("down-choosed");
            }
        }else{
            if(sort_down.hasClass('down-choosed')){
                $(this).siblings("input").attr("value",20-$(this).attr("value"));
                sort_up.addClass("up-choosed").removeClass("up-hover");
                sort_down.addClass("down-hover").removeClass("down-choosed");
                other_sort_up.removeClass("up-hover").removeClass("up-choosed");
                other_sort_down.removeClass("down-hover").removeClass("down-choosed");
            }else{
                $(this).siblings("input").attr("value",$(this).attr("value"));
                sort_up.addClass("up-hover").removeClass("up-choosed");
                sort_down.addClass("down-choosed").removeClass("down-hover");
                other_sort_up.removeClass("up-hover").removeClass("up-choosed");
                other_sort_down.removeClass("down-hover").removeClass("down-choosed");
            }
        }
    });
    $(".category li").hover(function () {
        var sort_up = $(this).find(".up");
        var sort_down = $(this).find(".down");
        if(sort_up.hasClass('up-hover') || sort_up.hasClass('up-choosed') || sort_down.hasClass('down-hover') || sort_down.hasClass('down-choosed')){
            return false;
        }
        sort_up.addClass('up-hover');
        sort_down.addClass('down-hover');
    },function () {
        var sort_up = $(this).find(".up");
        var sort_down = $(this).find(".down");
        if(sort_up.hasClass('up-choosed') || sort_down.hasClass('down-choosed')){
            return false;
        }
        sort_up.removeClass('up-hover');
        sort_down.removeClass('down-hover');
    });

    // 搜索任务
    $(".btn-search").on("click",function(){
        // var min_price = Number($(".min-price").val());
        // var max_price = Number($(".max-price").val());
        // var task_name = $(".input-search-task").val();
        // var get_status = $("select option:selected").val();

    });


    //补充收款信息
    $(".btn-submit").on("click",function(){
        var bank_name = $(".bank-name").val();
        var card_number = $.trim($(".card-number").val()).replace(/\s/g, '');
        var bank_card_name = $(".bank-card-name").val();
        if(bank_name == ""){
            wom_alert.msg({
                content:"请填写所属银行！",
                delay_time:1000
            });
            return false;
        }
        if(card_number == ""){
            wom_alert.msg({
                content:"请填写银行卡号！",
                delay_time:1000
            });
            return false;
        }else if(isNaN(card_number)){
            wom_alert.msg({
                content:"银行卡号必须为16或19位的数字！",
                delay_time:1000
            });
            return false;
        } else if(card_number.length != 16 && card_number.length != 19){
            wom_alert.msg({
                content:"银行卡号必须为16或19位！",
                delay_time:1000
            });
            return false;
        }

        if(bank_card_name == ''){
            wom_alert.msg({
                content:"请填写收款姓名！",
                delay_time:1000
            });
            return false;
        }
    });

    // 判断任务数量
    $(document).ready(function () {
        var task_account = $('.table tbody').find('tr').length;
        if(task_account < 1){
            $('.no-task').show();
        }else{
            $('.no-task').hide();
        }
        // if(task_account < 3){
        //     $('.page-wb').hide();
        // }else{
        //     $('.page-wb').show();
        // }
    });


    $(".min-price").on("keyup",function() {
        numRestraint($(this));
    });
    $(".max-price").on("keyup",function() {
        numRestraint($(this));
    });
    // 限制输入数字
    function numRestraint(_this_con){
        _this_con.val( _this_con.val().replace(/\D/g, ''));
    }


    //领取任务数量
    $(".reduce").on("click",function () {
        var task_num_input = $(".get-task-num .get-task-number"),
            task_num = Number($(".get-task-num .get-task-number").val());
        if(task_num < 2 ){
            return false;
        }
        task_num = task_num - 1;
        task_num_input.val(task_num);
    });
    $(".add").on("click",function () {
        var task_num_input = $(".get-task-num .get-task-number"),
            task_num = Number($(".get-task-num .get-task-number").val()),
            max_task_num = $(".max-task-num").text();
        if(task_num > max_task_num - 1 ){
            return false;
        }
        task_num = task_num + 1;
        task_num_input.val(task_num);
    });

    $(".get-task-num .get-task-number").on("keyup",function() {
        taskNumRestraint($(this));
    });
    // 限制输入数字且不超过最大值
    function taskNumRestraint(_this_con){
        var max_task_num = Number($(".max-task-num").text());
        _this_con.val( _this_con.val().replace(/\D/g, ''));
        var task_num = Number(_this_con.val());
        if(task_num > max_task_num){
            _this_con.val(max_task_num);
        }
    }
});



