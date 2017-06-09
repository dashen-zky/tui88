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
        var cell_phone_reg = /^1[34578]\d{9}$/;
        var this_tips = $(this).siblings(".tips");
        if (phone == "") {
            this_tips.addClass("show").text("请输入手机号码");
        } else if (!cell_phone_reg.test(phone)) {
            this_tips.addClass("show").text("手机号码格式不正确");
        }else{
            this_tips.removeClass("show");
        }
    });
    $('.verifyCode').on('blur', function () {
        var verifyCode = $.trim($(this).val());
        var this_tips = $(this).siblings(".tips");
        if (verifyCode == "") {
            this_tips.addClass("show").text("请输入图片验证码");
        }else{
            this_tips.removeClass("show");
        }
    });
    // 获取验证码
    $(".get-code").on("click", function () {
        var phone = $.trim($(".cell-phone").val());
        var verifyCode = $.trim($(".verifyCode").val());
        var phone_reg = /^1[34578]\d{9}$/;
        var phone_tips = $(".cell-phone").siblings(".tips");
        if (phone == "") {
            phone_tips.addClass("show").text("请输入手机号码");
            return false;
        } else if (!phone_reg.test(phone)) {
            phone_tips.addClass("show").text("手机号码格式不正确");
            return false;
        } else{
            var flag = false;
            if($(".verifyCode").val() == ''){
                $(".verifyCode").siblings(".tips").addClass("show").text("请填写图片验证码");
                return false;
            }
            $(".verifyCode").siblings(".tips").removeClass("show");
            // 发送短信 接受验证码
            $.ajax({
                url : "/index.php?r=site/send-message",
                type :"post",
                data : {phone:$(".cell-phone").val(),verify:$(".verifyCode").val(),method:"register"},
                success : function (res) {
                    var data = jQuery.parseJSON(res);
                    if(data.code == 1){
                        flag = false;
                        $(".cell-phone").siblings(".tips").addClass("show").text(data.message);
                    }else if(data.code == 2){
                        flag = false;
                        $(".verifyCode").siblings(".tips").addClass("show").text(data.message);
                    }else if(data.code == 0){
                        $(".cell-phone").siblings(".tips").removeClass("show");
                        $(".verifyCode").siblings(".tips").removeClass("show");
                        flag = true;
                    }
                },
                async : false
            });

            if (flag){
                phone_tips.removeClass("show");
                var whole_time = 60,active_time = 1;
                $(this).html("重新发送");
                $(this).hide();
                $(".unclick").show();
                var timer = setInterval(update, 1000);
            }

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
        var flag = true;
        $(".input-group").children("input").each(function(){
            if($(this).val() == "") {
                $(this).siblings(".tips").addClass("show");
                flag = false;
                return false;
            }
        });
        if(!flag){
            return false;
        }
        if(verify_code_val == ""){
            $(".code-insure").children(".tips").removeClass("show");
            return false;
        }
        if(agree_wom < 1){
            $(".agree_serve").addClass("show");
            return false;
        }else{
            $(".agree_serve").removeClass("show");
        }

        if($(".show").length != 0){
            return false;
            // wom_alert.msg({
            //     content:"恭喜您注册成功!",
            //     delay_time:1000
            // })
        }
    });

    $(".btn-agree").on("click",function () {
        $(".tui88-service").trigger("click");
    })

    $(".tui88-service").on("click",function () {
        if($(this).is(":checked")){
            $(".insure-regist").removeAttr("disabled");
        }else {
            $(".insure-regist").attr("disabled","disabled");
        }
    });


});