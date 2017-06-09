$(function(){
    $('.status-wrap li').on('click',function(){
        $(this).addClass('on-click').siblings().removeClass('on-click');
    })


    //开发固定modal（待删除）

    // $("#give-up").modal({backdrop: "static", keyboard: false});

    // $("#paying-voucher").modal({backdrop: "static", keyboard: false});

    // $("#reason").modal({backdrop: "static", keyboard: false});

    // $("#modal-submit").modal({backdrop: "static", keyboard: false});

    // $("#modal-modify").modal({backdrop: "static", keyboard: false});



    //搜索
    $('.btn-search').on('click',function () {
        var search_con = $('.search-input').val();
        if(search_con == ""){
            return false;
        }else{
            // to do
            alert("hello zky!");
        }
    })

    //放弃modal
    $('.btn-modal-reason').on('click',function () {
        var reason_con = $('.reason-show textarea').val();
        if(reason_con == ""){
            $('#give-up').find('.error-msg').text('请填写放弃原因');
            return false;
        }
    })

    $('.reason-show textarea').on('blur',function () {
        abandonReasonFillIn();
    })
    function abandonReasonFillIn(){
        var reason_con = $('.reason-show textarea').val();
        var this_error_msg = $('#give-up').find('.error-msg');
        if(reason_con == ""){
            this_error_msg.text('请填写放弃原因');
        }else{
            this_error_msg.text('');
        }
    }

    // 提交
    $('.btn-modal-submit').on('click',function () {
        var id_card_num = $('#modal-submit').find('.id-card-input').val();
        var tel = $('#modal-submit').find('.tel-input').val();
        var regist_account = $('#modal-submit').find('.regist-account-input').val();
        var message_info = $('#modal-submit').find('.message-input').val();

        if(id_card_num == ""){

        }



    })

    // 修改
    $('.btn-modal-modify').on('click',function () {
        var id_card_num = $('#modal-modify').find('.id-card-input').val();
        var tel = $('#modal-modify').find('.tel-input').val();
        var regist_account = $('#modal-modify').find('.regist-account-input').val();
        var message_info = $('#modal-modify').find('.message-input').val();



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
        csrf: $('input#csrf').val()
    });

    // 删除图片
    $(".upload-preview-area").on('click', '.file-list .delete-pic', function(){
        var this_img = $(this).closest('li');
        var img_name = $(this).data('img-name');
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
                img_name: img_name
            },
            success: function (resp) {
                if (resp.err_code == 0) {
                    this_img.remove();
                } else if(resp.err_code == 1){
                    wom_alert.msg({
                        content:"系统异常",
                        delay_time: 1500
                    });
                    return false;
                }
            },
            error: function (XMLHttpRequest, msg, errorThrown) {
                wom_alert.msg({
                    content:"系统异常",
                    delay_time: 1500
                });
                return false;
            }
        });
    });


    // 判断任务数量
    $(document).ready(function () {
        var task_account = $('.task-tb-info tbody').find('tr').length;
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
    })
})