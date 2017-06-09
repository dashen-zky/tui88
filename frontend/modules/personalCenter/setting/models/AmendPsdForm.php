<?php
/**
 * Created by PhpStorm
 * USER: dashe
 * Date: 2017/3/8
 */

namespace frontend\modules\personalCenter\setting\models;

use Yii;
use frontend\models\UserAccount;
use yii\base\Model;

class AmendPsdForm extends Model
{
    public $oldPassword;
    public $newPassword;
    public $reNewPassword;

    public function rules()
    {
        return [
            ["oldPassword","required","message"=>"原密码不能为空"],
            ["oldPassword","validateOldPassword"],

            ["newPassword","required","message"=>"新密码不能为空"],
            ["newPassword","string","length"=>[6,20],"tooShort"=>"新密码少于6位","tooLong"=>"新密码多余18位"],
            ["newPassword","validateNewPsdAndOldPsd"],

            ["reNewPassword","required","message"=>"重复新密码不能为空"],
            ["reNewPassword","compare","compareAttribute"=>"newPassword","message"=>"两次密码不匹配"],
        ];
    }

    public function validateOldPassword($attribute)
    {
        if(!$this->hasErrors()){
            $_account = UserAccount::findIdentity(Yii::$app->user->id);
            if(!$_account || !$_account->validatePassword($this->oldPassword)){
                $this->addError($attribute,"原密码错误");
            }
        }
    }

    public function validateNewPsdAndOldPsd($attribute)
    {
        if(!$this->hasErrors()){
            if($this->oldPassword == $this->newPassword){
                $this->addError($attribute,"新秘密不能与原密码一致");
            }
        }
    }
}