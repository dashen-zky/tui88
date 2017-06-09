<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- 面包屑 -->
<div class="bread">
    <ol class="breadcrumb font-500">
        <li><a href="#">首页</a></li>
        <li><a href="#">结算管理</a></li>
        <li class="active">结算记录</li>
    </ol>
</div>
<div class="con-header clearfix">
    <?php $form = ActiveForm::begin([
        "action" => Url::to(["/settle-manage/settle/settled-record-filter"]),
        "options"=> ["class"=>"ListFilterForm"],
    ])?>
    <div class="settlement-time fl">
        结算时间: <input type="text" name="ListFilterForm[settlement_start_time]" class="datetimepicker" placeholder="请选择开始时间"> 至
        <input type="text" name="ListFilterForm[settlement_end_time]" class="datetimepicker" placeholder="请选择结束时间">
        <i class="start-icon"></i><i class="end-icon"></i>
    </div>
    <div class="amount-money-collection fl">
        结算金额: <input type="text" name="ListFilterForm[min_amount]" placeholder="￥最小金额"> 至
        <input type="text" name="ListFilterForm[max_amount]" placeholder="￥最大金额">
    </div>
    <div class="search-area fl">
        <input class="search-input form-control" type="text" name='ListFilterForm[ser_number_and_phone]' placeholder="请输入结算单号或用户手机号">
        <button class="btn btn-search bg-main" type="button">搜索</button>
    </div>
    <?php ActiveForm::end()?>
</div>


<?php
$Js = <<<JS
$(function() {
    $('.ListFilterForm').on('click',".btn-search",function() {
        var pagination = new Pagination();
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