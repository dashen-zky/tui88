<?php
use backend\assets\AppAsset;

$this->title = '结算列表';
AppAsset::addCss($this,"@web/src/css/reset.css");
AppAsset::addCss($this,"@web/src/css/site-stage-common.css");
AppAsset::addCss($this,"@web/src/css/settlement-manage/settlement.css");
?>
<div class="content-r fl my-panel">
    <?= $this->render("settling-list-filter")?>
    <div class="list">
        <?= $this->render("settling-list",[
            "settling_lists" => $settling_lists,
        ])?>
    </div>
</div>

<?php
$Js = <<<JS
$('.list .pagination li').on('click', function() {
        var pagination = new Pagination();
        pagination.appendAfterPagination(new Array(
            ".total-account",
            ".settlement-all",
            "click",
            "AllSettlement"
        ));
        
        pagination.appendAfterPagination(new Array(
            ".list",
            ".all-select",
            "click",
            "CheckAll"
        ));
        pagination.appendAfterPagination(new Array(
            ".list",
            ".order-checked",
            "click",
            "allchk"
        ));
        pagination.pagination($(this));
});
JS;
$this->registerJs($Js, \yii\web\View::POS_END);
?>

<?php
AppAsset::addScript($this,"@web/dep/js/tui88-tool.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/site-stage-common.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/settlement-manage/settlement.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/pagination.js?v=" . Yii::$app->params['static_version']);
?>