<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use backend\modules\user_manage\models\DistributorExecutorMap;
?>
<!-- 面包屑 -->
<div class="bread">
    <ol class="breadcrumb font-500">
        <li><a href="#">首页</a></li>
        <li class="active">用户管理</li>
    </ol>
</div>
<div class="con-header">
    <?php $form = ActiveForm::begin([
        "action" => Url::to(["/user-manage/user/list-filter"]),
        "options"=>["class"=>"ListFilterForm"],
    ])?>
        <input type="hidden" class="order-by" name="ListFilterForm[orderBy]">
        <div class="con-header-bot">
        <div class="user-status condition-area">
            <span class="title">状态:</span>
            <div class="dropdown  order-status-select-area">
                <div class="clearfix" data-toggle="dropdown">
                    <span class="show-default fl">全部</span>
                    <span class="caret fr"></span>
                </div>
                <ul class="dropdown-menu order-status-select" role="menu">
                    <li>全部</li>
                    <li data-status="<?= DistributorExecutorMap::Enable?>">正常</li>
                    <li data-status="<?= DistributorExecutorMap::Disable?>">黑名单</li>
                </ul>
                <input type="hidden" name="ListFilterForm[enable]" class="enable-status">
            </div>
        </div>
        <div class="regist-time condition-area">
            <span class="title">注册时间:</span>
            <input type="text" name="ListFilterForm[register_time_start]" class="datetimepicker" placeholder="请选择开始时间"> 至
            <input type="text" name="ListFilterForm[register_time_end]" class="datetimepicker" placeholder="请选择结束时间">
            <i class="start-icon"></i>
            <i class="end-icon"></i>
        </div>
        <div class="search-area">
            <input class="search-input form-control" type="text" placeholder="请输入手机号" name="ListFilterForm[phone]">
            <button type="button" class="btn btn-search bg-main">搜索</button>
        </div>
    </div>
    <?php ActiveForm::end()?>
</div>

<?php
$Js = <<<JS
$(function() {
    $('.ListFilterForm').on('click',".btn-search,.order-status-select",function() {
        var pagination = new Pagination();
        
        pagination.appendAfterListFilterFormSubmit(new Array(
            ".operate",
            ".blacklist",
            "click",
            "pullBlackOrRestore"
        ));
        pagination.appendAfterListFilterFormSubmit(new Array(
            ".operate",
            ".resume",
            "click",
            "pullBlackOrRestore"
        ));
        
        pagination.appendAfterPagination(new Array(
            ".operate",
            ".blacklist",
            "click",
            "pullBlackOrRestore"
        ));
        pagination.appendAfterPagination(new Array(
            ".operate",
            ".resume",
            "click",
            "pullBlackOrRestore"
        ));
        
        pagination.appendAfterListFilterFormSubmit(new Array(
            ".list thead",
            ".sort",
            "click",
            "Sort"
        ));
        
        pagination.appendAfterPagination(new Array(
            ".list thead",
            ".sort",
            "click",
            "Sort"
        ));
        
        pagination.listFilterFormSubmit($(this));
    });
    $('.search-input').bind('keypress',function(event){
        if(event.keyCode == "13") {
            $('.ListFilterForm .btn-search').trigger("click");
        }
    });
});
JS;
$this->registerJs($Js, \yii\web\View::POS_END);
?>
