$(function () {
    //手机号码
    $('.new-ph-num input').on('blur', function () {
        var _this = $(this);
        var phone_value = $.trim(_this.val());
        var this_tips = _this.siblings('.error-alert');
        checkPhoneNum(_this,phone_value,this_tips);
    });
    // 获取验证码

    var timer = null,
        bclick = false;
    $(".get-code").on("click", function () {
        var _this = $(this);
        var phone_value = $.trim(_this.parents('.modal-body').find('.new-ph-num input').val());
        var this_tips = _this.parents('.modal-body').find('.new-ph-num .error-alert');
        var phone_reg = /^[1]\d{10}$/;
        if (phone_value == "") {
            this_tips.addClass("show").text("请输入手机号码");
            return false;
        } else if (!phone_reg.test(phone_value)) {
            this_tips.addClass("show").text("手机号码格式不正确");
            return false;
        } else{
            _this.siblings(".error-alert").removeClass("show");
        }
        var i = 60;
        if(bclick) return;
        bclick = true;
        _this.css({background:'#395169'}).text(i+'s');
        clearInterval(timer);
        timer = setInterval(function(){
            i--;
            if(i < 10){
                _this.text('0'+i+'s');
            } else {
                _this.text(i+'s');
            }
            if( i == 0){
                clearInterval(timer);
                bclick = false;
                _this.css({background:'#2EA0DE'}).text('获取验证码');
            }

        },1000)

    });

    //手机验证函数封装
    function checkPhoneNum(_this,phone_value,this_tips){
        var phone_reg = /^[1]\d{10}$/;

        if (phone_value == "") {
            this_tips.addClass("show").text("请输入手机号码");
            return false;
        } else if (!phone_reg.test(phone_value)) {
            this_tips.addClass("show").text("手机号码格式不正确");
            return false;
        } else{
            _this.siblings(".error-alert").removeClass("show");
        }
        return;
    }

    //填写验证码
    $(".verify-code").on("blur", function () {
        var verify_code = $.trim($(this).val());
        var this_tips = $(this).siblings(".error-alert");
        if (verify_code == '') {
            this_tips.addClass("show").text("请输入验证码");
            return false;
        } else {
            this_tips.removeClass("show");
        }
    });

    //modal层提交
    $(".amend-sub-btn").on("click",function(){
        var verify_code_val = $.trim($(".verify-code").val());
        $(".info-group").children("input").each(function(){
            if($(this).val() == "") {
                $(this).siblings(".error-alert").addClass("show");
            }
        })
        if(verify_code_val != ""){
            $(".code-insure").children(".error-alert").removeClass("show");
        }
    })

});