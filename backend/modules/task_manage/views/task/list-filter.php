<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use backend\modules\task_manage\models\Task;
?>
<!-- 面包屑 -->
<div class="bread">
    <ol class="breadcrumb font-500">
        <li><a href="#">首页</a></li>
        <li><a href="#">任务管理</a></li>
        <li class="active">任务列表</li>
    </ol>
</div>
<div class="con-header">
    <?php $form = ActiveForm::begin(["action"=>Url::to(["/task-manage/task/list-filter"]),"options"=>['class'=>"ListFilterForm"]]) ?>
        <div class="con-header-top clearfix">
            <div class="order-status condition-area">
                <span class="title">领取状态:</span>
                <div class="dropdown  order-status-select-area">
                    <div class="clearfix" data-toggle="dropdown">
                        <span class="show-default fl">全部</span>
                        <span class="caret fr"></span>
                    </div>
                    <ul class="dropdown-menu order-status-select" role="menu">
                        <li>全部</li>
                        <li data-status="<?= Task::GettingStatusWaitingStart?>">未开始</li>
                        <li data-status="<?= Task::GettingStatusEnable?>">可领取</li>
                        <li data-status="<?= Task::GettingStatusEnd?>">已结束</li>
                        <li data-status="<?= Task::StatusUnKnow?>">待发布</li>
                    </ul>
                    <input type="hidden" name="ListFilterForm[getting_status]">
                </div>
            </div>
            <div class="execute-status condition-area">
                <span class="title">执行状态:</span>
                <div class="dropdown  order-status-select-area">
                    <div class="clearfix" data-toggle="dropdown">
                        <span class="show-default fl">全部</span>
                        <span class="caret fr"></span>
                    </div>
                    <ul class="dropdown-menu order-status-select" role="menu">
                        <li data-status="0">全部</li>
                        <li data-status="<?= Task::ExecutingStatusWaiting?>">待执行</li>
                        <li data-status="<?= Task::ExecutingStatusExecuting?>">执行中</li>
                        <li data-status="<?= Task::ExecutingStatusEnd?>">已结束</li>
                    </ul>
                    <input type="hidden" name="ListFilterForm[executing_status]">
                </div>
            </div>
            <div class="publish-time condition-area">
                <span class="title">发布时间:</span>
                <input type="text" name="ListFilterForm[publish_start_time]" class="datetimepicker" placeholder="请选择开始时间"> 至
                <input type="text" name="ListFilterForm[publish_end_time]"  class="datetimepicker" placeholder="请选择结束时间">
                <i class="start-icon"></i>
                <i class="end-icon"></i>
            </div>
        </div>
        <div class="search-area">
            <input class="search-input form-control" type="text" name="ListFilterForm[titleOrSerialNumber]" placeholder="请输入任务名称或任务编号进行搜索">
            <button type="button" class="btn btn-search bg-main">搜索</button>
        </div>
    <?php ActiveForm::end()?>
</div>

<?php
$Js = <<<JS
$(function() {
    $('.ListFilterForm').on('click',".order-status-select li,.btn-search",function() {
        var pagination = new Pagination();
        
        pagination.appendAfterListFilterFormSubmit(new Array(
            ".operation",
            ".btn-stop",
            "click",
            "TerminateTask"
        ));
        
        pagination.appendAfterPagination(new Array(
            ".operation",
            ".btn-stop",
            "click",
            "TerminateTask"
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
