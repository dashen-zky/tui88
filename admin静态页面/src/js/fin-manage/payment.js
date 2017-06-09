$(function () {
    // layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });

    //限制收款金额和手机号输入数字
    $("input[name=min_amount]").on("keyup",function() {
        numRestraint($(this));
    })
    $("input[name=max_amount]").on("keyup",function() {
        numRestraint($(this));
    })
    // 限制输入数字
    function numRestraint(_this_con){
        _this_con.val( _this_con.val().replace(/\D/g, ''));
    }

    // 搜索
    $(".btn-search").on("click",function () {
        var settlement_start_time = $(".settlement-time").children("input[name=settlement_start_time]").val(),
            settlement_end_time = $(".settlement-time").children("input[name=settlement_end_time]").val(),
            min_amount = parseInt($("input[name=min_amount]").val()),
            max_amount = parseInt($("input[name=max_amount]").val()),
            search_con = $(".search-input").val();

        if (max_amount < min_amount) {
            wom_alert.msg({
                content: "请输入正确的结算金额范围！",
                delay_time: 1500
            })
            return false;
        }
        if (settlement_start_time != "" && settlement_end_time != "") {
            $(".settlement-time-selected span").removeClass("on");
        }
        if (!isNaN(min_amount) && !isNaN(max_amount)) {
            $(".amount-money-selected span").removeClass("on");
        }
    })

    // ************************************************
    // ***  wom uploader
    // *** 上传图片/文件
    // ************************************************
    // ========= 上传按钮与上传结果展示区域的对应 =========
    var wom_uploader_setting = {
        'id-upload-001-btn': 'id-upload-001-preview-area',
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

    // 上传凭证截图
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
        csrf: $('input#csrf').val()
    });

    //付款modal层
    $(".btn-submit").on("click",function () {
        var pic_proof_length = $(".file-list li").length,
            this_reply_msg = $(".reply-msg").find("textarea").val(),
            this_error_msg = $(".error-msg");

        if(pic_proof_length < 1){
            this_error_msg.text("请上传凭证！");
            return false;
        }

        wom_alert.msg({
            content:"修改成功!",
            delay_time:1500
        })

    })

    // 判断任务数量
    calculateTaskAccount();
    function calculateTaskAccount() {
        var task_account = $('.table tbody').find('tr').length;
        if(task_account < 1){
            $('.no-task').show();
        }else{
            $('.no-task').hide();
        }
        if(task_account < 20){
            $('.page-wb').hide();
        }else{
            $('.page-wb').show();
        }
    }
})