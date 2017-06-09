<?php
use backend\assets\AppAsset;
use yii\helpers\Url;

$this->title = "订单列表";

AppAsset::addCss($this,"@web/src/css/reset.css?v=" . Yii::$app->params['static_version']);
AppAsset::addCss($this,'@web/src/css/site-stage-common.css?v=' . Yii::$app->params['static_version']);
AppAsset::addCss($this,"@web/src/css/order-manage/order-list.css?v=" . Yii::$app->params['static_version']);
?>

<input type="hidden" id="id-upload-file-url" value="<?= Url::to(['/order-manage/order/file-upload'])?>">
<input type="hidden" id="id-delete-file-url" value="<?= Url::to(['/order-manage/order/file-delete'])?>">


<div class="content-r fl my-panel">
    <?= $this->render("list-filter",[
            "task_uuid" => isset($task_uuid) ? $task_uuid : '',
    ]);?>
    <div class="task-table list">
        <?= $this->render("list",[
            "order_lists" => $order_lists,
        ]);?>
    </div>
</div>

<?php
$Js = <<<JS
$('.list .pagination li').on('click', function() {
        var pagination = new Pagination();
        pagination.appendAfterPagination(new Array(
            ".operate",
            ".same-batch",
            "click",
            "getSameBatchData"
        ));
        pagination.appendAfterPagination(new Array(
            ".operate",
            ".check",
            "click",
            "getCheckInformation"
        ));
        pagination.appendAfterPagination(new Array(
            ".operate",
            ".btn-modify",
            "click",
            "getCheckInformation"
        ));
        pagination.pagination($(this));
});
JS;
$this->registerJs($Js, \yii\web\View::POS_END);
?>

<?= $this->render("check-model") ?>
<?= $this->render("modify-model") ?>




<?php
AppAsset::addScript($this,"@web/dep/layer/layer.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/dep/plupload/plupload.full.min.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/dep/js/wom-uploader.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/dep/js/tui88-tool.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/order-manage/order-list.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/order-manage/check.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/pagination.js?v=" . Yii::$app->params['static_version']);
?>
