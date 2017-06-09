$(function () {
    //失焦时原密码触发事件
    $('.org-psd input').blur(function(){
        var _value = $(this).val();
        if(_value == ''){
            $(this).siblings('.error').addClass('show');
        }
    })
    //新密码失焦时触发的事件
    $('.new-psd input').blur(function(){
        var _value = $.trim($(this).val());
        var psd_reg = /^[a-zA-Z0-9]{6,20}$/;
        if (_value == "") {
            $(this).siblings(".error-alert").addClass("show").text("请输入密码");
            return;
        } else if (_value.length < 6 || _value.length > 20) {
            $(this).siblings(".error-alert").addClass("show").text("密码长度要求6-20位");
            return;
        } else if (!psd_reg.test(_value)) {
            $(this).siblings(".error-alert").addClass("show").text("密码由6-20位的字母或数字组成");
            return;
        } else {
            $(this).siblings(".error-alert").removeClass("show");
            return;
        }
    })
    //确认密码失焦时触发事件
    $('.confir-new-psd input').blur(function(){
        var confir_value = $.trim($(this).val());
        var psd_value = $.trim($('.new-psd input').val());
        if(confir_value == ''){
            $(this).siblings('.error-alert').addClass('show').text("请再次输入密码");
            return;
        } else if(confir_value != psd_value){
            $(this).siblings('.error-alert').addClass('show').text("两次密码不一致");
            return;
        } else{
            $(this).siblings('.error-alert').removeClass('show');
            return;
        }
    })
    //点击提交时触发的事件
    $('.sub-btn').click(function(){
        $(".info-group").children("input").each(function(){
            if($(this).val() == "") {
                $(this).siblings(".error-alert").addClass("show");
            }
        })
    })
});