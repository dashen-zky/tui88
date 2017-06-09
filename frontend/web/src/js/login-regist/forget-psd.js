$(function(){
    //layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });
    var flagPhone = false;
    var flagVerify = false;
    var flagImgCode = false;
    var flagPsd = false;
    var flagRePsd = false;
    //手机号码
    $('.cell-phone').on("blur",function(){
        var cell_phone_val = $.trim($(this).val());
        flagPhone = validatePhone($(this),cell_phone_val);
    });

    $(".verify-code").on("blur",function(){
        var verify = $.trim($(this).val());
        flagVerify = validateVerify($(this),verify);

    });

    $(".img-code .imgCode").on("blur",function () {
        if($(this).val() == ''){
            $(this).siblings(".tips").addClass("show").text("请输入验证码");
            flagImgCode = false;
        }else {
            $(this).siblings(".tips").removeClass("show");
            flagImgCode = true;
        }
    });

    //获取验证码
    $(".get-code").on("click",function(){
        $(".cell-phone").trigger("blur");
        $(".imgCode").trigger("blur");
        if(flagPhone && flagImgCode){
            var flag = false;
            // 发送短信 接受验证码
            $.ajax({
                url : "/index.php?r=site/send-message",
                type :"post",
                data : {phone:$(".cell-phone").val(),verify:$(".imgCode").val(),method:"find_psd"},
                success : function (res) {
                    var data = jQuery.parseJSON(res);
                    if(data.code == 1){
                        flag = false;
                        $(".cell-phone").siblings(".tips").addClass("show").text(data.message);
                    }else if(data.code == 2){
                        flag = false;
                        $(".img-code").find(".tips").addClass("show").text(data.message);
                    }else if(data.code == 0){
                        flag = true;
                    }
                },
                async : false
            });

            if(flag){
                $(".img-code").find(".tips").removeClass("show");
                var whole_time = 60,active_time = 1;
                $(this).hide();
                $(".unclick").show();
                timer = setInterval(update, 1000);
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
    })

    //下一步
    $(".next-step").on("click",function(){
         $(".cell-phone").trigger("blur");
         $(".verify-code").trigger("blur");

        if(flagPhone && flagVerify){
            var data = {_csrf:$("meta[name=csrf-token]").attr("content"),FindPsdForm:{phone:$(".cell-phone").val(),verify:$(".verify-code").val()}};
            var targetUrl = $(this).attr("src");
            $.post(targetUrl,data,function (res){
                var data = jQuery.parseJSON(res);
                if(data.status == 0){
                    $(".main-con").html(data.data);
                    $(".psd").bind("blur",function () {
                        var $that = $(".insure-psd");
                        flagPsd = validataPassword($(this));
                        if($that.val() != ''){
                            flagRePsd = validateRePassword($that,$(this).val());
                        }
                    });
                    $(".insure-psd").bind("blur",function () {
                        var $that = $(".psd");
                        flagRePsd = validateRePassword($(this),$that.val());
                    });
                    $(".affirm-submit").bind("click",function () {
                        confirm($(this));
                    });
                } else if(data.status == 1){
                    if(data.message.phone != undefined){
                       $(".cell-phone").siblings(".tips").addClass("show").html(data.message.phone);
                    }
                    if(data.message.verify != undefined){
                        $(".code-insure .tips").addClass("show").html(data.message.verify);
                    }
                }

            });
        }


    })


    //确认重置密码
   function confirm($this) {
       $(".psd").trigger("blur");
       $(".insure-psd").trigger("blur");
       if (flagPsd && flagRePsd) {
           var targetUrl = $this.attr("src");
           var data = {FindPsdForm: {password: $(".psd").val(), rePassword: $(".insure-psd").val()}};
           $.post(targetUrl, data, function (res) {
               var data = jQuery.parseJSON(res);
               if(data.status == 0){
                   wom_alert.msg({
                       icon: "finish",
                       content:"密码重置成功!",
                       delay_time:1500
                   })
                   location.href = "/index.php?r=site/login";
               }
           });
       }
   }

    /**
     * 验证手机号
     * @param $this
     * @param cell_phone_val
     * @returns {boolean}
     */
    function validatePhone($this,cell_phone_val) {
        var cell_phone_reg= /^1[34578]\d{9}$/;
        if(cell_phone_val == ""){
            $this.siblings(".tips").addClass("show").text("请输入手机号码");
            return false;
        }else if(!cell_phone_reg.test(cell_phone_val)){
            $this.siblings(".tips").addClass("show").text("请输入正确的手机号");
            return false;
        }else{
            $this.siblings(".tips").removeClass("show");
            return true;
        }
    }

    /**
     * 验证验证码
     * @param $this
     * @param verify
     * @returns {boolean}
     */
    function validateVerify($this,verify) {
        if(verify == ""){
            $this.siblings(".tips").addClass("show");
            return false;
        }
        $this.siblings(".tips").removeClass("show");
        return true;
    }

    /**
     * 检测密码 是否符合格式
     * @param $this
     */
    function validataPassword($this) {
        var psd_reg= /^[a-zA-Z0-9]{6,20}$/;
        if($this.val() == ""){
            $this.siblings(".tips").addClass("show").text("请输入密码");
            return false;
        }else if( $this.val().length < 6 ||  $this.val().length >20){
            $this.siblings(".tips").addClass("show").text("密码长度要求6-20位");
            return false;
        }else if(!psd_reg.test($this.val())){
            $this.siblings(".tips").addClass("show").text("密码必须由数字和字母组成");
            return false;
        }else{
            $this.siblings(".tips").removeClass("show");
            return true;
        }
    }

    /**
     * 检测 确认密码 是否一致
     * @param $this
     * @param psd
     * @returns {boolean}
     */
    function validateRePassword($this,psd) {
        if($this.val() == ""){
            $this.siblings(".tips").addClass("show").text("请输入确认密码");
            return false;
        }else if( $this.val() != psd){
            $this.siblings(".tips").addClass("show").text("两次密码不一致");
            return false;
        }else{
            $this.siblings(".tips").removeClass("show");
            return true;
        }
    }

})