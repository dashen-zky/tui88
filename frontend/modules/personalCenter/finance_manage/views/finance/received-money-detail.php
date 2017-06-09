<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- 面包屑 -->
<div class="bread">
    <ol class="breadcrumb font-500">
        <li><a href="#">首页</a></li>
        <li><a href="#">个人中心</a></li>
        <li class="active">财务管理</li>
    </ol>
</div>
<div class="con-header">
    <ul class="clearfix">
        <li class="clearfix">
            <div class="icon"></div>
            <div class="account">
                <span>总收入</span>
                <span>￥<?= isset($money_info["total_revenue"]) ? $money_info["total_revenue"] : "0.00" ?></span>
            </div>
        </li>
        <li class="clearfix">
            <div class="icon"></div>
            <div class="account">
                <span>已收款</span>
                <span>￥<?= isset($money_info["received_revenue"]) ? $money_info["received_revenue"] : "0.00" ?></span>
            </div>
        </li>
        <li class="clearfix">
            <div class="icon"></div>
            <div class="account">
                <span>待收款</span>
                <span>￥<?= isset($money_info["wait_revenue"]) ? $money_info["wait_revenue"] : "0.00" ?></span>
            </div>
        </li>
        <li class="clearfix">
            <div class="icon"></div>
            <div class="account">
                <span>本月收款</span>
                <span>￥<?= isset($money_info["this_month_money"]) ? $money_info["this_month_money"] : "0.00" ?></span>
            </div>
        </li>
    </ul>
</div>

<h4>收款记录</h4>
<div class="collection-record-top bg-fff clearfix">
    <?php $form = ActiveForm::begin([
        "action" => Url::to(["/personal-center/finance-manage/finance/finance-list-filter"]),
        "options"=> ["class"=>"ListFilterForm"],
    ])?>
    <div class="collection-time fl">
        <span class="title">收款时间:</span>
        <input type="text" name="ListFilterForm[start_time]" class="input-section text-input datetimepicker" placeholder="请选择开始时间"> 至
        <input type="text" name="ListFilterForm[end_time]" class="input-section text-input datetimepicker" placeholder="请选择结束时间">
        <i class="start-icon"></i>
        <i class="end-icon"></i>
    </div>
    <div class="collection-amount fl">
        <span class="title">收款金额:</span>
        <input class="min-price" type="text" name="ListFilterForm[min_price]" placeholder="￥"> 至
        <input class="max-price" type="text" name="ListFilterForm[max_price]" placeholder="￥">
        <button class="btn btn-search bg-main color-fff" type="button">搜索</button>
    </div>
    <?php ActiveForm::end()?>
</div>

<?php
$Js = <<<JS
$(function() {
    $('.ListFilterForm').on('click', '.btn-search', function() {
        var pagination = new Pagination();
        pagination.listFilterFormSubmit($(this));
    });
     //绑定enter键
    $('.max-price').bind('keypress',function(event){
        if(event.keyCode == "13") {
            $('.ListFilterForm .btn-search').trigger("click");
        }
    });
});
JS;
$this->registerJs($Js, \yii\web\View::POS_END);
?>