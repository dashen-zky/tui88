<?php
use frontend\assets\AppAsset;

$this->title = "任务大厅";
?>

<?= AppAsset::addCss($this,"@web/src/css/task-hall/task-hall-list.css?v=" . Yii::$app->params['static_version']); ?>
<!-- 主要内容部分-->
<div class="main-wrap clearfix my-panel">
<?= $this->render("list-panel",[
    "task_lists" => $task_lists,
    'ser_filter'=>isset($ser_filter)?$ser_filter:'',
]); ?>
</div>
<?= $this->render("task-list-model"); ?>
<?php AppAsset::addScript($this,"@web/src/js/task-hall/task-hall-list.js?v=" . Yii::$app->params['static_version']); ?>
