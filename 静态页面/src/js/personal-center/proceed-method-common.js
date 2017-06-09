$(function () {
    //手机号码
    $('.ph-num input').blur(function () {
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
        var phone_value = $.trim(_this.parents('.modal-body').find('.ph-num input').val());
        var this_tips = _this.parents('.modal-body').find('.ph-num .error-alert');
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
    //银行账户验证
    $('.bank-num').blur(function(){
        var _band_num = $.trim($(this).val());
        var _reg = /^[0-9]*[1-9][0-9]*$/;
        if(!_reg.test(_band_num)){
            $(this).siblings('.error-alert').addClass('show');
            return;
        } else {
            $(this).siblings('.error-alert').removeClass('show');
            return;
        }
    })
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
    //点击保存触发事件
    $('.save-btn').click(function(){
        $(this).siblings('.info-group').find('input').each(function(){
            if($(this).val() == ''){
                $(this).siblings('.error-alert').addClass('show');
            }
        });
    })
});