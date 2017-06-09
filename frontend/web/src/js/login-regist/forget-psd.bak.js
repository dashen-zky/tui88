$(function(){
    //layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });
    var flag = true;
    //手机号码
    $('.cell-phone').on("blur",function(){
        var cell_phone_val = $.trim($(this).val());
        var cell_phone_reg= /^1[34578]\d{9}$/;
        if(cell_phone_val == ""){
            $(this).siblings(".tips").addClass("show").text("请输入手机号码");
            return false;
        }else if(!cell_phone_reg.test(cell_phone_val)){
            $(this).siblings(".tips").addClass("show").text("请输入正确的手机号");
            return false;
        }else{
            $(this).siblings(".tips").removeClass("show");
            return true;
        }
    })
    $(".verify-code").on("blur",function(){
        if($(this).val() == ""){
            $(this).siblings(".tips").addClass("show");
            return false;
        }
        $(this).siblings(".tips").removeClass("show");
        return true;
    })

    //获取验证码
    $(".get-code").on("click",function(){
        // var cell_phone_val = $.trim($(".cell-phone").val());
        // var cell_phone_reg= /^1[34578]\d{9}$/;
        // if(cell_phone_val == ""){
        //     $(".cell-phone").siblings(".tips").addClass("show");
        //     return false;/
        // }else if(!cell_phone_reg.test(cell_phone_val)){
        //     $(".cell-phone").siblings(".tips").addClass("show").text("请输入正确的手机号");
        //     return false;
        // } else{
        var flag = $(".cell-phone").trigger("blur");
        // console.log(flag);return false;
        if(flag){
            // $(".cell-phone").siblings(".tips").removeClass("show");
            var whole_time = 60,active_time = 1;
            $(this).hide();
            $(".unclick").show();
            function update() {
                if (active_time == whole_time) {
                    $(".unclick").hide();
                    $(".get-code").show();
                    clearInterval(timer);
                    surplus_time = 60;
                    $(".unclick").children("i").text(surplus_time);
                    return false;
                } else {
                    var surplus_time = whole_time - active_time;
                    $(".unclick").children("i").text(surplus_time);
                }
                active_time++;
            }
            // 发送短信 接受验证码
            $.post('/index.php?r=site/send-message',{phone:$(".cell-phone").val()},function (data){
                console.log(data);
            });
            timer = setInterval(update, 1000);
        }
    })

    //下一步
    $(".next-step").on("click",function(){
        $("#step-one .input-group").children("input").each(function(){
            if($(this).val() == "") {
                $(this).siblings(".tips").addClass("show");
                $(this).focus();
                flag = false;
                return false;
            }
        })

        var data = {_csrf:$("meta[name=csrf-token]").attr("content"),FindPsdForm:{phone:$(".cell-phone").val(),verify:$(".verify-code").val()}};
        var targetUrl = $(this).attr("src");
        if(flag){
            $.post(targetUrl,data,function (res){
                var data = jQuery.parseJSON(res);
                if(data.status == 0){
                    $(".step-con .step-two .line").addClass("bg-on-step");
                    $(".step-con .step-two .ball").addClass("bg-on-step");
                    $(".step-con .step-two .func").addClass("color-on-step");
                    $("#step-one").removeClass("show").addClass("hide");
                    $("#step-two").removeClass("hide").addClass("show");
                }else if(data.status == 1){
                    console.log(data);
                }

            });
        }


    })

    //重置密码
    //密码
    $('.psd').on("blur",function(){
        var psd_val = $.trim($(this).val());
        var psd_reg= /^[a-zA-Z0-9]{6,20}$/;
        if(psd_val == ""){
            $(this).siblings(".tips").addClass("show").text("请输入密码");
        }else if( psd_val.length < 6 ||  psd_val.length >20){
            $(this).siblings(".tips").addClass("show").text("密码长度要求6-20位");
        }else if(!psd_reg.test(psd_val)){
            $(this).siblings(".tips").addClass("show").text("密码必须由数字和字母组成");
        }else{
            $(this).siblings(".tips").removeClass("show");
        }
    })

    //确认密码
    $('.insure-psd').on("blur",function(){
        var insure_psd_val = $.trim($(this).val());
        var psd_val = $.trim($(".psd").val());
        if(insure_psd_val == ""){
            $(this).siblings(".tips").addClass("show").text("请确认密码");
        }else if( insure_psd_val != psd_val){
            $(this).siblings(".tips").addClass("show").text("两次密码不一致");
        }else{
            $(this).siblings(".tips").removeClass("show");
        }
    })

    //确认重置密码
    $(".affirm-submit").on("click",function(){
        var verify_code_val = $.trim($(".verify-code").val());
        $(".input-group").children("input").each(function(){
            if($(this).val() == "") {
                $(this).siblings(".tips").addClass("show");
            }
        })
        if(verify_code_val != ""){
            $(".code-insure").children(".tips").removeClass("show");
        }
        if($(".show").length == 0){
            wom_alert.msg({
                icon: "finish",
                content:"密码重置成功!",
                delay_time:1500
            })
        }
    })
    
    function validatePhone() {
        
    }

})