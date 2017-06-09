<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- 面包屑 -->
<div class="bread">
    <ol class="breadcrumb font-500">
        <li><a href="#">首页</a></li>
        <li><a href="#">结算管理</a></li>
        <li class="active">结算</li>
    </ol>
</div>
<div class="con-header">
    <?php $form = ActiveForm::begin([
        "action" => Url::to(["/settle-manage/settle/settling-list-filter"]),
        "options" => ["class"=>"ListFilterForm"],
    ])?>
    <div class="condition-selected-area clearfix">
        <div class="title fl">最近结算时间 :</div>
        <div class="condition-selected settlement-time-selected fl">
            <span time="now" class="on">不限</span>
            <span time="3 month ago">3个月前</span>
            <span time="1 month ago">1个月前</span>
            <span time="1 week ago">1周前</span>
            <input type="hidden" name="ListFilterForm[recent_settle_time_ago]" class="recent-settle-time">
        </div>
        <div class="settlement-time fl">
            <input type="text" name="ListFilterForm[recent_settle_time_start]" class="datetimepicker" placeholder="请选择开始时间"> 至
            <input type="text" name="ListFilterForm[recent_settle_time_end]" class="datetimepicker" placeholder="请选择结束时间">
            <i class="start-icon"></i><i class="end-icon"></i>
        </div>
    </div>
    <div class="condition-selected-area clearfix">
        <div class="title fl">待收款金额 :</div>
        <div class="condition-selected amount-money-selected fl">
            <span min="" max="" class="on">不限</span>
            <span min="500" max=''>500元以上</span>
            <span min="100" max="500">100-500元</span>
            <span min="50" max="100">50-100元</span>
            <span min="0" max="50">0-50元</span>
            <input type="hidden" name="ListFilterForm[min_wait_revenue]" class="min-wait-revenue">
            <input type="hidden" name="ListFilterForm[max_wait_revenue]" class=="max-wait-revenue">
        </div>
        <div class="amount-money-collection fl">
            <input type="text" placeholder="￥最小金额"> 至
            <input type="text" placeholder="￥最大金额">
        </div>
    </div>
    <div class="search-area">
        <input class="search-input form-control" name="ListFilterForm[phone]" type="text" placeholder="请输入用户手机号">
        <button type="button" class="btn btn-search bg-main">搜索</button>
    </div>
    <?php ActiveForm::end()?>
</div>


<?php
$Js = <<<JS
$(function() {
    $('.ListFilterForm').on('click',".btn-search",function() {
        var pagination = new Pagination();
        
        pagination.appendAfterListFilterFormSubmit(new Array(
            ".total-account",
            ".settlement-all",
            "click",
            "AllSettlement"
        ));
        pagination.appendAfterPagination(new Array(
            ".total-account",
            ".settlement-all",
            "click",
            "AllSettlement"
        ));
        pagination.appendAfterListFilterFormSubmit(new Array(
            ".list",
            ".all-select",
            "click",
            "CheckAll"
        ));
        pagination.appendAfterListFilterFormSubmit(new Array(
            ".list",
            ".order-checked",
            "click",
            "allchk"
        ));
        
        pagination.appendAfterPagination(new Array(
            ".list",
            ".all-select",
            "click",
            "CheckAll"
        ));
        pagination.appendAfterPagination(new Array(
            ".list",
            ".order-checked",
            "click",
            "allchk"
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