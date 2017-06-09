<?php

namespace common\models;

use common\helpers\PasswordSecurityCheckHelper;
use common\models\record_operator\FieldsHandlerHelper;
use common\models\record_operator\RecordOperatorBasedOnRules;
use common\models\record_operator\RecordOperatorInterFace;
use common\widgets\message\MessageManager;
use common\widgets\message\MessageQueueSubscription;
use frontend\modules\account\models\AdOwner;
use frontend\modules\account\models\FinanceRecord;
use Yii;
use yii\base\Exception;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "wom_account".
 *
 * @property integer $id
 * @property string $uuid
 * @property string $email
 * @property string $password
 * @property integer $type
 * @property string $weixin_open_platform_unionid
 * @property integer $status
 * @property string $authKey
 * @property integer $create_time
 * @property integer $last_update_time
 * @property integer $last_login_time
 * @property integer $account_type
 *
 * @property DtsWeixinArticleMonitorTask[] $dtsWeixinArticleMonitorTask
 */
class UserAccount extends BaseRecord  implements IdentityInterface
{
    const StatusActive = 1;
    const TypeAdOwner = 1;
    const RegisterFromWom = 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return self::USERACCOUNT;
    }

    public function init()
    {
        $this->recordOperator = new RecordOperatorBasedOnRules();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * 插入数据时的一些规则
     * 'sceniro'
     */
//    public function insertRecordRules($formData = null, $record=null) {
//        $common = [
//            [
//                /**
//                 * 注册的时候在ad　owner表里面添加一条数据
//                 */
//                'class'=>AdOwner::className(),
//                'operator'=>'insertRecord',
//                'operator_condition'=>true,
//                'params'=>[
//                    'uuid'=> $formData['uuid'],
//                    'point'=>FinanceRecord::InitialPoint,
//                ],
//            ],
//            [
//                /**
//                 * 注册的时候让其监听　system message queue　和　自身uuid的队列
//                 */
//                'class'=>MessageQueueSubscription::className(),
//                'operator'=>'subscribeMultiQueue',
//                'operator_condition'=>true,
//                'params'=>[
//                    'user_id'=>$formData['uuid'],
//                    'queues'=>[
//                        $formData['uuid'],
//                        MessageManager::SystemMessageQueue,
//                    ]
//                ]
//            ],
//            [
//                /**
//                 * 注册赠送５００点
//                 */
//                'class'=>FinanceRecord::className(),
//                'operator'=>'insertRecord',
//                'operator_condition'=>true,
//                'params'=>[
//                    'user_uuid'=>$formData['uuid'],
//                    'point'=>FinanceRecord::InitialPoint,
//                    'sign'=>FinanceRecord::InputSign,
//                    'type'=>FinanceRecord::TypeRegister,
//                    'create_uuid'=>$formData['uuid'],
//                ]
//            ]
//        ];
//        return [
//            'default'=>$common,
//            'register_from_wom'=>$common,
//        ];
//    }

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
        return static::find()->andWhere([
            'and',
            [
                'or',
                [
                    '=',
                    'email',
                    $username
                ],
                [
                    '=',
                    'phone',
                    $username
                ]
            ],
            [
                '=',
                'status',
                self::StatusActive,
            ]
        ])->one();
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
     * Generates password hash from password and sets it to the model
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
        return $this->password;
    }

    public function checkAccountExist($condition) {
        return self::find()->andWhere($condition)->one();
    }
}
