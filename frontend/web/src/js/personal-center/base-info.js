$(function () {
    // 点击保存按钮
    $(".incontent .save-btn").on("click",function () {

    });


    //侧边栏导航高度的显示
    var _h = $('.main').height();
    $('.sidebar').height(_h + 30);
    var flagPhone = false;
    var flagVerify = false;
    //手机号码
    $('.new-ph-num input').on('blur', function () {
        var _this = $(this);
        var phone_value = $.trim(_this.val());
        var this_tips = _this.siblings('.error-alert');
        flagPhone = checkPhoneNum(_this,phone_value,this_tips);
    });

    // 获取验证码
    var timer = null,
        bclick = false;
    $(".get-code").on("click", function () {
        var _this = $(this);
        $(".new-ph-num input").trigger("blur");
        if(!flagPhone) return false;
        var flag = false;
        var i = 60;
        if(bclick) return;
        $.ajax({
            url : _this.attr("url"),
            type : "get",
            data : {phone:$('.new-ph-num input').val()},
            success : function (res) {
                var data = jQuery.parseJSON(res);
                if(data.code == 0){
                    flag = true;
                }else {
                    flag = false;
                    $(".new-ph-num input").siblings('.error-alert').addClass("show").text(data.message);
                }
            },
            async : false
        });
        if(flag){
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

            },1000);
        }

        return flag;

    });

    //手机验证函数封装
    function checkPhoneNum(_this,phone_value,this_tips){
        var phone_reg = /^1[34578]\d{9}$/;

        if (phone_value == "") {
            this_tips.addClass("show").text("请输入手机号码");
            return false;
        } else if (!phone_reg.test(phone_value)) {
            this_tips.addClass("show").text("手机号码格式不正确");
            return false;
        } else{
            _this.siblings(".error-alert").removeClass("show");
        }
        return true;
    }

    //填写验证码
    $(".verify-code").on("blur", function () {
        var verify_code = $.trim($(this).val());
        var this_tips = $(this).siblings(".error-alert");
        if (verify_code == '') {
            this_tips.addClass("show").text("请输入验证码");
            flagVerify = false;
            return false;
        } else {
            this_tips.removeClass("show");
            flagVerify = true;
        }
    });

    //modal层提交
    $(".amend-sub-btn").on("click",function(){
        $('.new-ph-num input').trigger("blur");
        $('.verify-code').trigger("blur");
        if(flagPhone && flagVerify){
            var flagTrue = false;
            var phone = $('.new-ph-num input').val();
            var formData = $(this).parents("form");
            $.ajax({
                url : formData.attr("action"),
                type : "post",
                data : formData.serialize(),
                success : function (res) {
                    var data = jQuery.parseJSON(res);
                    if(data.code == 0){
                        flagTrue = true;
                        $(".my-modify-phone").html(phone);
                    }else {
                        flagTrue = false;
                        wom_alert.msg({
                            content:data.message,
                            delay_time:1000
                        })
                    }
                },
                async : false
            });
        }else {
            return false;
        }
        return flagTrue;
    })

    $("#amend-ph-num").on("hidden.bs.modal", function() {
        var _blank = $(this).find("input"),
            msg = $(this).find(".error-alert");
        _blank.val("");
        msg.text("");
    });

    $(".test-code input").on("keyup",function() {
        numRestraint($(this));
    });
    // 限制输入数字
    function numRestraint(_this_con){
        _this_con.val( _this_con.val().replace(/\D/g, ''));
    }
});