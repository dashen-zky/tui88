$(function () {
    //layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });
  //选择下拉单
  $('.dropdown .dropdown-menu').on('click','li',selectedOption);
  //下拉单选择某一个
  function selectedOption(){
      var _text = $(this).text();
      $(this).parent().prev().find('span:eq(0)').text(_text);
  }

    //固定modal
    // $("#task-has-get").modal({backdrop: "static", keyboard: false});


    //搜索分类
    $(".category li").on("click",function(){
        var sort_up = $(this).children(".sort-icon").find(".up");
        var sort_down = $(this).children(".sort-icon").find(".down");
        var other_sort_up = $(this).siblings().children(".sort-icon").find(".up");
        var other_sort_down = $(this).siblings().children(".sort-icon").find(".down");

        $(this).addClass("choosed").siblings().removeClass("choosed");
        sort_up.addClass("li-up-choosed");
        sort_down.addClass("down-choosed");
        other_sort_up.removeClass("li-up-choosed");
        other_sort_down.removeClass("down-choosed");
    })


    //补充收款信息
    $(".btn-submit").on("click",function(){
        var bank_name = $(".bank-name").val();
        var card_number = $(".card-number").val();
        if(bank_name == ""){
            wom_alert.msg({
                icon: "warning",
                content:"请填写所属银行！",
                delay_time:1500
            })
            return false;
        }
        if(card_number == ""){
            wom_alert.msg({
                icon: "warning",
                content:"请填写银行卡号！",
                delay_time:1500
            })
            return false;
        }
    })













    //搜索任务
    $(".btn-search").on("click",function(){
        var min_price = $(".min-price").val();
        var max_price = $(".max-price").val();
        var task_name = $(".input-search-task").val();
        var get_status = $("select option:selected").val();

        if (isNaN(min_price)||isNaN(max_price)){
            wom_alert.msg({
                icon: "warning",
                content:"请输入有效数字",
                delay_time:1500
            })
            return false;
        }
        if(min_price != "" && max_price != ""){
            if (max_price <= min_price){
                wom_alert.msg({
                    icon: "warning",
                    content:"请输入有效的单价范围",
                    delay_time:1500
                })
                return false;
            }
        }

    })

})



