<?php
/**
 * Created by PhpStorm.
 * User: Gamelife
 * Date: 2017/1/4
 * Time: 11:57
 */

namespace frontend\models;


use common\models\BaseRecord;
use common\models\record_operator\RecordOperator;
use frontend\modules\personalCenter\models\UserInformation;
use yii\web\IdentityInterface;
use Yii;

class UserAccount extends BaseRecord implements IdentityInterface
{
    const ACTIVEUSER = 1;
    const INACTIVEUSER = 2;

    const alias = [
        "default"=>[
            "user"=>"t1",
            "user_infomation"=>"t2",
        ],
    ];

    public static function tableName()
    {
        return parent::USERACCOUNT;
    }


    public static function findIdentity($uuid)
    {
        return static::findOne(["uuid" => $uuid,'status'=>self::ACTIVEUSER]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token,'status'=>self::ACTIVEUSER]);
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
        return $this->access_token;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function generateAuthKey()
    {
        $this->auth_key = $this->access_token;
    }

    // 通过username 查找数据库找寻到相关的信息
    public static function findByUsername($username) {
        return self::findOne(['phone'=>$username,"status"=>self::ACTIVEUSER]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password,$this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
        return $this->password;
    }

    /**
     * 
     */
    public function validateVerify($verify)
    {
        return '123456' === $verify;
    }

    /**
     * 
     */
    public function setAccessToken()
    {
        $this->access_token = md5($this->uuid);
        return $this->access_token;
    }

    public function formDataPreHandler(&$formData, $record = null)
    {
        parent::formDataPreHandler($formData, $record);
        if(empty($record)) {
            if(!isset($formData['access_token']) || empty($formData['access_token'])){
                $formData['access_token'] = md5($formData['uuid']);
            }
        }
        $formData['password'] = $this->setPassword($formData['password']);
    }

    public function insertRecordRules($formData = null, $record = null)
    {
        return [
            'default'=>[
                [
                    /**
                     * 注册的时候在user_information表里面添加一条数据
                     */
                    'class'=>UserInformation::className(),
                    'operator'=>'insertRecord',
                    'operator_condition'=>true,
                    'params'=>[
                        'user_uuid'=> $formData['uuid'],
                        'nick_name' => $formData['phone'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 注册后  直接登录
     */
    public function afterInsertRecord($e)
    {
        Yii::$app->getUser()->login($e->record);
    }

    /**
     * @return 关联表 直接查询用户的详细信息
     */
    public function getUserInformation()
    {
        return $this->hasOne(UserInformation::className(),["user_uuid"=>"uuid"]);
    }
}