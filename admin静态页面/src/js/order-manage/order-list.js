$(function () {
    // layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });

    //条件全选
    var check_all = $(".check-all input");
    var check_single = $(".condition-selected :checkbox");

    check_all.on("click",function(){
        if(this.checked){
            check_single.prop("checked",true);
        }else{
            check_single.prop("checked",false);
        }
    })
    //全选复选框的选中与否
    check_single.click(function(){
        allchk();
    });
    function allchk(){
        var chknum = check_single.size();//选项总个数
        var chk = 0; //已选中的个数
        check_single.each(function () {
            if($(this).prop("checked") == true){
                chk++;
            }
        });
        if(chknum == chk){ //全选时选中
            check_all.prop("checked",true);
        }else{  //不全选取消选中
            check_all.prop("checked",false);
        }
    }

    // 搜索
    $(".btn-search").on("click",function () {
        var search_con = $(".search-input").val(),
            publish_start_time = $(".establish-time").children("input[name='establish_start_time']").val(),
            publish_end_time = $(".establish-time").children("input[name='establish_end_time']").val();


    })


    // $("#modal-modify").modal({backdrop: "static", keyboard: false});

    //审核modal层
    $("#modal-check .btn-modal-pass").on("click",function(){
        var account_modify = $("#modal-check .modify-account input").val(),
            this_reply_msg = $("#modal-check .reply-msg").find("textarea").val(),
            this_error_msg = $("#modal-check .error-msg");

        if(isNaN(account_modify)){
            this_error_msg.text("金额应为数字");
            return false;
        }

        wom_alert.msg({
            content:"审核通过！",
            delay_time:1500
        })

    })


    //修改modal层
    $("#modal-modify .btn-modal-pass").on("click",function () {
        var id_card = $(".input-id-card").val(),
            tel = $(".input-tel").val(),
            regist_account = $(".input-regist-account").val(),
            account_modify = $("#modal-modify .modify-account input").val(),
            this_reply_msg = $("#modal-modify .reply-msg").find("textarea").val(),
            this_error_msg = $("#modal-modify .error-msg"),
            cell_phone_reg = /^[1-9]\d{10}$/;

        //身份证号
        if(id_card == ""){
            this_error_msg.text("请输入身份证号");
            return false;
        }
        if(isNaN(id_card) || id_card.length != 18){
            this_error_msg.text("身份证号应为18位数字");
            return false;
        }
        //手机号
        if(tel == ""){
            this_error_msg.text("请输入手机号");
            return false;
        }
        if(isNaN(id_card) || !cell_phone_reg.test(tel)){
            this_error_msg.text("手机号应为11位数字");
            return false;
        }
        //注册账号
        if(regist_account == ""){
            this_error_msg.text("请输入注册账号");
            return false;
        }

        wom_alert.msg({
            content:"修改成功!",
            delay_time:1500
        })

    })


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
        csrf: $('input#csrf').val()
    });



    calculateTaskAccount();
    // 判断任务数量
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