<?php
use frontend\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);

$this->title = "任务管理";
AppAsset::addCss($this,'@web/src/css/site-stage-layout-common.css?v=' . Yii::$app->params['static_version']);
AppAsset::addCss($this,"@web/src/css/personal-center/task-manage.css?v=" . Yii::$app->params['static_version']);

?>

<input type="hidden" id="id-upload-file-url" value="<?= Url::to(['/personal-center/task-manage/task/file-upload'])?>">
<input type="hidden" id="id-delete-file-url" value="<?= Url::to(['/personal-center/task-manage/task/file-delete'])?>">
<div class="content my-panel">
    <?= $this->render("list-filter");?>
    <div class="task-table list">
        <?= $this->render("list",[
            "task_lists" => $task_lists,
            'ser_filter'=>isset($ser_filter) ? $ser_filter : '',
        ]);?>
    </div>
</div>
<?php
$Js = <<<JS
$('.list .pagination li').on('click', function() {
        var pagination = new Pagination();
        pagination.appendAfterPagination(new Array(
             '.operation',
            '.give-up',
            'click',
            'bindAbandonFormAndTaskId'
        ));
        
        pagination.appendAfterPagination(new Array(
             '.operation',
            '.submit-result',
            'click',
            'bindSubmitTaskModel'
        ));
        pagination.appendAfterPagination(new Array(
             '.operation',
            '.modify',
            'click',
            'bindSubmitTaskModel'
        ));
        
        pagination.appendAfterPagination(new Array(
             '.operation',
            '.evidence',
            'click',
            'getEvidence'
        ));
        pagination.pagination($(this));
});
JS;
$this->registerJs($Js, \yii\web\View::POS_END);
?>

<?= $this->render("give-up-model");?>

<?= $this->render("submit-model");?>

<?= $this->render("modify-model");?>

<!-- 收款凭证modal层-->
<div id="paying-voucher" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">

</div>

<?php
AppAsset::addScript($this,'@web/dep/js/jquery.datetimepicker.js?v=' . Yii::$app->params['static_version']);
AppAsset::addScript($this,'@web/dep/js/datetime.js?v=' . Yii::$app->params['static_version']);
?>
<?php
AppAsset::addScript($this,"@web/dep/plupload/plupload.full.min.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/dep/js/wom-uploader.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/personal-center/task-manage.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/pagination.js?v=" . Yii::$app->params['static_version']);
?>
