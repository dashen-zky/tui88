$(function () {
    // layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });

    // 选择下拉单
    $(".dropdown .dropdown-menu").on("click","li",selectedOption);
    function selectedOption(){
        var _text = $(this).text();
        $(this).parent().prev().find('span:eq(0)').text(_text);
    }

    // 搜索
    $(".btn-search").on("click",function () {
        var publish_start_time = $(".regist-time").children("input[name='regist_start_time']").val(),
            publish_end_time = $(".regist-time").children("input[name='regist_end_time']").val(),
            phone_num = $(".search-input").val(),
            phone_reg = /^[1-9]\d{10}$/;

        if(phone_num != "" && !phone_reg.test(phone_num)){
            wom_alert.msg({
                content:"请输入正确的手机号!",
                delay_time:1500
            })
        }
    })

    // 排序
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
    //勿删除，待定
    // $(".up").on("click",function () {
    //     var siblings_up = $(this).parents("th").siblings().find(".up"),
    //         siblings_down = $(this).parents("th").siblings().find(".down");
    //
    //     if($(this).hasClass("up-choosed")){
    //         $(this).removeClass("up-choosed");
    //         return false;
    //     }
    //     $(this).addClass("up-choosed");
    //     siblings_up.removeClass("up-choosed");
    //     siblings_down.removeClass("down-choosed");
    //     $(this).siblings().removeClass("down-choosed");
    // })
    // $(".down").on("click",function () {
    //     var siblings_up = $(this).parents("th").siblings().find(".up"),
    //         siblings_down = $(this).parents("th").siblings().find(".down");
    //     if($(this).hasClass("down-choosed")){
    //         $(this).removeClass("down-choosed");
    //         return false;
    //     }
    //     $(this).addClass("down-choosed");
    //     siblings_up.removeClass("up-choosed");
    //     siblings_down.removeClass("down-choosed");
    //     $(this).siblings().removeClass("up-choosed");
    // })


    //拉黑用户
    $(".blacklist").on("click",function(){
        var _this = $(this),
            user_status = _this.parents("tr").find(".user-status-item");
        wom_alert.confirm({
            content:"是否确认拉黑该用户？"
        },function () {
            user_status.text("黑名单");
            _this.addClass("hide").siblings(".resume").removeClass("hide");
            wom_alert.msg({
                content:"成功拉黑！",
                delay_time:1500
            })
        })
    })

    //恢复
    $(".resume").on("click",function(){
        var _this = $(this),
            user_status = _this.parents("tr").find(".user-status-item");
        wom_alert.confirm({
            content:"是否确认恢复该用户？"
        },function () {
            user_status.text("正常");
            _this.addClass("hide").siblings(".blacklist").removeClass("hide");
            wom_alert.msg({
                content:"成功恢复！",
                delay_time:1500
            })
        })
    })

    //判断用户数量
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