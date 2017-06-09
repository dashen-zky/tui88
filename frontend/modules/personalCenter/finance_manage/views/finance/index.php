<?php
use frontend\assets\AppAsset;

$this->title = '财务管理';

AppAsset::addCss($this,"@web/dep/css/jquery.datetimepicker.css?v=" . Yii::$app->params['static_version']);
AppAsset::addCss($this,"@web/src/css/reset.css?v=" . Yii::$app->params['static_version']);
AppAsset::addCss($this,"@web/src/css/site-stage-layout-common.css?v=" . Yii::$app->params['static_version']);
AppAsset::addCss($this,"@web/src/css/personal-center/fin-manage.css?v=" . Yii::$app->params['static_version']);

?>
<div class="content my-panel">
    <?= $this->render("received-money-detail",["money_info"=>$money_info])?>
    <div class="list">
        <?= $this->render("received-money-list",["finance_lists"=>$finance_lists])?>
    </div>
</div>
<?php
$Js = <<<JS
$(function() {
    $(".list .pagination li").on('click', function() {
        var pagination = new Pagination();
        pagination.pagination($(this));
    });
});
JS;
$this->registerJs($Js, \yii\web\View::POS_END);
?>

<!-- 收款凭证modal层-->
<div id="paying-voucher" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">

</div>

<?php
AppAsset::addScript($this,"@web/dep/datetimepicker/jquery.datetimepicker.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/dep/datetimepicker/datetime.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/personal-center/fin-manage.js?v=" . Yii::$app->params['static_version']);
AppAsset::addScript($this,"@web/src/js/pagination.js?v=" . Yii::$app->params['static_version']);
?>
