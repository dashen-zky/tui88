<?php
/**
 * Created by PhpStorm
 * USER: dashe
 * Date: 2017/3/8
 */
use frontend\assets\AppAsset;
use yii\bootstrap\ActiveForm;

AppAsset::register($this);

$this->title = "修改登录密码";

AppAsset::addCss($this,"@web/src/css/personal-center/amend-psd.css?v=" . Yii::$app->params['static_version']);
?>

<div class="content">
    <!-- 面包屑 -->
    <div class="bread">
        <ol class="breadcrumb font-500">
            <li><a href="#">首页</a></li>
            <li><a href="#">个人中心</a></li>
            <li><a href="#">个人设置</a></li>
            <li class="active">修改登录密码</li>
        </ol>
    </div>
    <div class="incontent">
        <?php $form = ActiveForm::begin(["id" => 'amend-psd-form',"options"=>['class' => 'form-con']]) ?>
        <h3>修改登录密码</h3>
        <div class="info-group org-psd mb-10">
            <span>原登录密码：</span>
            <input type="password" class="form-control" name="AmendPsdForm[oldPassword]" value="<?= $model->oldPassword ?>"  placeholder="请输入原密码">
            <p class="error-alert <?= isset($model->getErrors()["oldPassword"])? 'show': ''; ?>"> <?= isset($model->getErrors()["oldPassword"]) ? $model->getErrors()["oldPassword"][0]: '请输入原密码'; ?></p>
        </div>
        <div class="info-group new-psd mb-10">
            <span>新设密码：</span>
            <input type="password" class="form-control" name="AmendPsdForm[newPassword]" value="<?= $model->newPassword ?>" placeholder="请输入6-20位字母或数字">
            <p class="error-alert">请输入新密码</p>
        </div>
        <div class="info-group confir-new-psd">
            <span>确认新密码：</span>
            <input type="password" class="form-control" name="AmendPsdForm[reNewPassword]" value="<?= $model->reNewPassword ?>" placeholder="请再次输入密码">
            <p class="error-alert <?= isset($model->getErrors()["reNewPassword"])? 'show': ''; ?>">
                <?= isset($model->getErrors()["reNewPassword"])? $model->getErrors()['reNewPassword'][0]: '请再次输入密码'; ?>
            </p>
        </div>
        <input type="submit" class="save-btn sub-btn" value="提交">
    <?php ActiveForm::end()?>
    </div>
</div>

<?php AppAsset::addScript($this,"@web/src/js/personal-center/amend-psd.js?v=" . Yii::$app->params['static_version'])?>