$(".list").on("click",".task-relevant",getPaymentRecord);
function getPaymentRecord() {
    var _this = $(this);
    var targetUrl = $(this).attr("url");
    var flag = false;

    var this_dot = _this.parents(".collection-item-show").find(".dot");
    var this_icon = _this.children("i");
    var this_tab = _this.parents(".collection-item-show").siblings(".collection-item-hide");

    if(this_icon.hasClass("rotate")){
        this_dot.removeClass("light");
        this_icon.removeClass("rotate");
        this_tab.slideUp(200);
        return false;
    }
    $.ajax({
        url : targetUrl,
        type : 'get',
        success : function (res) {
            var data = jQuery.parseJSON(res);
            if(data.code == 0){
                flag = true;
                _this.parents(".collection-item-show").siblings(".collection-item-hide").html(data["html"]);
            }else {
                flag = false;
                wom_alert.msg({
                    content: data.message,
                    delay_time: 1500
                })
            }
        },
        async : false
    });

    taskPagination(_this);
    return flag;
}


// 相关任务展示
function taskPagination(_this) {
    var this_dot = _this.parents(".collection-item-show").find(".dot");
    var this_icon = _this.children("i");
    var this_tab = _this.parents(".collection-item-show").siblings(".collection-item-hide");
    var other_item = _this.parents(".collection-item").siblings();
    var other_dot = other_item.find(".dot");
    var other_icon = other_item.find("i");
    var other_tab = other_item.find(".collection-item-hide");

    if(this_icon.hasClass("rotate")){
        this_icon.removeClass("rotate");
        this_tab.slideUp(200);
        return false;
    }
    this_icon.addClass("rotate");
    this_tab.slideDown(200);
    other_icon.removeClass("rotate");
    other_tab.slideUp(200);


    // 相关任务分页
    var this_table = _this.parents(".collection-item-show").siblings(".collection-item-hide").find("table");
    var this_prev = this_table.siblings(".page-change").find(".prev");
    var this_next = this_table.siblings(".page-change").find(".next");
    var pageSize = 10;//每页显示的记录条数
    var curPage = 0;
    var lastPage;
    var direct = 0;
    var len;
    var page;

    len = this_table.find("tbody tr").length;
    page = len % pageSize == 0 ? len/pageSize : Math.floor(len/pageSize) + 1;//根据记录条数，计算页数
    curPage = 1;
    this_prev.click(function(){
        direct = -1;
        displayPage();
    });
    this_next.click(function(){
        direct = 1;
        displayPage();
    });

    if(len < 20){
        this_prev.hide();
        this_next.hide();
    }else{
        this_prev.show();
        this_next.show();
    }

    function displayPage(){

        if(curPage <= 1 && direct== -1){
            direct = 0;
            wom_alert.msg({
                content:"已经是第一页了",
                delay_time:1000
            })
            return false;
        }

        if (curPage >= page && direct==1) {
            direct = 0;
            wom_alert.msg({
                content:"已经是最后一页了",
                delay_time:1000
            })
            return false;
        }

        lastPage = curPage;
        curPage = (curPage + direct + len) % len;
        var begin = (curPage - 1) * pageSize;//起始记录号
        var end = begin + pageSize;
        if(end > len ) end = len;
        this_table.find("tbody tr").hide();
        this_table.find("tbody tr").each(function(i){
            if(i >= begin && i < end)//显示第page页的记录
                $(this).show();
        });
    }
}

$(".list").on("click",".paying-voucher",getPaymentInfo);
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
                $("#paying-voucher").html(data["html"]);
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
    //layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });

    //侧边栏导航高度的显示
    var _h = $('.main').height();
    $('.sidebar').height(_h + 30);
    //搜索
    $(".btn-search").on('click',function () {
        var collection_start_time = $("input[name = 'collection_start_time']").val();
        var collection_end_time = $("input[name = 'collection_end_time']").val();
        var min_price = $(".min-price").val();
        var max_price = $(".max-price").val();
    });


    // 判断收款记录数量
    $(document).ready(function () {
        var collection_record = $('.collection-item').length;
        if(collection_record < 1){
            $('.no-collection-record').show();
        }else{
            $('.no-collection-record').hide();
        }
    })

    $(".min-price").on("keyup",function() {
        numRestraint($(this));
    });
    $(".max-price").on("keyup",function() {
        numRestraint($(this));
    });
    // 限制输入数字
    function numRestraint(_this_con){
        _this_con.val( _this_con.val().replace(/\D/g, ''));
    }

    //绑定enter键
    $('.max-price').bind('keypress',function(event){
        if(event.keyCode == "13") {
            $('.ListFilterForm .submit').trigger("click");
        }
    });
});


