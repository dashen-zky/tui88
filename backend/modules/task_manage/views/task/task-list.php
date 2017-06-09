<?php
use backend\assets\AppAsset;

$this->title = "任务列表";
AppAsset::addCss($this,'@web/src/css/reset.css?v=' . Yii::$app->params['static_version']);
AppAsset::addCss($this,'@web/src/css/site-stage-common.css?v=' . Yii::$app->params['static_version']);
AppAsset::addCss($this,"@web/src/css/task-manage/task-list.css?v=" . Yii::$app->params['static_version']);
?>

<div class="content-r fl my-panel">
    <?= $this->render("list-filter");?>
    <div class="task-table list">
        <?= $this->render("list",[
            "task_lists" => $task_lists,
        ]);?>
    </div>
</div>

<?php
$Js = <<<JS
$('.list .pagination li').on('click', function() {
        var pagination = new Pagination();
        pagination.appendAfterPagination(new Array(
            ".operation",
            ".btn-stop",
            "click",
            "TerminateTask"
        ));
        pagination.pagination($(this));
});
JS;
$this->registerJs($Js, \yii\web\View::POS_END);
?>


<?php
AppAsset::addScript($this,"@web/src/js/task-manage/task-list.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/pagination.js?v=" . Yii::$app->params['static_version']);
?>
