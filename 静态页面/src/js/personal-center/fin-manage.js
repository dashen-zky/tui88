$(function () {
    //layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });
    //搜索
    $(".btn-search").on('click',function () {
        var collection_start_time = $("input[name = 'collection_start_time']").val();
        var collection_end_time = $("input[name = 'collection_end_time']").val();
        var min_price = $(".min-price").val();
        var max_price = $(".max-price").val();

        if(min_price != "" && max_price != ""){
            if(max_price <= min_price){

            }
        }

    })

   // 相关任务展示
    $(".task-relevant").on("click",function () {
        var this_dot = $(this).parents(".collection-item-show").find(".dot");
        var this_icon = $(this).children("i");
        var this_tab = $(this).parents(".collection-item-show").siblings(".collection-item-hide");
        var other_item = $(this).parents(".collection-item").siblings();
        var other_dot = other_item.find(".dot");
        var other_icon = other_item.find("i");
        var other_tab = other_item.find(".collection-item-hide");

        if(this_icon.hasClass("rotate")){
            this_dot.removeClass("light");
            this_icon.removeClass("rotate");
            this_tab.slideUp(200);
            return false;
        }
        this_dot.addClass("light");
        this_icon.addClass("rotate");
        this_tab.slideDown(200);
        other_dot.removeClass("light");
        other_icon.removeClass("rotate");
        other_tab.slideUp(200);


        // 相关任务分页
        var this_table = $(this).parents(".collection-item-show").siblings(".collection-item-hide").find("table");
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
        // displayPage();//显示第一页
        this_prev.click(function(){
            direct = -1;
            displayPage();
        });
        this_next.click(function(){
            direct = 1;
            displayPage();
        });
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
    })




    // 判断收款记录数量
    $(document).ready(function () {
        var collection_record = $('.collection-item').length;
        if(collection_record < 1){
            $('.no-collection-record').show();
        }else{
            $('.no-collection-record').hide();
        }
        if(collection_record < 20){
            $('.page-wb').hide();
        }else{
            $('.page-wb').show();
        }
    })
})
