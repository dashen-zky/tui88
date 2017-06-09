<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header clearfix"><span class="fl">收款凭证</span><i class="close fr" data-dismiss="modal"></i></div>
        <div class="modal-body">
            <ul class="sum-show">
                <li><span>收款金额：</span><i><?= $payment_info['money'] ?></i>元</li>
                <li><span>任务数量：</span><i><?= $payment_info['number_of_order'] ?></i></li>
            </ul>
            <div class="pic-show">
                <span>收款凭证：</span>
                <div class="pic">
                    <?php foreach ($payment_info['attachment'] as $value): ?>
                        <img src="<?= Yii::$app->params['adminImageBaseUrl'].$value?>" height="100px" width="90px" alt="" />
                    <?php endforeach;?>
                </div>
                <p class="remarks clearfix">
                    <span class="title fl">留言：</span>
                    <span class="fl"><?= $payment_info['remarks'] ?></span>
                </p>
            </div>
        </div>
    </div>
</div>
