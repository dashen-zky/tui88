<?php
use yii\helpers\Url;
use backend\assets\AppAsset;
use yii\bootstrap\Html;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?= Html::csrfMetaTags() ?>
        <title><?= $this->title ?></title>
        <link rel="shortcut icon" href="/src/images/icon.png">
        <?php $this->head()?>
        <?php AppAsset::addCss($this,"@web/dep/css/jquery.datetimepicker.css?v=" . Yii::$app->params['static_version']); ?>
        <?php AppAsset::addCss($this,'@web/src/css/reset.css?v=' . Yii::$app->params['static_version']) ?>
        <?php AppAsset::addCss($this,'@web/src/css/site-stage-common.css?v=' . Yii::$app->params['static_version']); ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
        <!--登录和未登录状态下的头部导航-->
        <div class="header clearfix">
            <h2 class="fl">TUI88后台ADMIN</h2>
            <?php if(!Yii::$app->user->isGuest) :?>
                <div class="personal-info fr">
                    <ul class="clearfix">
                        <li class="clearfix">
                            <i class="fl"></i>
                            <span class="user-name fl"><?= Yii::$app->user->identity->phone?></span>
                        </li>
                        <li><a class="logout color-fff" href="<?= Url::to(['/site/logout'])?>">退出</a></li>
                    </ul>
                </div>
            <?php endif;?>
        </div>

        <?= $content ?>

        <!-- 底部-->
        <div class="footer">
            copyright@tui88.com版权所有
        </div>
        <?php
            AppAsset::addScript($this,"@web/dep/datetimepicker/jquery.datetimepicker.js?v=" . Yii::$app->params['static_version']);
            AppAsset::addScript($this,"@web/dep/datetimepicker/datetime.js?v=" . Yii::$app->params['static_version']);
            AppAsset::addScript($this,"@web/dep/layer/layer.js?v=" . Yii::$app->params['static_version']);
            AppAsset::addScript($this,"@web/dep/plupload/plupload.full.min.js?v=" . Yii::$app->params['static_version']);
            AppAsset::addScript($this,"@web/dep/js/wom-uploader.js?v=" . Yii::$app->params['static_version']);
            AppAsset::addScript($this,"@web/dep/js/tui88-tool.js?v=" . Yii::$app->params['static_version']);
            AppAsset::addScript($this,"@web/src/js/site-stage-common.js?v=" . Yii::$app->params['static_version']);
        ?>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

