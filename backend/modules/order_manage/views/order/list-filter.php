<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use backend\modules\order_manage\models\Order;
?>
<!-- 面包屑 -->
<div class="bread">
    <ol class="breadcrumb font-500">
        <li><a href="#">首页</a></li>
        <li class="active">订单管理</li>
    </ol>
</div>
<div class="con-header">
    <?php $form = ActiveForm::begin(["action"=>Url::to(["/order-manage/order/list-filter"]),"options"=>["class"=>"ListFilterForm"]]) ?>
    <input class="same-batch" type="hidden" name="ListFilterForm[task_uuid]" value="<?= $task_uuid?>">
    <div class="con-header-top">
        <span class="title">执行状态:</span>
        <label class="check-all"><input type="checkbox" <?= Yii::$app->request->get("task_uuid") ? 'checked="checked"': '';?> > 全部</label>
        <div class="condition-selected">
            <label><input name="ListFilterForm[status][]" value="<?= Order::ExecutingStatusConfirm?>" type="checkbox" checked="checked"> 待确认</label>
            <label><input name="ListFilterForm[status][]" value="<?= Order::ExecutingStatusReceivingWaiting?>" type="checkbox" <?= Yii::$app->request->get("task_uuid") ? 'checked="checked"': '';?> > 待收款</label>
            <label><input name="ListFilterForm[status][]" value="<?= Order::ExecutingStatusReceived?>" type="checkbox" <?= Yii::$app->request->get("task_uuid") ? 'checked="checked"': '';?> > 已收款</label>
            <label><input name="ListFilterForm[status][]" value="<?= Order::ExecutingStatusNotPass?>" type="checkbox" <?= Yii::$app->request->get("task_uuid") ? 'checked="checked"': '';?> > 失败-未通过</label>
            <label><input name="ListFilterForm[status][]" value="<?= Order::ExecutingStatusExpired?>" type="checkbox" <?= Yii::$app->request->get("task_uuid") ? 'checked="checked"': '';?> > 失败-已过期</label>
            <label><input name="ListFilterForm[status][]" value="<?= Order::ExecutingStatusTerminated?>" type="checkbox" <?= Yii::$app->request->get("task_uuid") ? 'checked="checked"': '';?> > 失败-已终止</label>
            <label><input name="ListFilterForm[status][]" value="<?= Order::ExecutingStatusGiveUp?>" type="checkbox" <?= Yii::$app->request->get("task_uuid") ? 'checked="checked"': '';?> > 失败-已放弃</label>
            <label><input name="ListFilterForm[status][]" value="<?= Order::ExecutingStatusWaiting?>" type="checkbox" <?= Yii::$app->request->get("task_uuid") ? 'checked="checked"': '';?> > 待执行</label>
            <label><input name="ListFilterForm[status][]" value="<?= Order::ExecutingStatusExecuting?>" type="checkbox" <?= Yii::$app->request->get("task_uuid") ? 'checked="checked"': '';?> > 执行中</label>
        </div>
    </div>
    <div class="con-header-bot">
        <div class="establish-time condition-area">
            <span class="title">创建时间:</span>
            <input type="text" name="ListFilterForm[start_create_time]" class="datetimepicker" placeholder="请选择开始时间"> 至
            <input type="text" name="ListFilterForm[end_create_time]" class="datetimepicker" placeholder="请选择结束时间">
            <i class="start-icon"></i>
            <i class="end-icon"></i>
        </div>
        <div class="search-area">
            <input class="search-input form-control" name="ListFilterForm[titleOrSerialNumber]" type="text" placeholder="请输入订单编号、任务名称或任务编号进行搜索">
            <button type="button" class="btn btn-search bg-main">搜索</button>
        </div>
    </div>
    <?php ActiveForm::end()?>
</div>

<?php
$Js = <<<JS
$(function() {
    $('.ListFilterForm').on('click',".btn-search",function() {
        var pagination = new Pagination();
        pagination.appendAfterListFilterFormSubmit(new Array(
            ".operate",
            ".check",
            "click",
            "getCheckInformation"
        ));
        pagination.appendAfterPagination(new Array(
            ".operate",
            ".check",
            "click",
            "getCheckInformation"
        ));
        
        pagination.appendAfterListFilterFormSubmit(new Array(
            ".operate",
            ".same-batch",
            "click",
            "getSameBatchData"
        ));
        pagination.appendAfterPagination(new Array(
            ".operate",
            ".same-batch",
            "click",
            "getSameBatchData"
        ));
        
        pagination.appendAfterListFilterFormSubmit(new Array(
            ".operate",
            ".btn-modify",
            "click",
            "getCheckInformation"
        ));
        pagination.appendAfterPagination(new Array(
            ".operate",
            ".btn-modify",
            "click",
            "getCheckInformation"
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
