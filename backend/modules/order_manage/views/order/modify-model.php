<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>

<!--修改modal层-->
<div id="modal-modify" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <?php $form = ActiveForm::begin([
            "action"=>Url::to(["/order-manage/order/modify-order-not-pass-to-pass"]),
            "options" => ["class"=>"OrderNotPassToPassForm"],
        ])?>
        <div class="modal-content">
            <input type="hidden" name="OrderNotPassToPassForm[id]" class="task-id">
            <input type="hidden" name="OrderNotPassToPassForm[dis_id]" class="dist-exec-id">
            <div class="modal-header clearfix"><span class="fl">修改</span><i class="close fr" data-dismiss="modal"></i></div>
            <div class="modal-body">
                <ul class="configuration-item clearfix">
                    <li><span class="title">身份证:</span><input class="input-id-card form-control" name="OrderNotPassToPassForm[id_card]" type="text" placeholder="请输入身份证号"></li>
                    <li><span class="title">手机:</span><input class="input-tel form-control" name="OrderNotPassToPassForm[phone]" type="text" placeholder="请输入手机号"></li>
                    <li><span class="title">注册账号:</span><input class="input-regist-account form-control" name="OrderNotPassToPassForm[register_account]" type="text" placeholder="请输入注册账号"></li>
                </ul>
                <div class="screenshots info-group">
                    <span class="title">截图:</span>
                    <button id="id-upload-002-btn" type="button" class="btn btn-up-file bg-main" for="id-upload-001-preview-area">上传</button>
                </div>
                <div id="id-upload-002-preview-area" class="upload-preview-area">
                    <ul class="file-list">

                    </ul>
                    <input type="hidden" name="OrderNotPassToPassForm[task_screen_shots]" class="task-screen-shots">
                </div>
                <div class="modify-account info-group">
                    <span class="title">确认金额:</span>
                    <input class="insure-money form-control" type="text" name="OrderNotPassToPassForm[insure_money]">
                    <span class="task-account">任务金额: <i>0.00</i></span>
                    <input class="unit-money form-control" type="hidden" name="OrderNotPassToPassForm[unit_money]">
                </div>
                <div class="reply-msg info-group">
                    <span class="title">留言:</span><textarea class="form-control" name="OrderNotPassToPassForm[message]" id="" cols="30" rows="10"></textarea>
                </div>
                <p class="error-msg"></p>
                <div class="btn-group-checkd">
                    <button type="button" class="btn btn-modal-pass bg-main" data-dismiss="modal">通过</button>
                </div>
            </div>
        </div>
        <?php ActiveForm::end()?>
    </div>
</div>
