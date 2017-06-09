<?php
use backend\assets\AppAsset;

$this->title = '结算管理-结算记录';
AppAsset::addCss($this,"@web/src/css/reset.css");
AppAsset::addCss($this,"@web/src/css/site-stage-common.css");
AppAsset::addCss($this,"@web/src/css/settlement-manage/settlement-record.css");
?>

<!-- 主要内容部分-->
<div class="content-r my-panel fl">
    <?= $this->render("settled-record-filter")?>
    <div class="list">
        <?= $this->render("settled-record",[
            "settled_records" => $settled_records,
            "ser_filter" => isset($ser_filter) ? $ser_filter : null,
        ])?>
    </div>
</div><div id="proof" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
<?= $this->render("settled-proof") ?>
</div>
<?php
$Js = <<<JS
$('.list .pagination li').on('click', function() {
        var pagination = new Pagination();
        pagination.pagination($(this));
});
JS;
$this->registerJs($Js, \yii\web\View::POS_END);
?>

<?php
AppAsset::addScript($this,"@web/src/js/pagination.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/settlement-manage/settlement-record.js?v=" . Yii::$app->params['static_version']);
?>