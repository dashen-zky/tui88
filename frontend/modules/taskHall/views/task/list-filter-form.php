<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use frontend\modules\taskHall\models\Task;
use frontend\assets\AppAsset;
?>
    <div class="all-task">全部任务 <span class="color-main">( <i><?= $totalCount?></i> ) </span></div>
    <?php $form = ActiveForm::begin([
            'id' => 'task-list-filter',
            "action" => Url::to(["/task-hall/task/task-list-filter"]),
            'options'=>[
                'class'=>'ListFilterForm'
            ]
    ])?>
<div class="search-con clearfix">
    <ul class="category clearfix fl">
        <li class="choosed" value="<?= Task::CompositeOrder?>">综合</li>
        <li value="<?= Task::DistributedTimeDesc?>">发布时间<span class="sort-icon"><i class="up"></i><i class="down"></i></span></li>
        <li class="surplus" value="<?= Task::RemainNumDesc?>">剩余数量<span class="sort-icon"><i class="up"></i><i class="down"></i></span></li>
        <li value="<?= Task::UnitMoneyDesc?>">单价<span class="sort-icon"><i class="up"></i><i class="down"></i></span></li>
        <input type="hidden" name="ListFilterForm[orderBy]" value="<?= Task::CompositeOrder?>">
    </ul>
    <div class="condition-area order-status clearfix fl">
        <span class="fl">领取状态:</span>
        <div class="dropdown fl order-status-select-area">
            <div class="clearfix" data-toggle="dropdown">
                <span class="show-default fl">全部</span>
                <input type="hidden" name="ListFilterForm[getting_status]" value="">
                <span class="caret fr"></span>
            </div>
            <ul class="dropdown-menu order-status-select" role="menu">
                <li value="">全部</li>
                <li value="<?= Task::GettingStatusWaitingStart?>">未开始</li>
                <li value="<?= Task::GettingStatusEnable?>">可领取</li>
            </ul>
        </div>
    </div>
    <div class="unit-price fl">
        <span>任务金额 :</span>
        <input class="min-price" name="ListFilterForm[min_price]" type="text" placeholder="￥" /> <i>至</i> <input class="max-price" name="ListFilterForm[max_price]" type="text" placeholder="￥" />
    </div>
    <input class="input-search-task" name="ListFilterForm[task_title]" type="text" placeholder="请输入任务名称进行搜索" />
    <button type="button" class="btn-search btn bg-main submit">搜索</button>
    <?php ActiveForm::end()?>
</div>


<?php
$Js = <<<JS
$(function() {
    $('.ListFilterForm').on('click', '.submit,.category li,.order-status .order-status-select li', function() {
        var pagination = new Pagination();
        pagination.appendAfterListFilterFormSubmit(new Array(
            '.operate',
            '.click',
            'click',
            'CheckQualificationSelf'
        ));
        pagination.appendAfterPagination(new Array(
            '.operate',
            '.click',
            'click',
            'CheckQualificationSelf'
        ));
        pagination.listFilterFormSubmit($(this));
    });
    
    //绑定enter键
    $('.input-search-task').bind('keypress',function(event){
        if(event.keyCode == "13") {
            $('.ListFilterForm .submit').trigger("click");
        }
    });
    
});
JS;
$this->registerJs($Js, \yii\web\View::POS_END);
?>

