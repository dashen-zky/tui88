$(function () {
    //layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });
    //手机号码
    $('.cell-phone').on('blur', function () {
        var phone = $.trim($(this).val());
        var cell_phone_reg = /^[1-9]\d{10}$/;
        var this_tips = $(this).siblings(".tips");
        if (phone == "") {
            this_tips.addClass("show").text("请输入手机号码");
        } else if (!cell_phone_reg.test(phone)) {
            this_tips.addClass("show").text("手机号码格式不正确");
        }else{
            this_tips.removeClass("show");
        }
    });
    // 获取验证码
    $(".get-code").on("click", function () {
        var phone = $.trim($(".cell-phone").val());
        var phone_reg = /^[1-9]\d{10}$/;
        var this_tips = $(".cell-phone").siblings(".tips");
        if (phone == "") {
            this_tips.addClass("show").text("请输入手机号码");
            return false;
        } else if (!phone_reg.test(phone)) {
            this_tips.addClass("show").text("手机号码格式不正确");
            return false;
        } else{
            $(".cell-phone").siblings(".tips").removeClass("show");
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
            timer = setInterval(update, 1000);
        }
    });

    //密码
    $('.psd').on("blur", function () {
        var psd_val = $.trim($(this).val());
        var psd_reg = /^[a-zA-Z0-9]{6,20}$/;
        if (psd_val == "") {
            $(this).siblings(".tips").addClass("show").text("请输入密码");
        } else if (psd_val.length < 6 || psd_val.length > 20) {
            $(this).siblings(".tips").addClass("show").text("密码长度要求6-20位");
        } else if (!psd_reg.test(psd_val)) {
            $(this).siblings(".tips").addClass("show").text("密码由6-20位的字母或数字组成");
        } else {
            $(this).siblings(".tips").removeClass("show");
        }
    });
    // 确认密码
    $('.insure-psd').on('blur', function () {
        var insure_psd_val = $.trim($(this).val());
        var psd_val = $.trim($(".psd").val());
        if (insure_psd_val == '') {
            $(this).siblings(".tips").addClass("show").text("请输入确认密码");
        } else if (insure_psd_val != psd_val) {
            $(this).siblings(".tips").addClass("show").text("两次密码不一致");
        } else {
            $(this).siblings(".tips").removeClass("show");
        }
    });

    //填写验证码
    $(".verify-code").on("blur", function () {
        var verify_code = $.trim($(this).val());
        var this_tips = $(this).siblings(".tips");
        if (verify_code == '') {
            this_tips.addClass("show").text("请输入验证码");
        } else {
            this_tips.removeClass("show");
        }
    });

    //确认注册
    $(".insure-regist").on("click",function(){
        var verify_code_val = $.trim($(".verify-code").val());
        var agree_wom = $("input:checked").length;
        $(".input-group").children("input").each(function(){
            if($(this).val() == "") {
                $(this).siblings(".tips").addClass("show");
            }
        })
        if(verify_code_val != ""){
            $(".code-insure").children(".tips").removeClass("show");
        }
        if(agree_wom < 1){
            $(".agree_serve").addClass("show");
        }else{
            $(".agree_serve").removeClass("show");
        }
        if($(".show").length == 0){
            wom_alert.msg({
                icon: "finish",
                content:"恭喜您注册成功!",
                delay_time:1500
            })
        }
    })


});