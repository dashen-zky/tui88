<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!--审核modal层-->
<div id="modal-check" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="fl">审核</span><i class="close fr" data-dismiss="modal"></i></div>
            <?php ActiveForm::begin(["action" => Url::to(["/order-manage/order/check-order"])]) ?>
            <input type="hidden" name="auditOrderForm[id]" class="task-id">
            <input type="hidden" name="auditOrderForm[dis_id]" class="dist-exec-id">
            <input type="hidden" name="auditOrderForm[method]" class="method">
            <div class="modal-body">
                    <ul class="configuration-item clearfix">
                        <li><span class="title">身份证:</span><i></i></li>
                        <li><span class="title">手机:</span><i></i></li>
                        <li><span class="title">注册账号:</span><i></i></li>
                    </ul>
                    <div class="pic-show">
                        <span class="title">截图:</span>
                        <div class="pic">

                        </div>
                    </div>
                    <p class="remarks clearfix info-group">
                        <span class="title fl">留言:</span>
                        <span class="fl">具体看截图</span>
                    </p>
                    <div class="modify-account info-group">
                        <span class="title">修改金额:</span><input class="form-control insure-money" type="text" name="auditOrderForm[insure_money]">
                        <span class="unit-account">任务金额: <i>0.00</i></span>
                        <input type="hidden" name="auditOrderForm[unit_money]" class="unit_money">
                    </div>
                    <div class="reply-msg info-group">
                        <span class="title">留言:</span><textarea class="form-control" name="auditOrderForm[check_remarks]" cols="30" rows="10"></textarea>
                    </div>
                    <p class="error-msg"></p>
                    <div class="btn-group-checked">
                        <button type='button' class="btn btn-modal-pass bg-main" data-dismiss="modal" method="pass">通过</button>
                        <button type='button' class="btn btn-modal-no-go bg-main" data-dismiss="modal" method="not_pass">不通过</button>
                    </div>
                </div>
            <?php ActiveForm::end()?>
        </div>
    </div>
</div>