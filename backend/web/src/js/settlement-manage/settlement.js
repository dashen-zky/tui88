// 全部结算
$(".total-account").on("click",".settlement-all",AllSettlement);
function AllSettlement() {
    var amount_choosed = $("tbody input:checked").length;
    if(amount_choosed < 1){
        wom_alert.msg({
            content:"请先选择用户！",
            delay_time:1500
        });
        return;
    }
    var data = [];
    $("tbody input:checked").each(function () {
        data.push($(this).val());
    });
    var targetUrl = $(this).attr("url");
    var flag = false;

    wom_alert.confirm({
        content:"是否确认全部结算？"
    },function () {
        $.ajax({
            url : targetUrl,
            type : "get",
            data : {ids:data},
            success : function (res) {
                var msg = jQuery.parseJSON(res);
                wom_alert.msg({
                    content:msg.message,
                    delay_time:1000
                });
                if(msg.code == 0){
                    flag = true;
                }else {
                    flag = false;
                }
            },
            async:false
        });
        if(flag){
            $(".btn-search").trigger("click");
        }
    });
}

// /全选用户
$(".list").on("click",'.all-select',CheckAll);
function CheckAll(){
    var table = $(this).parents('table');
    if($(this).is(":checked")){
        table.find("tbody :checkbox").each(function () {
            $(this).prop("checked",true);
        });
    }else{
        table.find("tbody :checkbox").each(function () {
            $(this).prop("checked",false);
        });
    }
}

//全选复选框的选中与否
$(".task-table").on("click",".order-checked",allchk);

function allchk(){
    var chknum = $(".task-table .order-checked").size();
    var chk = 0;
    $(".task-table ").find(".order-checked").each(function () {
        if($(this).is(":checked")){
            chk++;
        }
    });
    if(chknum == chk){
        $(".list thead .all-select").prop("checked",true);
    }else{
        $(".list thead .all-select").prop("checked",false);
    }
}

//用户个体全部结算
$(".list").on("click",".unit-settlement-all",personalSettled);
function personalSettled() {
    var data = [];
    data.push($(this).parents("tr").find("td:eq(0)").find("input[type=checkbox]").val());
    var flag = false;
    var targetUrl = $(this).attr("url");
    wom_alert.confirm({
        content:"是否确认全部结算当前用户?"
    },function () {
        $.ajax({
            url : targetUrl,
            type : "get",
            data : {ids:data},
            success : function (res) {
                var msg = jQuery.parseJSON(res);
                wom_alert.msg({
                    content:msg.message,
                    delay_time:1000
                });
                if(msg.code == 0){
                    flag = true;
                }else {
                    flag = false;
                }
            },
            async:false
        });
        if(flag){
            $(".btn-search").trigger("click");
        }
    })
}


$(function () {
    // layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });

    // 条件筛选
    $(".condition-selected span").on("click",function () {
        $(this).addClass("on").siblings().removeClass("on");
        $(this).parents(".condition-selected").siblings().children("input").val("");
        $(".btn-search").trigger("click");
    })


    //限制收款金额和手机号输入数字
    $(".my-panel .amount-money-collection input").on("keyup",function() {
        numRestraint($(this));
    });
    // $("input[name=max_amount]").on("keyup",function() {
    //     numRestraint($(this));
    // })
    $(".search-input").on("keyup",function() {
        numRestraint($(this));
    });
    // 限制输入数字
    function numRestraint(_this_con){
        _this_con.val( _this_con.val().replace(/\D/g, ''));
    }


    // 搜索
    $(".btn-search").on("click",function () {
        var resent_settle_time_start = $(".my-panel .settlement-time").find("input:eq(0)").val();
        var resent_settle_time_end = $(".my-panel .settlement-time").find("input:eq(1)").val();

        var wait_revenue_start = $(".my-panel").find(".amount-money-collection").find("input:eq(0)").val();
        var wait_revenue_end = $(".my-panel").find(".amount-money-collection").find("input:eq(1)").val();

        var phone = $(".my-panel .search-area").find(".search-input").val();

        if(resent_settle_time_start != '' || resent_settle_time_end != ''){
            $(".settlement-time-selected span").removeClass("on");
        }else {
            var recent_settle_time = $(".settlement-time-selected .on").attr("time");
            $(".settlement-time-selected").find("input[name='ListFilterForm[recent_settle_time_ago]']").val(recent_settle_time);
        }

        if(wait_revenue_start != '' || wait_revenue_end != ''){
            $(".amount-money-selected span").removeClass("on");
            $(".amount-money-selected").find("input[name='ListFilterForm[min_wait_revenue]']").val(wait_revenue_start);
            $(".amount-money-selected").find("input[name='ListFilterForm[max_wait_revenue]']").val(wait_revenue_end);
        }else{
            wait_revenue_start = $(".amount-money-selected .on").attr("min");
            wait_revenue_end = $(".amount-money-selected .on").attr("max");
            $(".amount-money-selected").find("input[name='ListFilterForm[min_wait_revenue]']").val(wait_revenue_start);
            $(".amount-money-selected").find("input[name='ListFilterForm[max_wait_revenue]']").val(wait_revenue_end);
        }

        // if(phone != ''){
        //     var preg = /^1[34578]\d{0,9}$/;
        //     if(!preg.test(phone)){
        //         wom_alert.msg({
        //             content:"手机号码不正确！",
        //             delay_time:1500
        //         });
        //         return false;
        //     }
        // }
    });



    // 表格排序
    $(".sort").on("click",function(){
        var this_up = $(this).find(".up"),
            this_down = $(this).find(".down"),
            siblings_up = $(this).siblings().find(".up"),
            siblings_down = $(this).siblings().find(".down");

        if(this_down.hasClass('down-choosed')){
            this_up.addClass("up-choosed");
            this_down.removeClass("down-choosed");
            siblings_up.removeClass("up-choosed");
            siblings_down.removeClass("down-choosed");
            return false;
        }else{
            this_up.removeClass("up-choosed");
            this_down.addClass("down-choosed");
            siblings_up.removeClass("up-choosed");
            siblings_down.removeClass("down-choosed");
        }
    });

    // 判断任务数量
    calculateTaskAccount();
    function calculateTaskAccount() {
        var task_account = $('.table tbody').find('tr').length;
        if(task_account < 1){
            $('.no-task').show();
        }else{
            $('.no-task').hide();
        }

    }

})