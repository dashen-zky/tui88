<?php
use backend\assets\AppAsset;
$this->title = '个人结算列表';

AppAsset::addCss($this,"@web/src/css/reset.css?v=" . Yii::$app->params['static_version']);
AppAsset::addCss($this,"@web/src/css/site-stage-common.css?v=" . Yii::$app->params['static_version']);
AppAsset::addCss($this,"@web/src/css/settlement-manage/user-order-list.css?v=" . Yii::$app->params['static_version']);
?>

<!-- 主要内容部分-->
<div class="main-wrap my-panel clearfix">
    <?= $this->render("order-list-filter")?>
    <div class="list">
        <?= $this->render("order-list",[
            "order_lists" => $order_lists,
            "ser_filter" => $ser_filter,
        ])?>
    </div>
</div>

<?php
$Js = <<<JS
$('.list .pagination li').on('click', function() {
        var pagination = new Pagination();
        pagination.appendAfterPagination(new Array(
            ".task-table",
            ".all-select",
            "click",
            "CheckAll"
        ));
        pagination.appendAfterPagination(new Array(
            ".list tbody",
            ":checkbox",
            "click",
            "allchk"
        ));
        pagination.pagination($(this));
});
JS;
$this->registerJs($Js, \yii\web\View::POS_END);
?>

<?php
AppAsset::addScript($this,"@web/src/js/settlement-manage/user-order-list.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/pagination.js?v=" . Yii::$app->params['static_version']);
?>
