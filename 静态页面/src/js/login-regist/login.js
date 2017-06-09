$(function(){

    $(".input-group input").on("blur",function(){
        var this_val = $(this).val();
        if(this_val == ""){
            $(this).siblings(".insure").show();
        }else{
            $(this).siblings(".insure").hide();
        }
    })
    $(".btn-login").on("click",function(){
        var phone_val = $(".phone-number").val();
        var psd_val = $(".psd").val();
        $(".input-group input").each(function(){
            var this_val = $(this).val();
            if(this_val == ""){
                $(this).siblings(".insure").show();
            }else{
                $(this).siblings(".insure").hide();
            }
        })

    })
})