$(function(){
    $(".input-group input").on("blur",function(){
        var this_val = $(this).val();
        if(this_val == ""){
            $(this).siblings(".insure").show();
        }else{
            $(this).siblings(".insure").hide();
        }
    });

    $(".btn-login").on("click",function(){
        var phone_val = $(".phone-number").val();
        var phone_tips = $(".phone-number").siblings(".insure");
        var psd_val = $(".psd").val();
        var psd_tips = $(".psd").siblings(".insure");

        if(phone_val == ''){
            phone_tips.show();
            return false;
        }else {
            phone_tips.hide();
        }

        if(psd_val == ''){
            psd_tips.show();
            return false;
        }else {
            psd_tips.hide();

        }

    })

});