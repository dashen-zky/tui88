<?php
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\captcha\Captcha;

$this->title = '找回密码';
AppAsset::register($this);
?>
<?php AppAsset::addCss($this,"@web/src/css/login-regist/forget-psd.css?v=" . Yii::$app->params['static_version']) ?>

<!--fisrt one-->
<div class="main-wrap clearfix">
    <div class="main-con">
        <div class="reset-psd-con content">
            <div class="step-con clearfix">
                <div class="step-one step fl">
                    <span class="line bg-on-step"></span>
                    <span class="ball bg-on-step">1</span>
                    <span class="func color-on-step">找回密码</span>
                </div>
                <div class="step-two step fl">
                    <span class="line"></span>
                    <span class="ball">2</span>
                    <span class="func">重置密码</span>
                </div>
            </div>

            <div class="show" id="step-one">
                <div class="input-group">
                    <span class="title">手机号码:</span>
                    <input type="text" name="phone" class="cell-phone" placeholder="请输入绑定的手机号码">
                    <div class="tips">
                        请输入手机号码
                    </div>
                </div>

                <div class="input-group code-insure img-code">
                    <span class="title">图形验证码:</span>
                    <input type="text" name="verifyCode" class="imgCode" placeholder="请输入图形验证码">
                    <?= Captcha::widget([
                        'model' 		=> $model,
                        'attribute' 	=> 'verifyCode',
                        'captchaAction' => 'site/captcha',
                        'template' 		=> '<span>{image}</span>',
                        'imageOptions' => [
                            'title' 	=> '点击图片刷新',
                            "style" => "cursor:pointer",
                        ]
                    ]);?>
                    <div class="tips">
                        请输入验证码
                    </div>
                </div>

                <div class="input-group code-insure">
                    <span class="title">手机验证码:</span>
                    <input type="text" class="verify-code" name="verify" placeholder="请输入手机获取的验证码" >
                    <span class="get-code bg-main color-fff">获取手机验证码</span>
                    <span class="unclick color-fff"><i>60</i> s</span>
                    <div class="tips">
                        请输入手机验证码
                    </div>
                </div>
                <a  class="next-step btn bg-main color-fff" href="javascript:;" src="<?= Url::to(['/site/find-psd-one']) ?>">下一步</a>
            </div>
        </div>
    </div>
</div>

<?php AppAsset::addScript($this,"@web/src/js/login-regist/forget-psd.js?v=" . Yii::$app->params['static_version']) ?>