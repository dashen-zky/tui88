<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- 修改modal-->
<div id="modal-modify" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="fl">修改</span><i class="close fr" data-dismiss="modal"></i></div>
            <?php $form = ActiveForm::begin([
                    "action" => Url::to(["/personal-center/task-manage/task/submit-task"]),
                    "options" =>["class"=>"modify-form"],
            ])?>
            <input type="hidden" name="SubmitTaskForm[id]" class="task-map-id">
            <input type="hidden" name="SubmitTaskForm[task_uuid]" class="task-uuid">
            <div class="modal-body">
                <div class="info-group">
                    <span class="title">身份证:</span>
                    <input class="id-card-input form-control" name="SubmitTaskForm[id_card]" type="text" placeholder="请输入身份证号">
                </div>
                <div class="info-group">
                    <span class="title">手机号:</span>
                    <input class="tel-input form-control" name="SubmitTaskForm[phone]" type="text" placeholder="请输入手机号">
                </div>
                <div class="info-group">
                    <span class="title">注册账号:</span>
                    <input class="regist-account-input form-control" name="SubmitTaskForm[register_account]" type="text" placeholder="请输入注册账号">
                </div>
                <div class="info-group">
                    <span class="title">截图:</span>
                    <button id="id-upload-002-btn" class="btn btn-up-file bg-main" for="id-upload-001-preview-area">上传</button>
                </div>
                <div id="id-upload-002-preview-area" class="upload-preview-area">
                    <ul class="file-list">

                    </ul>
                </div>
                <div class="message info-group clearfix">
                    <span class="title fl">留言:</span>
                    <textarea class="message-input form-control" class="fl" name="SubmitTaskForm[message]" cols="30" rows="10"></textarea>
                </div>
                <p class="error-msg"></p>
                <button class="btn btn-modal-modify bg-main" data-dismiss="modal">提交</button>
                <input type="hidden" name="SubmitTaskForm[task_screen_shots]" class="task-screen-shots">
            </div>
            <?php ActiveForm::end()?>
        </div>
    </div>
</div>