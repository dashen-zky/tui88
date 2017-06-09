$(function () {
    //侧边栏导航高度的显示
    var _h = $('.main').height();
    $('.sidebar').height(_h + 30);

    var flagOld = false;
    var flagNew = false;
    var flagReNew = false;

    //失焦时原密码触发事件
    $('.org-psd input').blur(function(){
        var _value = $.trim($(this).val());
        flagOld = oldPassword($(this), _value);
    })

    //新密码失焦时触发的事件
    $('.new-psd input').blur(function(){
        var _value = $.trim($(this).val());
        flagNew = newPassword($(this), _value,$(".confir-new-psd input"));

    })

    //确认密码失焦时触发事件
    $('.confir-new-psd input').blur(function(){
        var re_psd_value = $.trim($(this).val());
        var psd_value = $.trim($(".new-psd input").val());
        flagReNew = reNewPassword($(this), re_psd_value, psd_value);

    })

    //点击提交时触发的事件
    $('.sub-btn').click(function(){
       $(".org-psd input").trigger("blur");
       $(".new-psd input").trigger("blur");
       $(".confir-new-psd input").trigger("blur");
       if(flagOld && flagNew && flagReNew){
            return true;
       }
       return false;
    })

    /**
     * 原密码 验证函数
     * @param _this
     * @param _value
     * @returns {boolean}
     */
    function oldPassword(_this,_value) {
        if(_value == ''){
            _this.siblings('.error-alert').addClass('show').html("请输入原密码");
            return false;
        }else if(_value.length < 6  || _value.length > 20 ){
            _this.siblings('.error-alert').addClass('show').html("密码长度要求为6~20位");
            return false;
        }
        _this.siblings('.error-alert').removeClass('show');
        return true;
    }

    /**
     * 新密码的验证 函数
     * @param _this
     * @param _value
     * @returns {boolean}
     */
    function newPassword(_this,_value,re_this) {
        var psd_reg = /^[a-zA-Z0-9]{6,20}$/;
        if (_value == "") {
            _this.siblings(".error-alert").addClass("show").text("请输入密码");
            return false;
        } else if (_value.length < 6 || _value.length > 20) {
            _this.siblings(".error-alert").addClass("show").text("密码长度要求6-20位");
            return false;
        } else if (!psd_reg.test(_value)) {
            _this.siblings(".error-alert").addClass("show").text("密码由6-20位的字母或数字组成");
            return false;
        } else {
            if($.trim($(".org-psd input").val()) == _value){
                _this.siblings(".error-alert").addClass("show").html('新密码不能与原密码一致');
                return false;
            }
            if (re_this.val() == ''){
                _this.siblings(".error-alert").removeClass("show");
                return true;
            }
            if(re_this.val() != _value){
                re_this.siblings(".error-alert").addClass("show").html("两次密码不一致");
                return false;
            }else {
                re_this.siblings(".error-alert").removeClass("show");
                return true;
            }
        }
    }

    /**
     * 确认密码 是否一致
     * @param _this
     * @param re_psd_value
     * @param psd_value
     * @returns {boolean}
     */
    function reNewPassword(_this, re_psd_value, psd_value) {
        if(re_psd_value == ''){
            _this.siblings('.error-alert').addClass('show').text("请再次输入密码");
            return false;
        } else if(re_psd_value != psd_value){
            _this.siblings('.error-alert').addClass('show').text("两次密码不一致");
            return false;
        } else{
            _this.siblings('.error-alert').removeClass('show');
            return true;
        }
    }
});