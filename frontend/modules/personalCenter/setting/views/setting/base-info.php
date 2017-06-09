<?php
use frontend\assets\AppAsset;
use yii\bootstrap\ActiveForm;
use  yii\helpers\Url;
AppAsset::register($this);

$this->title = "基础信息";
?>
<?php AppAsset::addCss($this,"@web/src/css/personal-center/base-info.css?v=" . Yii::$app->params['static_version'])?>

<div class="content">
    <!-- 面包屑 -->
    <div class="bread">
        <ol class="breadcrumb font-500">
            <li><a href="#">首页</a></li>
            <li><a href="#">个人中心</a></li>
            <li><a href="#">个人设置</a></li>
            <li class="active">基础信息</li>
        </ol>
    </div>

    <div class="incontent">
        <?php $form = ActiveForm::begin(['id'=>"base-info","options"=>["class"=>"form-con"]]) ?>
            <h3>基础信息</h3>
            <div class="info-group">
                <span>昵称：</span>
                <input type="text" class="form-control" name="BaseInfoForm[nick_name]" value="<?= $model->_userInfo->nick_name?>" maxlength="20">
                <span style="font-size:12px;color:red;"><?= isset($model->getErrors()["nick_name"]) ?  $model->getErrors()["nick_name"][0] : ''?></span>
            </div>
            <div class="info-group">
                <span>注册时间：</span>
                <span><?= date("Y.m.d",Yii::$app->user->identity->create_time)?></span>
            </div>
            <div class="info-group mb-10">
                <span>所在地：</span>
                <input type="text" class="form-control" name="BaseInfoForm[location]" value="<?= $model->_userInfo->location?>">
            </div>
            <div class="info-group">
                <span>联系人：</span>
                <input type="text" class="form-control" name="BaseInfoForm[contact]" value="<?= $model->_userInfo->contact?>">
            </div>
            <div class="info-group ph-num">
                <span>手机：</span>
                <span class="my-modify-phone"><?= Yii::$app->user->identity->phone?></span>
                <span class="amend" data-toggle="modal" data-target="#amend-ph-num">修改</span>
            </div>
            <div class="info-group mb-10">
                <span>微信号：</span>
                <input type="text" class="form-control" name="BaseInfoForm[wechat]" value="<?= $model->_userInfo->wechat?>">
            </div>
            <div class="info-group test-code">
                <span>QQ：</span>
                <input type="text" class="form-control" name="BaseInfoForm[qq]" value="<?= $model->_userInfo->qq?>" maxlength="11">
                <?php if (isset($model->getErrors()["qq"])):?>
                <span style="font-size:12px;color:red;"><?= $model->getErrors()["qq"][0]?></span>
                <?php endif;?>
            </div>
            <input type="submit" class="save-btn" value="保存">
        <?php ActiveForm::end()?>
    </div>
</div>

<!-- 修改手机号modal层-->
<div id="amend-ph-num" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="fl">修改手机号</span><i class="close fr" data-dismiss="modal"></i></div>
            <?php $form = ActiveForm::begin(["action"=>Url::to(["/personal-center/setting/setting/modify-phone"])])?>
            <div class="modal-body">
                <div class="info-group new-ph-num mb-10">
                    <span>新手机号：</span>
                    <input type="text" class="form-control" name="phone">
                    <p class="error-alert">请输入手机号码</p>
                </div>
                <div class="info-group test-code">
                    <span>验证码：</span>
                    <input type="text" class="form-control verify-code" name="code">
                    <button type="button" class="get-code" url="<?= Url::to(['/personal-center/setting/setting/send-message'])?>">获取验证码</button>
                    <p class="error-alert">请输入手机验证码</p>
                </div>
                <button type="button" class="amend-sub-btn" data-toggle="modal" data-target="#amend-over"  data-dismiss="modal">提交</button>
            </div>
            <?php ActiveForm::end()?>
        </div>
    </div>
</div>
<!-- 修改完成-->
<div id="amend-over" class="modal modal-message fade in" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix"><span class="fl"></span><i class="close fr" data-dismiss="modal"></i></div>
            <div class="modal-body">
                <i></i>
                <span>修改完成！</span>
            </div>
        </div>
    </div>
</div>

<?php AppAsset::addScript($this,"@web/src/js/personal-center/base-info.js?v=" . Yii::$app->params['static_version'])?>
