<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<input type="hidden" id="id-upload-file-url" value="<?= Url::to(['/fin-manage/finance/file-upload'])?>">
<input type="hidden" id="id-delete-file-url" value="<?= Url::to(['/fin-manage/finance/file-delete'])?>">

<!--付款modal层-->
<div id="modal-payment" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php $form = ActiveForm::begin([
                "action" => Url::to(["/fin-manage/finance/payment-settled"]),
            ])?>
            <input type="hidden" name="Form[id]" class="finance-id">
            <div class="modal-header clearfix"><span class="fl">付款</span><i class="close fr" data-dismiss="modal"></i></div>
            <div class="modal-body">
                <ul class="configuration-item clearfix">
                    <li><span class="title">手机号:</span><i></i></li>
                    <li><span class="title">付款金额:</span><i></i></li>
                    <li><span class="title">收款人:</span><i></i></li>
                    <li><span class="title">收款银行:</span><i></i></li>
                    <li><span class="title">收款账号:</span><i></i></li>
                </ul>
                <div class="screenshot">
                    <span class="title">上传凭证:</span><button id="id-upload-001-btn" class="btn btn-up-file bg-main" for="id-upload-001-preview-area">上传</button>
                </div>
                <div id="id-upload-001-preview-area" class="upload-preview-area"></div>
                <input type="hidden" class="data-img-name" name="Form[img_names]">
                <div class="reply-msg info-group">
                    <span class="title">留言:</span><textarea class="form-control" name="Form[remarks]" cols="30" rows="10" placeholder="请输入留言"></textarea>
                </div>
                <p class="error-msg"></p>
                <div class="btn-group-checkd">
                    <button type="button" class="btn-submit btn bg-main" data-dismiss="modal">提交</button>
                </div>
            </div>
            <?php ActiveForm::end()?>
        </div>
    </div>
</div>
