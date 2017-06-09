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
    })


    //限制收款金额和手机号输入数字
    $("input[name=min_amount]").on("keyup",function() {
        numRestraint($(this));
    })
    $("input[name=max_amount]").on("keyup",function() {
        numRestraint($(this));
    })
    $(".search-input").on("keyup",function() {
        numRestraint($(this));
    })
    // 限制输入数字
    function numRestraint(_this_con){
        _this_con.val( _this_con.val().replace(/\D/g, ''));
    }


    // 搜索
    $(".btn-search").on("click",function () {
        var settlement_start_time = $(".settlement-time").children("input[name=settlement_start_time]").val(),
            settlement_end_time = $(".settlement-time").children("input[name=settlement_end_time]").val(),
            min_amount = parseInt($("input[name=min_amount]").val()),
            max_amount = parseInt($("input[name=max_amount]").val()),
            phone_num = $(".search-input").val(),
            phone_reg = /^[1-9]\d{10}$/;

        if (max_amount < min_amount){
            wom_alert.msg({
                content: "请输入正确的待收款金额范围！",
                delay_time: 1500
            })
            return false;
        }
        if(phone_num != "" && !phone_reg.test(phone_num)){
            wom_alert.msg({
                content:"手机号码格式不正确!",
                delay_time:1500
            })
            return false;
        }
        if(settlement_start_time != "" && settlement_end_time !=""){
            $(".settlement-time-selected span").removeClass("on");
        }
        if(!isNaN(min_amount) && !isNaN(max_amount)){
            $(".amount-money-selected span").removeClass("on");
        }
    })

    //全选用户
    var check_all = $("thead input");
    var check_single = $("tbody :checkbox");

    check_all.on("click",function(){
        if(this.checked){
            check_single.prop("checked",true);
        }else{
            check_single.prop("checked",false);
        }
    })
    //全选复选框的选中与否
    check_single.click(function(){
        allchk();
    });
    function allchk(){
        var chknum = check_single.size();
        var chk = 0;
        check_single.each(function () {
            if($(this).prop("checked") == true){
                chk++;
            }
        });
        if(chknum == chk){
            check_all.prop("checked",true);
        }else{
            check_all.prop("checked",false);
        }
    }


    // 全部结算
    $(".settlement-all").on("click",function () {
        var amount_choosed = $("tbody input:checked").length;
        if(amount_choosed < 1){
            wom_alert.msg({
                content:"请先选择用户！",
                delay_time:1500
            })
           return;
        }
        wom_alert.confirm({
            content:"是否确认全部结算？"
        },function () {
            wom_alert.msg({
                content:"结算成功!",
                delay_time:1000
            })
        })
    })

    //用户个体全部结算
    $(".unit-settlement-all").on("click",function () {
        
    })

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
    })

    // 判断任务数量
    calculateTaskAccount();
    function calculateTaskAccount() {
        var task_account = $('.table tbody').find('tr').length;
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
    }

})