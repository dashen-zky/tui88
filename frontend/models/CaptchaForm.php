<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-5-5
 * Time: 上午10:57
 */

namespace frontend\models;

use Yii;
use yii\base\Model;


class CaptchaForm extends Model
{
    public $phone;
    public $verifyCode;

    const Register = "register";
    const FindPassword = "find_password";
    const ModifyPhone = "modify_phone";

    public function rules()
    {
        return [
            ["phone","required","message"=>"请输入手机号"],
            ["phone","match","message"=>"请输入图片验证码"],

            ["verifyCode","required","message"=>"请输入图片验证码"],
            ["verifyCode","captcha","message"=>'图片验证码错误'],
//            ["verifyCode","captcha","validateVerifyCode"],
        ];
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::Register => [self::Register],
            self::FindPassword => [self::FindPassword],
            self::ModifyPhone => [self::ModifyPhone],
        ]);
    }

    public function attributeLabels()
    {
        return [
            "verifyCode" => '',
        ];
    }

    public function validateVerifyCode($attribute)
    {
        $captcha_validate  = new MyCaptchaAction('captcha',Yii::$app->controller);
        if($this->$attribute){
            $code = $captcha_validate->getVerifyCode();
            if($this->$attribute!=$code){
                $this->addError($attribute, 'The verification code is incorrect.');
            }
        }
    }

}