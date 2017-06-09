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

    //结算
    $(".btn-settlement").on("click",function () {
        var amount_choosed = $("tbody input:checked").length;
        if(amount_choosed < 1){
            wom_alert.msg({
                content:"请先选择订单！",
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




    // 判断任务数量
    calculateTaskAccount();
    function calculateTaskAccount() {
        var task_account = $('.table tbody').find('tr').length;
        if(task_account < 1){
            $('.no-task').show();
        }else{
            $('.no-task').hide();
        }
        if(task_account < 50){
            $('.page-wb').hide();
        }else{
            $('.page-wb').show();
        }
    }
})