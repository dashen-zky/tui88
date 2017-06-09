<?php
use backend\assets\AppAsset;

$this->title = '财务管理-付款';
AppAsset::addCss($this,"@web/src/css/reset.css?v=" . Yii::$app->params['static_version']);
AppAsset::addCss($this,"@web/src/css/site-stage-common.css?v=" . Yii::$app->params['static_version']);
AppAsset::addCss($this,"@web/src/css/fin-manage/payment.css?v=" . Yii::$app->params['static_version']);

?>
<div class="content-r fl my-panel">
    <?= $this->render("paying-settled-list-filter",[
        "total_count" => $paying_settled_lists["pagination"]->totalCount,
    ]) ?>
    <div class="list">
        <?= $this->render("paying-settled-list",[
            "paying_settled_lists" => $paying_settled_lists,
            "ser_filter" => isset($ser_filter) ? $ser_filter : null,
        ])?>
    </div>
</div>
<?= $this->render("paying-settled-model") ?>

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
AppAsset::addScript($this,"@web/src/js/fin-manage/payment.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/pagination.js?v=" . Yii::$app->params['static_version']);

?>