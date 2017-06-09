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
        var enable = $(this).attr("data-status");
        $(this).parents("ul").siblings(".enable-status").val(enable);
        if(!$(this).parents(".ListFilterForm").hasClass("order-by-sort")){
            $(this).parents(".ListFilterForm").find(".order-by").val("");
        }
    }

    // 搜索
    $(".btn-search").on("click",function () {
        var regist_start_time = $(".regist-time").children("input[name='regist_start_time']").val(),
            regist_end_time = $(".regist-time").children("input[name='regist_end_time']").val(),
            phone_num = $(".search-input").val(),
            phone_reg = /^1[34578]\d{0,9}$/;

        if(!$(this).parents(".ListFilterForm").hasClass("order-by-sort")){
            $(this).parents(".ListFilterForm").find(".order-by").val("");
        }


    });

    //判断用户数量
    calculateTaskAccount();

    function calculateTaskAccount() {
        var task_account = $('.table tbody').find('tr').length;
        if(task_account < 1){
            $('.no-task').show();
        }else{
            $('.no-task').hide();
        }
    }
});
//拉黑用户 或者 恢复用户
$(".list .operate").on("click",".blacklist,.resume",pullBlackOrRestore);

function pullBlackOrRestore() {
    var _this = $(this);
    if(_this.hasClass("blacklist")){
        var content = "是否确认拉黑该用户？";
        var oldClass = "blacklist";
        var newClass = "resume";
    }else if(_this.hasClass("resume")){
        var content = "是否恢复该用户?";
        var oldClass = "resume";
        var newClass = "blacklist";
    }

    wom_alert.confirm({
        content:content
    },function () {
        $.ajax({
            url : _this.attr("url"),
            success : function (res) {
                var data = jQuery.parseJSON(res);
                wom_alert.msg({
                    content:data.message,
                    delay_time:1000
                });
                if(data.code != 0){
                    return false;
                }
                _this.removeClass(oldClass).addClass(newClass).attr("url",data.url).html(data["operate_cn"]);
                _this.parents("tr").find(".user-status-item").html(data["enable_cn"]);
            },
            type : "get",
            async : false
        });
    })
}

// 排序
$(".list thead").on("click",".sort",Sort);

function Sort() {
    var this_up = $(this).find(".up"),
        this_down = $(this).find(".down"),
        siblings_up = $(this).siblings().find(".up"),
        siblings_down = $(this).siblings().find(".down");
    var sort = null;

    if (this_down.hasClass('down-choosed')) {
        this_up.addClass("up-choosed");
        this_down.removeClass("down-choosed");
        siblings_up.removeClass("up-choosed");
        siblings_down.removeClass("down-choosed");
        sort = this_up.attr("sort");
    } else {
        this_up.removeClass("up-choosed");
        this_down.addClass("down-choosed");
        siblings_up.removeClass("up-choosed");
        siblings_down.removeClass("down-choosed");
        sort = this_down.attr("sort");

    }
    $(".ListFilterForm").addClass("order-by-sort");
    $(".ListFilterForm .order-by").val(sort);
    $(".ListFilterForm .btn-search").trigger("click");
    $(".ListFilterForm").removeClass("order-by-sort");
}
