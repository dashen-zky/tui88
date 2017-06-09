<?php
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>推客注册</title>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <?= Html::csrfMetaTags() ?>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="shortcut icon" href="/src/images/icon.png">

    <?php $this->head()?>
     <!-- 页面样式 -->
    <?php AppAsset::addCss($this,'@web/dep/css/bootstrap-3.3.6.min.css?v=' . Yii::$app->params['static_version']) ?>
    <?php AppAsset::addCss($this,'@web/src/css/reset.css?v=' . Yii::$app->params['static_version']) ?>
    <?php AppAsset::addCss($this,'@web/dep/layer/skin/layer.css?v=' . Yii::$app->params['static_version']) ?>
    <?php AppAsset::addCss($this,'@web/src/css/site-stage-layout-common.bak.css?v=' . Yii::$app->params['static_version']) ?>
    
</head>
<body>
    <!--未登录状态下的头部导航-->
    <div class="header clearfix">
        <h1 class="fl"><a href="#">TUI88最快捷的推广平台</a></h1>
        <div class="unlogin fr">
            <a href="#" style="display: none">注册</a>
            <a href="#" style="display: none">登录</a>
            <a href="<?= Url::to(['/site/login'])?>">返回登录</a>
        </div>
    </div>

<!-- 底部-->
<?= $content ?>
<div class="footer">
    copyright@tui88.com版权所有
</div>
<?php
//页面JS文件引入
AppAsset::addScript($this,'@web/dep/js/wom-tool.js?v=' . Yii::$app->params['static_version']);
AppAsset::addScript($this,'@web/dep/layer/layer.js?v=' . Yii::$app->params['static_version']);
AppAsset::addScript($this,'@web/dep/js/tui88-tool.js?v=' . Yii::$app->params['static_version']);
AppAsset::addScript($this,'@web/src/js/sidebar-common.js?v=' . Yii::$app->params['static_version']);
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

