// 终止任务
$(".operation").on("click",".btn-stop",TerminateTask);
function TerminateTask() {
    var _this = $(this);
    var flag = false;
    wom_alert.confirm({
        content:"是否确认停止该任务？(停止后将不可恢复)"
    },function () {
        $.ajax({
            url : _this.attr("url"),
            type : "get",
            success : function (res) {
                var data = jQuery.parseJSON(res);
                wom_alert.msg({
                    content:data.message,
                    delay_time:1500
                });
                if (data.code == 0){
                    flag = true;
                    var html = _this.parents("tr").find("td:eq(1)").find("a").html();
                    _this.parents("tr").find("td:eq(1)").find("a").html('【异常】' + html);
                    _this.parents("tr").find("td:eq(3)").html('已结束');
                    _this.parents("tr").find("td:eq(4)").html('已结束');
                    _this.remove();
                }else {
                    flag = false;
                }
            },
            async : false
        });

       return flag;

    })
}

// 删除任务
$(".operation").on("click",".btn-delete",DeleteTask);
function DeleteTask() {
    var targetUrl = $(this).attr("url");
    wom_alert.confirm({
        content:"是否确认删除该任务？"
    },function () {
        $.ajax({
            url : targetUrl,
            type : "get",
            success : function (res) {
                var data = jQuery.parseJSON(res);
                wom_alert.msg({
                    content:data.message,
                    delay_time:1000
                });
                if(data.code == 0){
                    setTimeout("location.reload()",1000);
                }
            },
            async : false
        });

    })

}
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
        var status = $(this).attr("data-status");
        $(this).parent().prev().find('span:eq(0)').text(_text);
        $(this).parent().next("input").val(status);

    }




    // 搜索
    $(".btn-search").on("click",function () {
        var search_con = $(".search-input").val();
        var publish_start_time = $(".publish-time").children("input[name='publish_start_time']").val();
        var publish_end_time = $(".publish-time").children("input[name='publish_end_time']").val();


    });

    // 删除任务
    $(".btn-delete").on("click",function () {
        var this_task = $(this).parents("tr");
        // wom_alert.confirm({
        //     content:"是否确认删除该任务？"
        // },function () {
        //     this_task.remove();
        //     calculateTaskAccount();
        //     wom_alert.msg({
        //         content:"删除成功!",
        //         delay_time:1500
        //     })
        // })

    });


    calculateTaskAccount();
    // 判断任务数量
    function calculateTaskAccount() {
        var task_account = $('.table tbody').find('tr').length;
        if(task_account < 1){
            $('.no-task').show();
        }else{
            $('.no-task').hide();
        }
        // if(task_account < 20){
        //     $('.page-wb').hide();
        // }else{
        //     $('.page-wb').show();
        // }
    }

})