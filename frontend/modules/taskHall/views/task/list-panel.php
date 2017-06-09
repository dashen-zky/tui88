<?php
use frontend\assets\AppAsset;
?>
<?= $this->render("list-filter-form",["totalCount"=>$task_lists["pagination"]->totalCount]); ?>
<div id="task-list" class="list">
    <?= $this->render('list', [
        'task_lists'=>$task_lists,
        'ser_filter'=>$ser_filter,
    ])?>
</div>

<?php
$Js = <<<JS
$('.list .pagination li').on('click', function() {
        var pagination = new Pagination();
        pagination.appendAfterPagination(new Array(
            '.operate',
            '.click',
            'click',
            'CheckQualificationSelf'
        ));
        pagination.pagination($(this));
});
JS;
$this->registerJs($Js, \yii\web\View::POS_END);
?>
<?php AppAsset::addScript($this,"@web/src/js/task-hall/getting-task.js?v=" . Yii::$app->params['static_version'])?>
