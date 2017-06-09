<?php
/**
 * Created by PhpStorm.
 * User: dashe
 * Date: 2017/3/3
 * Time: 10:12
 */
namespace backend\models;

use Yii;
use yii\base\Model;
use backend\models\UserAccount;

/**
 * Class LoginForm
 * @package backend\models
 * 用来 后台用户登陆
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe;
    private $_user;
    /**
     * @return [] 返回用来验证登陆表单传递归来的规则
     */
    public function rules(){
        return [
            ["username","required","message"=>"用户名不能为空"],
            ["password","required","message"=>"密码不能为空"],
            ["password","validatePassword","message"=>"用户或者密码不正确"]
        ];
    }

    /**
     * @param $attribute
     * @param $param
     * @return bool
     */
    public function validatePassword($attribute, $params)
    {
        if($this->hasErrors()){
            return false;
        }
        $user = $this->getUser();
        if(!$user || !$user->validatePassword($this->password)){
            $this->addError($attribute,"用户名或者密码不正确");
        }
    }

    /**
     * 后台用户登陆以及验证
     * @return bool
     */
    public function login()
    {
        if($this->validate()){
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*12 : 0);
        }
        return false;
    }

    public function getUser()
    {
        if($this->_user === null){
            $this->_user = UserAccount::findByUsername($this->username);
        }
        return $this->_user;

    }


}