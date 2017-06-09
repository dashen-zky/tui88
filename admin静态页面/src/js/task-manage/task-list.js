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
        var search_con = $(".search-input").val();
        var publish_start_time = $(".publish-time").children("input[name='publish_start_time']").val();
        var publish_end_time = $(".publish-time").children("input[name='publish_end_time']").val();


    })

    // 删除任务
    $(".btn-delete").on("click",function () {
        var this_task = $(this).parents("tr");
        wom_alert.confirm({
            content:"是否确认删除该任务？"
        },function () {
            this_task.remove();
            calculateTaskAccount();
            wom_alert.msg({
                content:"删除成功!",
                delay_time:1500
            })
        })

    })

    // 终止任务
    $(".btn-stop").on("click",function () {
        wom_alert.confirm({
            content:"是否确认终止该任务？(终止后将不可恢复)"
        },function () {
            wom_alert.msg({
                content:"终止成功!",
                delay_time:1500
            })
        })
    })


    calculateTaskAccount();
    // 判断任务数量
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