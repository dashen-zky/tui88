<?php
use frontend\assets\AppAsset;

AppAsset::addCss($this,"@web/src/css/personal-center/proceeds-method-common.css?v=" . Yii::$app->params['static_version']);

$this->title = '收款方式';
?>
<div class="content">
    <!-- 面包屑 -->
    <div class="bread">
        <ol class="breadcrumb font-500">
            <li><a href="#">首页</a></li>
            <li><a href="#">个人中心</a></li>
            <li><a href="#">个人设置</a></li>
            <li class="active">收款方式</li>
        </ol>
    </div>
    <?php if(!empty(Yii::$app->session["user_information"]["finance_information"])):?>
        <div class="incontent">
            <?= $this->render("proceed-method-show")?>
        </div>
    <?php else:?>
        <div class="incontent">
            <div class="info-show">
                <div class="bind-card">
                    <i></i>
                    <span>请先绑定银行卡</span>
                </div>
                <button class="bind-btn" data-toggle="modal" data-target="#bind-card">绑定银行卡</button>
            </div>
        </div>
    <?php endif;?>
</div>
<?=
    $this->render("proceed-method-model");
?>
<?php
AppAsset::addScript($this,"@web/src/js/personal-center/proceed-method-common.js?v=" . Yii::$app->params['static_version']);
?>
