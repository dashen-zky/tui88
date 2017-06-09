$(function(){
    //侧边栏导航高度的显示
    var _h = $('.main').height();
    $('.sidebar').height(_h + 30);
});
// 放弃 任务 (点击放弃按钮)
$(".operation").on("click",".give-up",bindAbandonFormAndTaskId);

// 绑定 id 和 form表单 给 tr 加上标记
function bindAbandonFormAndTaskId() {
    $("#give-up .modal-content .reason-show .order-id").val($(this).attr("task_id"));
    $(this).parents("tbody").find("tr").removeClass("active");
    $(this).parents("tr").addClass("active");
    $("#give-up .modal-content .reason-show textarea").val('');
}

// 提交 任务 (点击提交按钮)
$(".operation").on("click",".submit-result",bindSubmitTaskModel);

// 修改 按钮
$(".operation").on("click",".modify",bindSubmitTaskModel);

// 绑定 id 和 form表单 给 tr 加上标记
function bindSubmitTaskModel() {
    if($(this).hasClass("modify")){
        $("#modal-modify .task-map-id").val($(this).parents(".operation").attr("task_map_id"));
        $("#modal-modify .task-uuid").val($(this).parents(".operation").attr("task_uuid"));
        var targetUrl = $(this).attr("src");
        var form = $("#modal-modify form");
        $("#id-upload-002-preview-area").find(".file-list").empty();
    }else if($(this).hasClass("submit-result")){
        $("#modal-submit .task-map-id").val($(this).parents(".operation").attr("task_map_id"));
        $("#modal-submit .task-uuid").val($(this).parents(".operation").attr("task_uuid"));
        var targetUrl = $(this).attr("src");
        var form = $("#modal-submit form");
    }
    $(this).parents("tbody").find("tr").removeClass("active");
    $(this).parents("tr").addClass("active");

    form.find(".modal-body").find(">div:lt(4)").each(function () {
        $(this).removeClass("show").addClass("hide");
        if($(this).find("input").hasClass("id-card-input")){
            $(this).find("input").removeClass("id-card-input");
        }

        if($(this).find("input").hasClass("tel-input")){
            $(this).find("input").removeClass("tel-input");
        }

        if($(this).find("input").hasClass("regist-account-input")){
            $(this).find("input").removeClass("regist-account-input");
        }

    });

    var flag = false;
    var data = {};
    var _this = $(this);
    $.ajax({
        url: targetUrl,
        type: "get",
        success: function (res) {
            data = jQuery.parseJSON(res);
            if(data.code == 0){
                flag = true;
                var submit_evidence = data["data"]["submit_evidence"];
                for(var i in submit_evidence){
                    switch (i){
                        case "id_card":
                            form.find(".modal-body").find(">div:eq(0)").removeClass("hide").addClass("show");
                            form.find(".modal-body").find(">div:eq(0)").find("input").addClass("id-card-input");
                            if(_this.hasClass("modify")){
                                form.find(".modal-body").find(">div:eq(0)").find("input").val(submit_evidence[i]);
                            }
                            break;
                        case "phone":
                            form.find(".modal-body").find(">div:eq(1)").removeClass("hide").addClass("show");
                            form.find(".modal-body").find(">div:eq(1)").find("input").addClass("tel-input");
                            if(_this.hasClass("modify")){
                                form.find(".modal-body").find(">div:eq(1)").find("input").val(submit_evidence[i]);
                            }
                            break;
                        case "register_account":
                            form.find(".modal-body").find(">div:eq(2)").removeClass("hide").addClass("show");
                            form.find(".modal-body").find(">div:eq(2)").find("input").addClass("regist-account-input");
                            if(_this.hasClass("modify")){
                                form.find(".modal-body").find(">div:eq(2)").find("input").val(submit_evidence[i]);
                            }
                            break;
                        case "screen_shots":
                            if(_this.hasClass("modify")){
                                var html = '';
                                for (var index in submit_evidence[i]){
                                    html += '<li> <div class="progress" style="display: none;"> <span class="bar" style="width: 100%;"></span> <span class="percent">已上传100%</span> </div> <a data-img-name="'+submit_evidence[i][index]["img_name"]+'" class="delete-pic" href="javascript:;"><i></i></a> <img src="'+submit_evidence[i][index]["src"]+'"> </li>';
                                }
                                form.find(".upload-preview-area").find(".file-list").html(html);
                            }
                            break;
                        case "screen_shots_config":
                            if(!submit_evidence["screen_shots_config"]){
                                break;
                            }
                            form.find(".modal-body").find(">div:eq(3)").removeClass("hide").addClass("show");
                            break;
                    }
                }
                form.find(".modal-body").find(".message").find(".message-input").val(submit_evidence["message"]);
            }else {
                flag = false;
            }
        },
        async:false
    });

    if(!flag){
        wom_alert.msg({
            content: data.message,
            delay_time: 1000
        });
        return false;
    }


}

