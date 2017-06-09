$(function () {
    // layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });

    //侧边栏导航高度的显示
    var _h = $('.main').height();
    $('.sidebar').height(_h + 30);

    var bank_card_num_flag = false;
    var bank_card_name_flag = false;
    var bank_name_opening_flag = false;
    //银行账户验证
    $('.bank-card-num').on("blur",function(){
        var _band_num = $.trim($(this).val()).replace(/\s/g, '');
        var _reg = /^\d*$/;
        if(!_reg.test(_band_num) || !(_band_num.length == 16 || _band_num.length == 19)){
            wom_alert.msg({
                content: "银行账号为16或19位的数字",
                delay_time: 1500
            });
            bank_card_num_flag = false;
            return false;
        }
        var flag = CheckBankNum($(this),_band_num);
        if(!flag) {
            wom_alert.msg({
                content: "未知银行账号",
                delay_time: 1500
            });
            return false;
        }
        bank_card_num_flag = true;
        return true;
    });

    $(".bank-name-opening").on("blur",function () {
        if($.trim($(this).val()) == ''){
            wom_alert.msg({
                content: "请填写银行卡开户行",
                delay_time: 1500
            });
            bank_name_opening_flag = false;
            return false;
        }
        bank_name_opening_flag = true;
        return true;
    });

    $(".bank-card-name").on("blur",function () {
        if($.trim($(this).val()) == ''){
            wom_alert.msg({
                content: "请填写银行卡开户姓名",
                delay_time: 1500
            });
            bank_card_name_flag = false;
            return false;
        }
        bank_card_name_flag = true;
        return true;
    });

    //点击保存触发事件
    $('.save-btn').on("click",function(){
        $(".bank-card-num").trigger("blur");
        $(".bank-name-opening").trigger("blur");
        $(".bank-card-name").trigger("blur");
        if(!bank_card_num_flag || !bank_name_opening_flag || !bank_card_name_flag){
            return false;
        }
        var formData = $(this).parents("form").serialize();
        var targetUrl = $(this).parents("form").attr("action");
        $.ajax({
            url : targetUrl,
            type : "post",
            data : formData,
            success : function (res) {
                var data = jQuery.parseJSON(res);
                if(data.code == 0){
                    wom_alert.msg({
                        content: data.message,
                        delay_time: 1500
                    });
                    location.href = '/index.php?r=personal-center/setting/setting/proceed-method';
                }else {
                    wom_alert.msg({
                        content: data.message,
                        delay_time: 1500
                    });
                }
            },
            async : false
        });


    });
    
    function CheckBankNum($this,bank_num) {
        var bank_num_len = bank_num.length;
        var last_num = bank_num.substr(bank_num_len -1,1);
        var sum = 0;
        var j = 1;
        for(var i=bank_num_len-2; i>-1;i--){
            var num = parseInt(bank_num.charAt(i));
            if(j % 2 == 1) {
                if (num * 2 > 9) {
                    // sum += num*2/10 + num*2%10;
                    sum += num * 2 - 9;
                }else{
                    sum += num*2;
                }
            }else if(j % 2 == 0){
                sum += num;
            }
            j++;
        }
        var luhm = 10 - (sum % 10 == 0 ? 10 : sum % 10);
        if(last_num == luhm){
            $this.siblings(".error-alert").removeClass("show");
            return true;
        }
        $this.siblings(".error-alert").addClass("show");
        return false;
    }

});