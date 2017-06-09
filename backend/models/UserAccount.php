<?php
/**
 * Created by PhpStorm.
 * User: Gamelife
 * Date: 2017/1/4
 * Time: 11:57
 */

namespace backend\models;

use common\models\BaseRecord;
use yii\web\IdentityInterface;
use Yii;

class UserAccount extends BaseRecord  implements IdentityInterface
{
    public static function tableName()
    {
        return parent::ADMINACCOUNT;
    }

    public static function findIdentity($uuid)
    {
        return static::findOne(["uuid" => $uuid]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->uuid;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() == $authKey;
    }

    // 通过username 查找数据库找寻到相关的信息
    public static function findByUsername($username) {
        return static::findOne(["phone"=>$username]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {   
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * 在主表验证过后 查询详情表
     * @return $userInformtion 用户的详细信息
     */
    public function afterLogin()
    {


    }


}