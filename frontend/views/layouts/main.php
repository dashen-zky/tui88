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
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?= $this->title ?></title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="shortcut icon" href="/src/images/icon.png">
    <?php $this->head()?>
     <!-- 页面样式 -->
    <?php AppAsset::addCss($this,'@web/src/css/reset.css?v=' . Yii::$app->params['static_version']) ?>
    <?php AppAsset::addCss($this,'@web/src/css/site-stage-layout-common.css?v=' . Yii::$app->params['static_version'])?>
</head>
<?php $this->beginBody() ?>
<?php if(Yii::$app->user->isGuest) :?>
<!--未登录状态下的头部导航-->
<div class="header clearfix">
    <h1 class="fl"><a href="<?= Url::to(['/site/login'])?>">TUI88最快捷的推广平台</a></h1>
    <div class="unlogin fr">
        <a href="<?= Url::to(['/site/register'])?>">注册</a>
        <a href="<?= Url::to(['/site/login'])?>">登录</a>
        <a href="#" style="display: none">返回登录</a>
    </div>
</div>
<?php else:?>
<!--登录状态下的头部导航-->
<div class="header clearfix">
    <h1 class="fl"><a href="<?= Url::to(['/site/index'])?>">TUI88最快捷的推广平台</a></h1>
    <div class="nav fl">
        <a href="<?= Url::to(['/personal-center/task-manage/task/index'])?>" <?= isset($this->context->action->controller->module->module->id) && $this->context->action->controller->module->module->id == 'personal-center'? 'class="current-nav"': ''; ?>>个人中心</a>
        <a href="<?= Url::to(['/task-hall/task/task-hall'])?>" <?= isset($this->context->action->controller->module->id) && $this->context->action->controller->module->id == 'task-hall'? 'class="current-nav"': ''; ?>>任务大厅</a>
    </div>
    <div class="enter fr">
        <a href="/index.php?r=personal-center%2Fsetting%2Fsetting%2Fbase-info">欢迎您，<?= Yii::$app->session['user_information']['nick_name']?></a>
        <a href="<?= Url::to(['/site/logout']) ?>">退出</a>
    </div>
</div>
<?php endif;?>

<?= $content ?>

<!-- 底部-->
<div class="footer">
    copyright@tui88.com版权所有
</div>

<?php
//页面JS文件引入
AppAsset::addScript($this,'@web/src/js/sidebar-common.js?v=' . Yii::$app->params['static_version']);
AppAsset::addScript($this,'@web/dep/layer/layer.js?v=' . Yii::$app->params['static_version']);
AppAsset::addScript($this,'@web/dep/js/tui88-tool.js?v=' . Yii::$app->params['static_version']);
AppAsset::addScript($this, 'src/js/pagination.js?v=' . Yii::$app->params['static_version']);
?>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>