//放弃modal
$('.btn-modal-reason').on('click',abandonReasonFillIn);

$('.reason-show textarea').on('blur',function () {
    var reason_con = $(this).val();
    var this_error_msg = $('#give-up').find('.error-msg');
    if(reason_con == "") {
        this_error_msg.text('请填写放弃原因');
        return false;
    }else{
        this_error_msg.text('');
        return;
    }
});

function abandonReasonFillIn(){
    var reason_con = $('.reason-show textarea').val();
    var this_error_msg = $('#give-up').find('.error-msg');
    var abandonFlag = false;
    if(reason_con == ""){
        this_error_msg.text('请填写放弃原因');
        return false;
    }else{
        this_error_msg.text('');
        var targetUrl = $(this).parents("form").attr("action");
        var formData = $(this).parents("form").serialize();
        $.ajax({
            url: targetUrl,
            type:"post",
            data :formData,
            async:false,
            success:function (res) {
                var msg = jQuery.parseJSON(res);
                wom_alert.msg({
                    content: msg.message,
                    delay_time: 1000
                });
                if(msg.code == 0){
                    abandonFlag = true;
                    $(".ListFilterForm .btn-search").trigger("click");
                }else {
                    abandonFlag = false;
                }
            }
        });
    }
    return abandonFlag;
}

//提交modal
$('.btn-modal-submit').on('click',SubmitTask);

// 修改 提交按钮 model
$('.btn-modal-modify').on('click',ModifyTask);

$("#modal-submit .id-card-input").on("blur",function () {
    if($(this).val() == ''){
        $(this).parents(".modal-body").find(".error-msg").html("请填写身份证号码").show();
        return false;
    }else{
        var id_card_flag = validateIdCard($(this).val());
        if(!id_card_flag){
            $(this).parents(".modal-body").find(".error-msg").html("身份证号码不正确").show();
            return false;
        }else {
            $(this).parents(".modal-body").find(".error-msg").html("").hide();
        }
    }
});

$("#modal-submit .tel-input").on("blur",function () {
    if($(this).val() == ''){
        $(this).parents(".modal-body").find(".error-msg").html("请填写手机号码").show();
        return false;
    }else{
        var tel_flag = validateTel($(this).val());
        if(!tel_flag){
            $(this).parents(".modal-body").find(".error-msg").html("手机号码不正确").show();
            return false;
        }else {
            $(this).parents(".modal-body").find(".error-msg").html("").hide();
        }
    }
});
$("#modal-submit .regist-account-input").on("blur",function () {
    if($(this).val() == ''){
        $(this).parents(".modal-body").find(".error-msg").html("请填写注册账号").show();
        return false;
    }else{
        $(this).parents(".modal-body").find(".error-msg").html('').hide();
    }
});


