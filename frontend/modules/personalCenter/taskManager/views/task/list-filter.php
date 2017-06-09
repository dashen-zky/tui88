<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use frontend\modules\taskHall\models\ExecutorTaskMap;
?>
<!-- 面包屑 -->
    <div class="bread">
        <ol class="breadcrumb font-500">
            <li><a href="#">首页</a></li>
            <li><a href="#">个人中心</a></li>
            <li class="active">任务管理</li>
        </ol>
    </div>
    <div class="con-header">
        <?php $form = ActiveForm::begin([
                "id"=> '',
                "action" => Url::to(["/personal-center/task-manage/task/list-filter"]),
                "options"=>["class"=>"ListFilterForm"],
        ]) ?>
        <div class="status-wrap">
            <ul class="clearfix">
                <li class="on-click">所有任务</li>
                <li value="<?= ExecutorTaskMap::ExecutingStatusWaiting?>">待执行</li>
                <li value="<?= ExecutorTaskMap::ExecutingStatusExecuting?>">执行中</li>
                <li value="<?= ExecutorTaskMap::ExecutingStatusConfirm?>">待确认</li>
                <li value="<?= ExecutorTaskMap::ExecutingStatusDefeated?>">执行失败</li>
                <li value="<?= ExecutorTaskMap::ExecutingStatusReceivingWaiting?>">待收款</li>
                <li value="<?= ExecutorTaskMap::ExecutingStatusReceived?>">已收款</li>
                <input type="hidden" name="ListFilterForm[status]">
            </ul>
        </div>
        <div class="search-area">
            <input class="search-input form-control" name="ListFilterForm[titleOrOrderNum]" type="text" placeholder="请输入任务名称或任务编号进行搜索">
            <input type="text" style="display: none">
            <button type="button" class="btn btn-search bg-main">搜索</button>
        </div>
        <?php ActiveForm::end() ?>
    </div>

<?php
$Js = <<<JS
$(function() {
    $('.ListFilterForm').on('click', '.btn-search,.status-wrap li', function() {
        var pagination = new Pagination();
        pagination.appendAfterListFilterFormSubmit(new Array(
            '.operation',
            '.give-up',
            'click',
            'bindAbandonFormAndTaskId'
        ));
        
        pagination.appendAfterPagination(new Array(
            '.operation',
            '.give-up',
            'click',
            'bindAbandonFormAndTaskId'
        ));
        
        pagination.appendAfterListFilterFormSubmit(new Array(
            '.operation',
            '.submit-result',
            'click',
            'bindSubmitTaskModel'
        ));
        pagination.appendAfterPagination(new Array(
             '.operation',
            '.submit-result',
            'click',
            'bindSubmitTaskModel'
        ));
        
        pagination.appendAfterListFilterFormSubmit(new Array(
             '.operation',
            '.modify',
            'click',
            'bindSubmitTaskModel'
        ));
        
        pagination.appendAfterPagination(new Array(
             '.operation',
            '.modify',
            'click',
            'bindSubmitTaskModel'
        ));
        
        pagination.appendAfterListFilterFormSubmit(new Array(
             '.operation',
            '.evidence',
            'click',
            'getEvidence'
        ));
        pagination.appendAfterPagination(new Array(
             '.operation',
            '.evidence',
            'click',
            'getEvidence'
        ));
        pagination.listFilterFormSubmit($(this));
    });
    //绑定enter键
    $('.search-input').bind('keypress',function(event){
        if(event.keyCode == "13") {
            $('.ListFilterForm .btn-search').trigger("click");
        }
    });
});

JS;
$this->registerJs($Js, \yii\web\View::POS_END);
?>


