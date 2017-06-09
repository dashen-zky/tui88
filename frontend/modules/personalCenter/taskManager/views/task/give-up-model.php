<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- 放弃modal层-->
<div id="give-up" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <?php $form = ActiveForm::begin([
            'action' => Url::to(["/personal-center/task-manage/task/abandon-task"]),
        ])?>
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="fl">放弃</span><i class="close fr" data-dismiss="modal"></i></div>
            <div class="modal-body">
                <p class="confirm-abandon">是否确认放弃执行该任务？</p>
                <div class="reason-show">
                    <span>原因：</span>
                    <textarea name="execute_failed_reason" class="form-control"></textarea>
                    <input type="hidden" name="id" class="order-id">
                </div>
                <p class="error-msg"></p>
            </div>
            <div class="modal-footer">
                <a class="btn-modal-reason" href="javascript:;" data-dismiss="modal">提交</a>
                <a href="javascript:;" data-dismiss="modal">取消</a>
            </div>
        </div>
        <?php ActiveForm::end()?>
    </div>
</div>