<?php
use backend\assets\AppAsset;

$this->title = '财务管理-付款记录';
AppAsset::addCss($this,"@web/src/css/reset.css?v=" . Yii::$app->params['static_version']);
AppAsset::addCss($this,"@web/src/css/site-stage-common.css?v=" . Yii::$app->params['static_version']);
AppAsset::addCss($this,"@web/src/css/fin-manage/payment-record.css?v=" . Yii::$app->params['static_version']);
?>
<div class="content-r fl my-panel">
    <?= $this->render("paid-settled-list-filter") ?>
    <div class="list">
        <?= $this->render("paid-settled-record",[
            "paid_settled_records" => $paid_settled_records,
            "ser_filter" => isset($ser_filter) ? $ser_filter : null,
        ]) ?>
    </div>

</div>
<?= $this->render("paid-settled-proof") ?>

<?php
$Js = <<<JS
$('.list .pagination li').on('click', function() {
        var pagination = new Pagination();
        pagination.appendAfterPagination(new Array(
            ".operate",
            ".get-payment-info",
            "click",
            "getPaymentInfo"
        ));
        pagination.pagination($(this));
});
JS;
$this->registerJs($Js, \yii\web\View::POS_END);
?>

<?php
AppAsset::addScript($this,"@web/src/js/fin-manage/payment-record.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/pagination.js?v=" . Yii::$app->params['static_version']);
?>
