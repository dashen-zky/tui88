<?php
namespace common\models;

use frontend\models\LoginRecord;
use frontend\modules\account\models\AdOwner;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码错误');
            }
        }
    }

    public function loginAfterRegister($formData) {
        $this->username = $formData['email'];
        // 因为password 已经被加密，password_confirm
        $this->password = $formData['password_confirm'];
        return $this->login();
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * 用户登陆的额时候获取登录时间，登录ip,同时将用户的登录次数＋１
     * @param $event　是一个事件的对象
     * @return bool
     */
    public function whileLogin($event) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $adOwner = new AdOwner();
            if(!$adOwner->numberOfLoginCounter($event->identity->getId())) {
                throw new Exception('number of login time counter failed');
            }

            $userAccount = new UserAccount();
            if(!$userAccount->updateRecord([
                'uuid'=>$event->identity->getId(),
                'last_login_time'=>time(),
            ])) {
                throw new Exception('update last login time failed');
            }

            $loginRecord = new LoginRecord();
            if(!$loginRecord->insertRecord([
                'user_uuid'=>$event->identity->getId(),
                'time'=>time(),
                'ip'=>Yii::$app->request->getUserIP(),
            ])) {
                throw new Exception('insert login record failed');
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::trace($e->getMessage());
            return false;
        }
        $transaction->commit();
        return true;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = UserAccount::findByUsername($this->username);
        }

        return $this->_user;
    }
}
