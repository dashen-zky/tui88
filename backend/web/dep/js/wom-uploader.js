var wom_uploader = {
    init: function (uploader_btn_id, uploader_config) {
        var max_file_size = '2mb';
        if (typeof uploader_config.max_file_size != undefined && uploader_config.max_file_size != '') {
            max_file_size = uploader_config.max_file_size;
        }
        var file_ext_accept = 'jpeg,jpg,gif,png';
        if (typeof uploader_config.file_ext_accept != undefined && uploader_config.file_ext_accept != '') {
            file_ext_accept = uploader_config.file_ext_accept;
        }
        var uploader = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4', //用来指定上传方式，默认选择最合适的。(可以不配置)
            browse_button: '' + uploader_btn_id + '', //触发文件选择对话框的按钮，为那个元素id
            url: uploader_config['upload_url'], //服务器端的上传页面地址
            flash_swf_url: 'js/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
            silverlight_xap_url: 'js/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
            multi_selection: false, //是否可以在文件浏览对话框中选择多个文件
            multipart_params: {
                _csrf: uploader_config['csrf']
            },
            // 筛选条件
            filters: {
                max_file_size: max_file_size, //限制上传图片的大小
                mime_types: [
                    {title: "upload file", extensions: file_ext_accept}, //限制文件类型
                ],
                multi_selection: false
            },
            init: {
                FilesAdded: function (up, files) {
                    uploader_config['file_added'](files);
                    uploader.start();
                },

                UploadProgress: function (up, file) {
                    uploader_config['upload_progress'](file);
                },

                FileUploaded: function (uploader, file, resp) {
                    uploader_config['file_uploaded'](file, resp);
                }
            }
        });
        uploader.init();
    }
};

// 使用plupload中的mOxie对象,展示图片
function previewImage(file, callback) {//file为plupload事件监听函数参数中的file对象,callback为预览图片准备完成的回调函数
    if (!file || !/image\//.test(file.type)) return; //确保文件是图片
    if (file.type == 'image/gif') {//gif使用FileReader进行预览,因为mOxie.Image只支持jpg和png
        var fr = new mOxie.FileReader();
        fr.onload = function () {
            callback(fr.result);
            fr.destroy();
            fr = null;
        }
        fr.readAsDataURL(file.getSource());
    } else {
        var preloader = new mOxie.Image();
        preloader.onload = function () {
            preloader.downsize(100, 100);//先压缩一下要预览的图片,宽100，高100
            var imgsrc = preloader.type == 'image/jpeg' ? preloader.getAsDataURL('image/jpeg', 80) : preloader.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
            callback && callback(imgsrc); //callback传入的参数为预览图片的url
            preloader.destroy();
            preloader = null;
        };
        preloader.load(file.getSource());
    }
}