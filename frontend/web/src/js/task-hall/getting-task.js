$(".operate").on("click",".click",gettingTask);
$("#confirm-get-task").on("click",".btn-sure",gettingTask);
$("#add-collection-info").on("click",".btn-submit",addBankCard);
function gettingTask() {
    if ($(this).attr("data-target") == "#add-collection-info") {
        $(this).parents("tbody").find(">tr").removeClass("select-flag");
        $(this).parents("tr").addClass("select-flag");
        return;
    }

    var my_method = $(this).attr("my-method");
    if(my_method != 'check' && my_method != 'getting'){
        wom_alert.msg({
            content: "系统故障，刷新后再试",
            delay_time: 1000
        });
        return false;
    }
    var data = {};

    var _this = $(this);
    var url = $(this).attr("url");
    if(my_method == 'getting'){
       url += "&get_task_num="+$(".get-task-number").val();
    }
    $.ajax({
        url: url,
        type: "get",
        async: false,
        success: function (res) {
            data = jQuery.parseJSON(res);
        }
    });

    if(my_method == 'check'){
        if(data.code == 0){
            var title = _this.parents("tr").find("td:eq(0)").html();
            $("#confirm-get-task .task-name span").html(title);
            $("#confirm-get-task .btn-sure").attr("url",data.url);
            $("#confirm-get-task .btn-sure").attr("my-method",data["my_method"]);
            $("#confirm-get-task .max-task-num").html(data["max_get_num"]);
            _this.parents("tbody").find(">tr").removeClass("select-flag");
            _this.parents("tr").addClass("select-flag");
            return true;
        }
    }
    wom_alert.msg({
        content: data.message,
        delay_time: 1000
    });
    if(data.code != 0){
        return false;
    }
    // var remain_tr = _this.parents("body").find(".list").find("tbody").find(".select-flag");
    //
    // var num = remain_tr.find("td:eq(3)").attr("value");
    // if (0 < num - 1) {
    //     remain_tr.find("td:eq(3)").attr("value", num - 1);
    //     remain_tr.find("td:eq(3)").html(num - 1);
    // } else {
    //     remain_tr.remove();
    // }
    $(".ListFilterForm .btn-search").trigger("click");
}

function CheckQualificationSelf() {
    if ($(this).attr("data-target") == "#add-collection-info") {
        $(this).parents("tbody").find(">tr").removeClass("select-flag");
        $(this).parents("tr").addClass("select-flag");
        return;
    }

    var targetUrl = $(this).attr("url");
    var _this = $(this);
    var flag = false;
    $.ajax({
        url: targetUrl,
        type: "get",
        async: false,
        success: function (res) {
            var data = jQuery.parseJSON(res);
            if(data.code == 0){
                flag = true;
                var title = _this.parents("tr").find("td:eq(0)").html();
                $("#confirm-get-task .task-name span").html(title);
                $("#confirm-get-task .btn-sure").attr("url",data.url);
                _this.parents("tbody").find(">tr").removeClass("select-flag");
                _this.parents("tr").addClass("select-flag");
            }else {
                flag = false;
                wom_alert.msg({
                    content: data.message,
                    delay_time: 1000
                });
            }
        }
    });
    return flag;

}

function addBankCard() {
    var targetUrl = $(this).parents("form").attr("action");
    var formData = $(this).parents("form").serialize();
    var msg = {};
    $.ajax({
        url:targetUrl,
        type:'post',
        data:formData,
        success:function (res) {
            msg = jQuery.parseJSON(res);
        },
        async:false
    });

    wom_alert.msg({
        content: msg.message,
        delay_time: 1000
    });
    if(msg.code == 0){
        $(".list .operate .click").attr("data-target","#confirm-get-task");
        var title = $(".select-flag").find("td:eq(0)").html();
        $("#confirm-get-task").find(".task-name").find("span:eq(0)").html(title);
        $("#add-collection-info").modal("hide");
        return true;
    }
    return false;

}