function SubmitTask() {
    var id_card_num = $('#modal-submit').find('.id-card-input').val();
    var tel = $('#modal-submit').find('.tel-input').val();
    var regist_account = $('#modal-submit').find('.regist-account-input').val();

    if(id_card_num != undefined && id_card_num == ""){
        $(this).siblings(".error-msg").html("请填写身份证号码").show();
        return false;
    }else if(id_card_num != undefined){
        var id_card_flag = validateIdCard(id_card_num);
        if(!id_card_flag){
            $(this).siblings(".error-msg").html("身份证号码格式错误").show();
            return false;
        }else {
            $(this).siblings(".error-msg").html("").hide();
        }
    }

    if(tel != undefined && tel == ""){
        $(this).siblings(".error-msg").html("请填写手机号码").show();
        return false;
    }else if(tel != undefined){
        var tel_flag = validateTel(tel);
        if(!tel_flag){
            $(this).siblings(".error-msg").html("手机号码格式错误").show();
            return false;
        }else {
            $(this).siblings(".error-msg").html("").hide();
        }
    }

    if(regist_account != undefined && (regist_account == "" || $.trim(regist_account) == '')){
        $(this).siblings(".error-msg").html("请填写注册账号").show();
        return false;
    }else {
        $(this).siblings(".error-msg").html("").hide();
    }

    var targetUrl = $(this).parents("form").attr("action");
    var task_screen_shots = '';
    $("#id-upload-001-preview-area").find("ul").find("li").find("a").each(function () {
        task_screen_shots += ","+$(this).attr("data-img-name");
    });
    $(this).siblings(".task-screen-shots").val(task_screen_shots);

    if($("#id-upload-001-btn").parents(".info-group").hasClass("show") && task_screen_shots == ''){
        $(this).siblings(".error-msg").html("请上传截图").show();
        return false;
    }else {
        $(this).siblings(".error-msg").html("").hide();
    }

    var formData = $(this).parents("form").serialize();
    var flag = false;
    $.ajax({
        url:targetUrl,
        type:"post",
        data:formData,
        async:false,
        success:function (res) {
            flag = jQuery.parseJSON(res);
            wom_alert.msg({
                content:flag.message,
                delay_time: 1000
            });

        }
    });
    if(flag.code != 0){
        return false;
    }else {
        $(".ListFilterForm .btn-search").trigger("click");
    }
    $(".list tbody").find(">.active").find(">td:eq(2)").html(flag.next);
    $(".list tbody").find(">.active").find(".operation").find(".submit-result").find("span").html("修改");
    $(".list tbody").find(">.active").find(".operation").find(".submit-result").addClass("modify").removeClass("submit-result").attr("data-target","#modal-modify").attr("src",flag.modify_url);


}

function ModifyTask() {
    var id_card_num = $('#modal-modify').find('.id-card-input').val();
    var tel = $('#modal-modify').find('.tel-input').val();
    var regist_account = $('#modal-modify').find('.regist-account-input').val();

    if(id_card_num != undefined && id_card_num == ""){
        $(this).siblings(".error-msg").html("请填写身份证号码").show();
        return false;
    }else if(id_card_num != undefined){
        var id_card_flag = validateIdCard(id_card_num);
        if(!id_card_flag){
            $(this).siblings(".error-msg").html("身份证号码格式错误").show();
            return false;
        }else {
            $(this).siblings(".error-msg").html("").hide();
        }
    }

    if(tel != undefined && tel == ""){
        $(this).siblings(".error-msg").html("请填写手机号码").show();
        return false;
    }else if(tel != undefined){
        var tel_flag = validateTel(tel);
        if(!tel_flag){
            $(this).siblings(".error-msg").html("手机号码格式错误").show();
            return false;
        }else {
            $(this).siblings(".error-msg").html("").hide();
        }
    }

    if(regist_account != undefined && (regist_account == "" || $.trim(regist_account) == '')){
        $(this).siblings(".error-msg").html("请填写注册账号").show();
        return false;
    }else {
        $(this).siblings(".error-msg").html("").hide();
    }

    var targetUrl = $(this).parents("form").attr("action");
    var task_screen_shots = '';
    $("#id-upload-002-preview-area").find("ul").find("li").find("a").each(function () {
        task_screen_shots += ","+$(this).attr("data-img-name");
    });
    $(this).siblings(".task-screen-shots").val(task_screen_shots);

    if($("#id-upload-002-btn").parents(".info-group").hasClass("show") && task_screen_shots == ''){
        $(this).siblings(".error-msg").html("请上传截图").show();
        return false;
    }else {
        $(this).siblings(".error-msg").html("").hide();
    }

    var formData = $(this).parents("form").serialize();
    var flag = false;
    $.ajax({
        url:targetUrl,
        type:"post",
        data:formData,
        async:false,
        success:function (res) {
            flag = jQuery.parseJSON(res);
            wom_alert.msg({
                content:flag.message,
                delay_time: 1000
            });

        }
    });
    if(flag.code != 0){
        return false;
    }else {
        $(".ListFilterForm .btn-search").trigger("click");
    }
}

