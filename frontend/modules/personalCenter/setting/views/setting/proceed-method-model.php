<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$finance = json_decode(Yii::$app->session["user_information"]["finance_information"],true);
?>
<!-- 绑定银行卡modal层-->
<div id="bind-card" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="fl">收款方式</span><i class="close fr" data-dismiss="modal"></i></div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'bind-bank-card',
                    'action' => Url::to(["/personal-center/setting/setting/check-bind-bank"]),
                    'options' => ["class"=>"bind-card-form"],
                ])?>
                <div class="info-group mb-10">
                    <span>银行账户：</span>
                    <input type="text" class="form-control bank-card-num" name="BindBankCardForm[bank_card_num]" value="<?= isset($finance['bank_card_num']) ? $finance['bank_card_num'] : '';?>">
                    <p class="error-alert">请输入正确的银行卡号</p>
                </div>
                <div class="info-group ph-num mb-10">
                    <span>所属银行：</span>
                    <input type="text" class="form-control bank-name-opening" name="BindBankCardForm[bank_name_opening]" value="<?= isset($finance['bank_name_opening']) ? $finance['bank_name_opening'] : '';?>">
                    <p class="error-alert">请输入开户行</p>
                </div>
                <div class="info-group mb-10">
                    <span>姓名：</span>
                    <input type="text" class="form-control bank-card-name" name="BindBankCardForm[bank_card_name]" value="<?= isset($finance['bank_card_name']) ? $finance['bank_card_name'] : '';?>">
                    <p class="error-alert">请输入姓名</p>
                </div>
                <button type="button" class="save-btn">保存</button>
                <?php ActiveForm::end()?>
            </div>
        </div>
    </div>
</div>
