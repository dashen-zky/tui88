<?php
use backend\assets\AppAsset;

$this->title = "推客列表";
?>
<?php AppAsset::addCss($this,'@web/src/css/reset.css?v=' . Yii::$app->params['static_version']) ?>
<?php AppAsset::addCss($this,'@web/src/css/site-stage-common.css?v=' . Yii::$app->params['static_version']); ?>
<?php AppAsset::addCss($this,'@web/src/css/user-manage/user-list.css?v=' . Yii::$app->params['static_version']); ?>

<!-- 主要内容部分-->
<div class="content-r fl my-panel">
    <?= $this->render("list-filter")?>
    <div class="list">
        <?= $this->render("list",[
            "user_lists" => $user_lists,
            "orderBy" => $orderBy,
        ])?>
    </div>
</div>

<?php
$Js = <<<JS
$('.list .pagination li').on('click', function() {
        var pagination = new Pagination();
        
        pagination.appendAfterPagination(new Array(
            ".operate",
            ".blacklist",
            "click",
            "pullBlackOrRestore"
        ));
        pagination.appendAfterPagination(new Array(
            ".operate",
            ".resume",
            "click",
            "pullBlackOrRestore"
        ));
        
         pagination.appendAfterPagination(new Array(
            ".list thead",
            ".sort",
            "click",
            "Sort"
        ));
        
        pagination.pagination($(this));
});
JS;
$this->registerJs($Js, \yii\web\View::POS_END);
?>

<?php
AppAsset::addScript($this,"@web/src/js/user-manage/user-list.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/pagination.js?v=" . Yii::$app->params['static_version']);
?>