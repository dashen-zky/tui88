<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
class LoginForm extends Model
{
	public $username;
	public $password;
	public $rememberMe = true;
	private $_account;

	/**
	 * 验证规则  验证数据是否  正确
	 */
	public function rules()
	{
		return [
			['username','required',"message"=>"请输入账号"],
			['rememberMe',"boolean"],

			['password' ,'required',"message"=>"请输入密码"],
			['password',"string",'length'=>[6,18],"tooShort"=>"请至少输入六个字符","tooLong"=>"最多输入18个字符","message"=>"密码是6到18长度的字符"],
			['password' ,'validatePassword',"message"=>"用户名或者密码不正确"],
		];
	}

	public function validatePassword($attribute, $params)
	{
		if (!$this->hasErrors()) {
            $account = $this->getAccount();
            if (!$account || !$account->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或者密码不正确');
            }
        }

	}

	public function login()
	{
		if($this->validate()){
			return Yii::$app->user->login($this->getAccount(), $this->rememberMe ? 3600*12 : 0);
		}

        return false;
	}

	protected function getAccount() {
        if ($this->_account === null) {
            $this->_account = UserAccount::findByUsername($this->username);
        }
        return $this->_account;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}