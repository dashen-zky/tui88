<?php
/**
 * Created by PhpStorm.
 * User: Gamelife
 * Date: 2017/1/4
 * Time: 11:57
 */

namespace frontend\models;


use yii\base\Model;
use yii\web\IdentityInterface;
use frontend\models\UserAccount;
use Yii;

class SignUpForm extends Model
{
   public $phone;
   public $verify;
   public $password;
   public $rePassword;
   public $verifyCode;


    /**
    * 用于用户注册时的验证规则    
    * @param 
    * @return 
    */
   public function rules()
   {
        return [
            ["phone" ,"required","message"=>"手机号不能为空"],
            ["phone" , "match","pattern"=>"/^1[34578]\d{9}$/","message"=>"手机号格式不正确"],
            ["phone" , "validatePhone"],
            ["phone","unique","targetClass"=>"frontend\models\UserAccount","message"=>"该手机号码已经注册"],

            ['verify', "required","message"=>"请填写短信验证码"],
            ["verify","validateVerify","message"=>"短信验证码不正确"],

            ["verifyCode","required","message"=>"请填写图片验证码"],
            ["verifyCode","captcha","message"=>"图片验证码不正确"],

            ['password',"required",'message'=>"密码不能为空"],
            ["password","string","length"=>[6,20],"tooShort"=>"密码少于六位","tooLong"=>"密码多余20位"],
            ["password","match","pattern"=>"/^[a-zA-Z0-9]{6,20}$/i","message"=>"密码格式不正确"],

            ["rePassword","required","message"=>"确认密码不能为空"],
            ["rePassword","compare","compareAttribute"=>"password","message"=>"两次密码不一致"],
        ];

   }


   /**
    * 用于验证验证码是否正确
    * @param attribute
    */
   public function validateVerify($attribute)
   {
        if($this->hasErrors()){
            return ;
        }

       if($this->verify != Yii::$app->session["sendMessage"]["verify"]){
           $this->addError($attribute, '验证码不正确');
       }
   }

    /**
     * 用于验证验证码是否正确
     * @param attribute
     */
    public function validatePhone($attribute)
    {
        if($this->hasErrors()){
            return ;
        }

        if($this->phone != Yii::$app->session["sendMessage"]["phone"]){
            $this->addError($attribute, '手机号不是  获取验证码的手机号');
        }
    }

   /**
    * 用于用户注册 添加用户
    * @param formData 表单提交的数据
    * @return true , false 是否正确添加用户
    */
   public function add($formData)
   {
        $user = new UserAccount;
        if($this->validate()){
            $formData["password"] = $user->setPassword($formData["password"]);
            if($user->insertRecord($formData)){
                return true;
            }
            return false;
        }
        return false;
   }

    
}