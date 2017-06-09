<?php
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '管理员登录';
AppAsset::register($this);
?>
<?php
AppAsset::addCss($this,'@web/src/css/login/login.css?v=' . Yii::$app->params['static_version']);
?>

<!-- 主要内容部分-->
<?php $form = ActiveForm::begin(["id" => 'login-form']) ?>
<div class="main-wrap">
    <div class="main clearfix">
        <div class="login-con content">
            <h2>登录</h2>
            <div class="account-login">
                <div class="input-group">
                    <span>用户名:</span>
                    <?= Html::input('text' ,'LoginForm[username]', $model->username,["class"=>'phone-number','placeholder'=>'请输入账号']) ?>
                    <div class="insure-name insure <?= isset($model->getErrors()['username'])? 'show': ''; ?> ">
                        <?= isset($model->getErrors()['username'])? $model->getErrors()['username'][0] : '请输入账号'; ?>
                    </div>
                </div>
                <div class="input-group">
                    <span>密码:</span>
                    <?= Html::input("password", "LoginForm[password]", $model->password,["class"=>'psd','placeholder'=>'请输入6-18位的密码']) ?>
                    <div class="insure-psd insure <?= isset($model->getErrors()['password'])? 'show': ''; ?> " >
                        <?= isset($model->getErrors()['password'])? $model->getErrors()['password'][0] : '请输入密码'; ?>
                    </div>
                </div>
                <button class="btn-login btn bg-main">登 录</button>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>

<?php
AppAsset::addScript($this,'@web/src/js/login/login.js?v=' . Yii::$app->params['static_version']);
?>