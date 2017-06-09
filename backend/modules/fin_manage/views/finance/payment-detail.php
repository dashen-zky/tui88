<?php
use backend\assets\AppAsset;

$this->title = '账单详情';
AppAsset::addCss($this,"@web/src/css/reset.css?v=" . Yii::$app->params['static_version']);
AppAsset::addCss($this,"@web/src/css/site-stage-common.css?v=" . Yii::$app->params['static_version']);
AppAsset::addCss($this,"@web/src/css/fin-manage/payment-detail.css?v=" . Yii::$app->params['static_version']);
?>
<!-- 主要内容部分-->
<div class="main-wrap my-panel clearfix">
    <?= $this->render("payment-order-info",["fin_info"=>$fin_info])?>
    <div class="list">
        <?= $this->render("payment-detail-list",[
            "order_lists"=>$order_lists,
            "ser_filter" => $ser_filter,
        ])?>
    </div>
</div>

<?php
$Js = <<<JS
$('.list .pagination li').on('click', function() {
        var pagination = new Pagination();
        pagination.pagination($(this));
});
JS;
$this->registerJs($Js, \yii\web\View::POS_END);
?>

<script>
    $(function () {
        // 判断任务数量
        calculateTaskAccount();
        function calculateTaskAccount() {
            var task_account = $('.table tbody').find('tr').length;
            if(task_account < 1){
                $('.no-task').show();
            }else{
                $('.no-task').hide();
            }
        }
    })
</script>

<?php
AppAsset::addScript($this,"@web/src/js/pagination.js?v=" . Yii::$app->params['static_version'])

?>
