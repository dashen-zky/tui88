<?php
/**
 * Created by PhpStorm
 * USER: dashe
 * Date: 2017/3/9
 */

namespace frontend\modules\personalCenter\setting\models;

use Yii;
use yii\base\Model;

class BaseInfoForm extends Model
{
    public $nick_name;
    public $wechat;
    public $qq;
    public $location;
    public $contact;
    public $_userInfo = null;

    public function rules()
    {
        return [
            ["qq","validateQQ"],
            ["nick_name","validateNickName"],
        ];
    }

    public function getUserInformation()
    {
        if($this->_userInfo == null){
            $user = Yii::$app->getUser()->getIdentity();
            $this->_userInfo = $user->userInformation;
        }
        return $this->_userInfo;
    }

    public function validateQQ($attribute)
    {
        if(!$this->hasErrors()){
            $preg = "/^\d{6,11}$/";
            if(!preg_match($preg,$this->qq)){
                $this->addError($attribute,"qq号不正确");
            }
        }
    }

    public function validateNickName($attribute)
    {
        if(!$this->hasErrors()){
            if(mb_strlen($this->nick_name,"utf-8") > 20){
                $this->addError($attribute,"昵称过长");
            }
        }
    }

}