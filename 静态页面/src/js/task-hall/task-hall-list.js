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
        $(this).parent().prev().find('span:eq(0)').text(_text);
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
                sort_up.removeClass("up-choosed").addClass("up-hover");
                sort_down.removeClass("down-hover").addClass("down-choosed");
                other_sort_up.removeClass("up-hover").removeClass("up-choosed");
                other_sort_down.removeClass("down-hover").removeClass("down-choosed");
            }else{
                sort_up.removeClass("up-hover").addClass("up-choosed");
                sort_down.removeClass("down-choosed").addClass("down-hover");
                other_sort_up.removeClass("up-hover").removeClass("up-choosed");
                other_sort_down.removeClass("down-hover").removeClass("down-choosed");
            }
        }else{
            if(sort_down.hasClass('down-choosed')){
                sort_up.addClass("up-choosed").removeClass("up-hover");
                sort_down.addClass("down-hover").removeClass("down-choosed");
                other_sort_up.removeClass("up-hover").removeClass("up-choosed");
                other_sort_down.removeClass("down-hover").removeClass("down-choosed");
                return false;
            }else{
                sort_up.addClass("up-hover").removeClass("up-choosed");
                sort_down.addClass("down-choosed").removeClass("down-hover");
                other_sort_up.removeClass("up-hover").removeClass("up-choosed");
                other_sort_down.removeClass("down-hover").removeClass("down-choosed");
            }
        }
    })
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
        var min_price = Number($(".min-price").val());
        var max_price = Number($(".max-price").val());
        var task_name = $(".input-search-task").val();
        var get_status = $("select option:selected").val();

        if(min_price !='' && max_price != ''){
            if (isNaN(min_price)||isNaN(max_price)){
                wom_alert.msg({
                    content:"请输入有效数字",
                    delay_time:1500
                })
            }

            if (max_price <= min_price){
                wom_alert.msg({
                    content:"请输入有效的任务金额范围",
                    delay_time:1500
                })
            }
        }
    })


    //补充收款信息
    $(".btn-submit").on("click",function(){
        var bank_name = $(".bank-name").val();
        var card_number = $(".card-number").val();
        if(bank_name == ""){
            wom_alert.msg({
                content:"请填写所属银行！",
                delay_time:1500
            })
            return false;
        }
        if(card_number == ""){
            wom_alert.msg({
                content:"请填写银行卡号！",
                delay_time:1500
            })
            return false;
        }else if(isNaN(card_number)){
            wom_alert.msg({
                content:"请填写数字！",
                delay_time:1500
            })
            return false;
        }
    })

    // 判断任务数量
    $(document).ready(function () {
        var task_account = $('.table tbody').find('tr').length;
        // console.log(task_account);
        if(task_account < 1){
            $('.no-task').show();
        }else{
            $('.no-task').hide();
        }
        if(task_account < 20){
            $('.page-wb').hide();
        }else{
            $('.page-wb').show();
        }
    })
})



