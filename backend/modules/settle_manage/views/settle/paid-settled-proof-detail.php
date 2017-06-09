<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header clearfix"><span class="fl">付款凭证</span><i class="close fr" data-dismiss="modal"></i></div>
        <?php if (isset($payment_info) && !empty($payment_info)):?>
        <div class="modal-body">
            <ul class="configuration-item clearfix">
                <?php foreach ($payment_info["base_info"] as $key => $value):?>
                <li><span class="title"><?= $value["name"]?>:</span><i><?= $value["value"]?></i></li>
                <?php endforeach;?>
            </ul>
            <div class="pic-show">
                <span class="title">上传凭证:</span>
                <div class="pic">
                    <?php foreach ($payment_info["attachment"] as $value):?>
                    <img src="<?= Yii::$app->params['adminImageBaseUrl'].$value?>" height="100px" width="90px" alt="" />
                    <?php endforeach;?>
                </div>
            </div>
            <p class="remarks clearfix info-group">
                <span class="title fl">留言:</span>
                <span class="fl"><?= $payment_info["remarks"]?></span>
            </p>
        </div>
        <?php endif;?>
    </div>
</div>