function validateIdCard(id_card_num) {
    var city={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江 ",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北 ",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏 ",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外 "};
    var preg = /^\d{6}(18|19|20)?\d{2}(0[1-9]|1[12])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)$/i;
    var code = $.trim(id_card_num).replace(/\s/g, '');

    if(!preg.test(code)){
        return false;
    }

    if(!city[code.substr(0,2)]){
        return false;
    }

    if(code.length == 18){
        code = code.split('');
        //加权因子
        var factor = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2 ];
        //校验位
        var parity = [ 1, 0, 'X', 9, 8, 7, 6, 5, 4, 3, 2 ];
        var sum = 0;
        var ai = 0;
        var wi = 0;
        for (var i = 0; i < 17; i++)
        {
            ai = code[i];
            wi = factor[i];
            sum += ai * wi;
        }
        var last = parity[sum % 11];
        if(parity[sum % 11] != code[17]){
            return false;
        }else {
            return true;
        }
    }
}

function validateTel(tel) {
    var code = $.trim(tel).replace(/\s/g, '');
    var preg = /^1[34578]\d{9}$/;
    if(!preg.test(code)){
        return false;
    }
    return true;
}

$(function(){
    // layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });
    $('.status-wrap li').on('click',function(){
        $(this).siblings("input").val($(this).val());
        $(this).addClass('on-click').siblings().removeClass('on-click');
    });

    // 修改
    $('.btn-modal-modify').on('click',function () {
        var id_card_num = $('#modal-modify').find('.id-card-input').val();
        var tel = $('#modal-modify').find('.tel-input').val();
        var regist_account = $('#modal-modify').find('.regist-account-input').val();
        var message_info = $('#modal-modify').find('.message-input').val();
    });


    // ************************************************
    // ***  wom uploader
    // *** 上传图片/文件
    // ************************************************
    // ========= 上传按钮与上传结果展示区域的对应 =========
    var wom_uploader_setting = {
        'id-upload-001-btn': 'id-upload-001-preview-area',
        'id-upload-002-btn': 'id-upload-002-preview-area'
    };

    // =========  上传中自定义函数 =========
    function uploader_file_added(uploader_btn_id, files){
        var display_area = $('#' + wom_uploader_setting[uploader_btn_id] + '');
        var display_content = '';
        for(var i = 0, len = files.length; i < len; i++){
            var file_name = files[i].name;
            var file_id = files[i].id;
            // 上传的图片
            var img_item = '<div class="progress"><span class="bar"></span><span class="percent"></span></div><a data-img-name="" class="delete-pic" href="javascript:;"><i></i></a>';
            display_content += '<li id="' + file_id +'">' + img_item + '</li>';

            !function(i){
                previewImage(files[i], function(img_url){
                    $('#' + files[i].id).append('<img src="'+ img_url +'" />');
                })
            }(i);
        }
        var file_list = display_area.find('.file-list');
        if(file_list.length == 0){
            display_content = '<ul class="file-list">' + display_content + '</ul>';
            display_area.append(display_content);
        } else {
            display_area.find('.file-list').append(display_content);
        }
    }
    function uploader_upload_progress(uploader_btn_id, file){
        var file_id = file.id;
        var percent = file.percent;
        $('#' + file_id).find('.bar').css({'width': percent + '%'});
        $('#' + file_id).find('.percent').text('已上传' + percent + '%');
    }
    function uploader_file_uploaded(uploader_btn_id, file, resp){
        // 设置删除图片按钮的属性(data-img-name)
        var file_id = file.id;
        var display_area = $('#' + wom_uploader_setting[uploader_btn_id] + '');
        var resp = $.parseJSON(resp.response);
        if(resp.err_code == 0){
            var file_name = resp.file_name;
            $('#' + file_id).find(".delete-pic").attr("data-img-name", file_name);
            display_area.find('.progress').hide();
        } else {
            // 出错
        }
    }

    // 提交效果截图
    wom_uploader.init('id-upload-001-btn',{
        file_added : function(files){
            uploader_file_added('id-upload-001-btn', files);
        },
        upload_progress: function(file) {
            uploader_upload_progress('id-upload-001-btn', file);
        },
        file_uploaded: function(file, resp){
            uploader_file_uploaded('id-upload-001-btn', file, resp);
        },
        max_file_size: '2mb', //限制上传图片的大小,
        file_ext_accept: 'jpeg,jpg,gif,png',
        upload_url: $('input#id-upload-file-url').val(),
        csrf: $('#modal-submit form input[name=_csrf]').val()
    });

    // 修改截图
    wom_uploader.init('id-upload-002-btn',{
        file_added : function(files){
            uploader_file_added('id-upload-002-btn', files);
        },
        upload_progress: function(file) {
            uploader_upload_progress('id-upload-002-btn', file);
        },
        file_uploaded: function(file, resp){
            uploader_file_uploaded('id-upload-002-btn', file, resp);
        },
        max_file_size: '2mb', //限制上传图片的大小,
        file_ext_accept: 'jpeg,jpg,gif,png',
        upload_url: $('input#id-upload-file-url').val(),
        csrf: $('#modal-modify form input[name=_csrf]').val()
    });

    // 删除图片
    $(".upload-preview-area").on('click', '.file-list .delete-pic', function(){
        var this_img = $(this).closest('li');
        var img_name = $(this).data('img-name');
        var order_id = 0;
        if($(this).parents(".upload-preview-area").attr("id") == 'id-upload-001-preview-area'){
             order_id = $("#modal-submit .task-map-id").val();
        }else if($(this).parents(".upload-preview-area").attr("id") == 'id-upload-002-preview-area'){
             order_id = $("#modal-modify .task-map-id").val();
        }
        var delete_file_url = $("input#id-delete-file-url").val();
        if(img_name == ''){
            return false;
        }
        $.ajax({
            url: delete_file_url,
            type: 'GET',
            cache: false,
            dataType: 'json',
            data: {
                img_name: img_name,
                id: order_id
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    this_img.remove();
                } else if(resp.err_code == 1){
                    wom_alert.msg({
                        icon: "error",
                        content:"系统异常",
                        delay_time: 1000
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    icon: "error",
                    content:"系统异常",
                    delay_time: 1000
                });
                return false;
            },
            async:false
        });
    });

    // 判断任务数量
    $(document).ready(function () {
       checkTaskNum();
    });

    function checkTaskNum() {
        var task_account = $('.task-tb-info tbody').find('tr').length;
        if(task_account < 1){
            $('.no-task').show();
        }else{
            $('.no-task').hide();
        }
    }

});

//收款凭证
$(".operation").on("click",".evidence",getEvidence);
function getEvidence() {
    var _this = $(this);
    var flag = false;
    $.ajax({
        url : _this.attr("url"),
        type : 'get',
        success : function (res) {
            var data = jQuery.parseJSON(res);
            if(data.code == 0){
                $("#paying-voucher").html(data.html);
                flag = true;
            }
        },
        async : false,
    });
    return flag;
}

$("#modal-submit").on("hidden.bs.modal", function() {
    var _blank = $(this).find("input"),
        msg = $(this).find(".error-msg"),
        textarea = $(this).find(".message-input"),
        img = $(".file-list li");
    _blank.val("");
    msg.text("");
    textarea.val("");
    img.remove();
});

