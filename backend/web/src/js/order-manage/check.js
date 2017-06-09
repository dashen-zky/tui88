$(".list .operate ").on("click",".check,.btn-modify",getCheckInformation);

function getCheckInformation() {
    $(this).parents("tbody").find("tr").removeClass("active");
    $(this).parents("tr").addClass("active");
    var flag = false;
    var url = $(this).attr("url");
    if($(this).hasClass("check")){
        $.ajax({
            url:url,
            type:"get",
            async:false,
            success:function (res) {
                var data = jQuery.parseJSON(res);
                if(data.code != 0){
                    flag = false;
                    wom_alert.msg({
                        content:data.message,
                        delay_time:1000
                    });
                    return false;
                }else {
                    flag = true;
                    $("#modal-check .modal-content form").find(".task-id").val(data.id);
                    $("#modal-check .modal-content form").find(".dist-exec-id").val(data["dis_id"]);
                    $("#modal-check .modal-body .configuration-item").find("li").each(function () {
                        $(this).removeClass("show").addClass("hide");
                    });
                    for (var index in data.submit_evidence) {
                        switch (index) {
                            case "id_card":
                                $("#modal-check .modal-body .configuration-item").find("li:eq(0)").find("i").html(data['submit_evidence'][index]);
                                $("#modal-check .modal-body .configuration-item").find("li:eq(0)").removeClass("hide").addClass("show");
                                break;
                            case "phone":
                                $("#modal-check .modal-body .configuration-item").find("li:eq(1)").find("i").html(data['submit_evidence'][index]);
                                $("#modal-check .modal-body .configuration-item").find("li:eq(1)").removeClass("hide").addClass("show");
                                break;
                            case "register_account":
                                $("#modal-check .modal-body .configuration-item").find("li:eq(2)").find("i").html(data['submit_evidence'][index]);
                                $("#modal-check .modal-body .configuration-item").find("li:eq(2)").removeClass("hide").addClass("show");
                                break;
                        }
                    }

                    $("#modal-check .modal-body .pic-show").removeClass("show").addClass("hide");
                    if (data['screen_shots'].length != 0) {
                        var imgHtml = '';
                        for (var index in data['screen_shots']) {
                            imgHtml += '<img src="' + data['screen_shots'][index] + '" height="100px" width="90px" alt="" />';
                        }
                        $("#modal-check .modal-body .pic-show .pic").html(imgHtml);
                        $("#modal-check .modal-body .pic-show").removeClass("hide").addClass("show");
                    } else {
                        $("#modal-check .modal-body .pic-show").removeClass("show").addClass("hide");
                    }

                    $("#modal-check .modal-body .remarks").hide();
                    if(data['submit_evidence']['message'] && data['submit_evidence']['message'].length != 0){
                        $("#modal-check .modal-body .remarks").find("span:eq(1)").html(data['submit_evidence']['message']);
                        $("#modal-check .modal-body .remarks").show();
                    }
                    $("#modal-check .modal-body .modify-account").find(".unit-account").find("i").html(data['unit_money']);
                    $("#modal-check .modal-body .modify-account").find(".unit_money").val(data['unit_money']);
                    $("#modal-check .modal-body .modify-account").find(".insure-money").val('');
                    $("#modal-check .modal-body .reply-msg").find("textarea").val('');
                }

            }
        });
    }else if($(this).hasClass("btn-modify")){
        // 大清除
        $("#modal-modify form .modal-body .configuration-item li").each(function () {
            if($(this).find("input[type=text]").hasClass("input-id-card")){
                $(this).find("input[type=text]").removeClass("input-id-card");
            }else if($(this).find("input[type=text]").hasClass("input-tel")){
                $(this).find("input[type=text]").removeClass("input-tel");
            }else if($(this).find("input[type=text]").hasClass("input-regist-account")){
                $(this).find("input[type=text]").removeClass("input-regist-account");
            }
            $(this).addClass("hide").removeClass("show");
        });

        $("#modal-modify form .modal-body .screenshot").addClass("hide").removeClass("show");
        $("#id-upload-002-preview-area").addClass("hide").removeClass("show");

        if($("#modal-modify form .modal-body .modify-account .insure-money").hasClass("insure-money")){
            $("#modal-modify form .modal-body .modify-account .insure-money").removeClass("insure-money");
        }
        $("#modal-modify form .modal-body .reply-msg textarea").empty();

        $.ajax({
            url : url,
            type : "get",
            async : false,
            success : function (res) {
                var data = jQuery.parseJSON(res);
                if(data.code != 0){
                    flag = false;
                    wom_alert.msg({
                        content:data.message,
                        delay_time:1000
                    });
                    return false;
                }
                flag = true;
                $("#modal-modify form .modal-content").find(".task-id").val(data.id);
                $("#modal-modify form .modal-content").find(".dist-exec-id").val(data["dis_id"]);

                for (var index in data["submit_evidence"]) {
                    switch (index) {
                        case "id_card":
                            $("#modal-modify .modal-body .configuration-item").find("li:eq(0)").find("input").val(data['submit_evidence'][index]);
                            $("#modal-modify .modal-body .configuration-item").find("li:eq(0)").removeClass("hide").addClass("show");
                            break;
                        case "phone":
                            $("#modal-modify .modal-body .configuration-item").find("li:eq(1)").find("input").val(data['submit_evidence'][index]);
                            $("#modal-modify .modal-body .configuration-item").find("li:eq(1)").removeClass("hide").addClass("show");
                            break;
                        case "register_account":
                            $("#modal-modify .modal-body .configuration-item").find("li:eq(2)").find("input").val(data['submit_evidence'][index]);
                            $("#modal-modify .modal-body .configuration-item").find("li:eq(2)").removeClass("hide").addClass("show");
                            break;
                    }
                }

                if (data['screen_shots'].length != 0) {
                    // var imgHtml = '';
                    // for (var index in data['screen_shots']) {
                    //     var img_name = data["screen_shots"][index].substring(data["screen_shots"][index].lastIndexOf("/")+1,data["screen_shots"][index].lastIndexOf("."));
                    //     imgHtml += '<li> <div class="progress" style="display: none;"> <span class="bar" style="width: 100%;"></span> <span class="percent">已上传100%</span> </div> <a data-img-name="'+img_name+'" class="delete-pic" href="javascript:;"><i></i></a> <img src="'+data['screen_shots'][index]+'"> </li>';
                    // }
                    //
                    // $("#id-upload-002-preview-area .file-list").html(imgHtml);
                    $("#modal-modify .modal-body .screenshot").removeClass("hide").addClass("show");
                    $("#id-upload-002-preview-area").removeClass("hide").addClass("show");
                }

                $("#modal-modify .modal-body .modify-account").find("input:eq(0)").val(data['insure_money']);
                $("#modal-modify .modal-body .modify-account").find(".input:eq(0)").addClass("insure-money");
                $("#modal-modify .modal-body .modify-account").find(".task-account").find("i").html(data['unit_money']);
                $("#modal-modify .modal-body .modify-account").find(".unit-money").val(data['unit_money']);
                $("#modal-modify .modal-body .reply-msg").find("textarea").html(data['submit_evidence']['message']);
            }
        });
    }
    return flag;
}

$(".list .operate .same-batch").on("click",getSameBatchData);
function getSameBatchData() {
    var task_uuid_new = $(this).attr("task-uuid");
    var task_uuid_old = $(".ListFilterForm").find(".same-batch").val();
    if(task_uuid_new == task_uuid_old){
        wom_alert.msg({
            content:"已是同批！",
            delay_time:1000
        });
        return false;
    }
    $(".ListFilterForm").find(".same-batch").val(task_uuid_new);
    $(".ListFilterForm .btn-search").trigger("click");
}

