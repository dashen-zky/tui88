//全选用户
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
    countTotalNum($(".order-checked"));
}

//全选复选框的选中与否
$(".task-table").on("click",".order-checked",function(){
    allchk();
    countTotalNum();
});

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
//计算总金额
function countTotalNum(_this_order) {
    var total_num_area = $(".order-num"),
        _this_order = $(".order-checked"),
        total_num = 0,
        total_price = 0;
    _this_order.each(function () {
        if($(this).is(":checked")){
            var single_price = Number($(this).parents("tr").find("td:eq(5)").text());
            total_price += single_price;
        }

    });
    total_num = $("tbody input:checked").length;
    $(".order-num").text(total_num);
    $(".total-num").text(parseFloat(total_price).toFixed(2));
}

$(function () {
    // layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });

    // 搜索
    $(".btn-search").on("click",function () {
        var confirm_start_time = $(".confirm-time").children("input[name=confirm_start_time]").val(),
            confirm_end_time = $(".confirm-time").children("input[name=confirm_end_time]").val(),
            search_con = $(".input-search").val();


    });

    //结算
    $(".btn-settlement").on("click",function () {
        var amount_choosed = $("tbody input:checked").length;
        if(amount_choosed < 1){
            wom_alert.msg({
                content:"请先选择订单！",
                delay_time:1500
            });
            return;
        }

        var targetUrl = $(this).attr("url");
        var data = [];
        $("tbody input:checked").each(function (i) {
            if($(this).is(":checked")){
                data.push($(this).val());
            }
        });
        wom_alert.confirm({
            content:"是否确认全部结算？" }, function () {
                $.ajax({
                    url: targetUrl,
                    type: "get",
                    data : {data:data},
                    success: function (res) {
                        var msg = jQuery.parseJSON(res);
                        if(msg.code == 0){
                            wom_alert.msg({
                                content:msg.message,
                                delay_time:1000
                            });
                            $(".btn-search").trigger("click");
                        }else{
                            wom_alert.msg({
                                content:msg.message,
                                delay_time:1000
                            })
                        }

                    },
                    async : false
                });

            })
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