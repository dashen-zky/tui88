$(".operate").on("click",".get-payment-info",getPaymentInfo);
function getPaymentInfo() {
    var _this = $(this);
    var flag = false;
    $.ajax({
        url: _this.attr("url"),
        type: "get",
        success: function (res) {
            var data = jQuery.parseJSON(res);
            if(data.code == 0){
                flag = true;
                $("#proof").html(data["html"]);
            }else {
                wom_alert.msg({
                    content: data.message,
                    delay_time: 1500
                })
            }
        },
        async: false
    });
    return flag;
}

$(function () {
    // layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });

    //限制收款金额和手机号输入数字
    $("input[name=min_amount]").on("keyup",function() {
        numRestraint($(this));
    })
    $("input[name=max_amount]").on("keyup",function() {
        numRestraint($(this));
    })
    // 限制输入数字
    function numRestraint(_this_con){
        _this_con.val( _this_con.val().replace(/\D/g, ''));
    }

    // 搜索
    $(".btn-search").on("click",function () {
        var settlement_start_time = $(".settlement-time").children(".paid-start-time").val(),
            settlement_end_time = $(".settlement-time").children(".paid-end-time").val(),
            min_amount = parseInt($(".min-amount").val()),
            max_amount = parseInt($(".max-amount").val()),
            search_con = $(".search-input").val();

        if (max_amount < min_amount) {
            wom_alert.msg({
                content: "请输入正确的结算金额范围！",
                delay_time: 1500
            })
            return false;
        }
        if (settlement_start_time != "" && settlement_end_time != "") {
            $(".settlement-time-selected span").removeClass("on");
        }
        if (!isNaN(min_amount) && !isNaN(max_amount)) {
            $(".amount-money-selected span").removeClass("on");
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
    }
})