<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<!--补充收款信息modal-->
<div id="add-collection-info" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <?php $form = ActiveForm::begin([
            "action" => Url::to(["/personal-center/setting/setting/check-bind-bank"]),
        ])?>
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="fl">补充收款信息</span><i
                        class="close fr" data-dismiss="modal"></i></div>
            <div class="modal-body">
                <div class="info-group new-ph-num mb-10">
                    <span>收款类型：</span>
                    <span>银行卡</span>
                </div>
                <div class="info-group">
                    <span>所属银行：</span>
                    <input class="bank-name form-control" type="text" name="BindBankCardForm[bank_name_opening]" placeholder="请输入所属银行">
                </div>
                <div class="info-group">
                    <span>银行卡号：</span>
                    <input class="card-number form-control" name="BindBankCardForm[bank_card_num]" type="text" placeholder="请输入收款的银行账号">
                </div>
                <div class="info-group">
                    <span>收款姓名：</span>
                    <input class="bank-card-name form-control" name="BindBankCardForm[bank_card_name]" type="text" placeholder="请输入收款的姓名">
                </div>
                <button type="button" class="btn-submit" data-toggle="modal" data-target="#confirm-get-task" url="<?= Url::to(['/persanal-center/setting/setting/check-bind-bank'])?>">提交
                </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
    </div>
</div>
<!--是否确认领取任务modal-->
<div id="confirm-get-task" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="fl"></span><i class="close fr" data-dismiss="modal"></i>
            </div>
            <div class="modal-body">
                <h3>是否确认领取该任务?</h3>
                <div class="task-name color-main">任务名称：<span></span></div>
                <div class="get-task-num">领取任务数量:
                    <button class="reduce">-</button><input type="text" class="get-task-number form-control" value="1" /><button class="add">+</button>
                    <span>(剩余数量 <i class="max-task-num">10</i> )</span>
                </div>
                <div>
                    <button class="btn-sure" data-target="#get-success" data-toggle="modal" data-dismiss="modal">确认
                    </button>
                    <button class="cancel" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
</div>
