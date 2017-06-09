<?php
namespace frontend\modules\personalCenter\models;
use common\fixtures\User;
use common\models\BaseRecord;
use frontend\models\UserAccount;
use Yii;

/**
 * Class UserInformation
 * @package frontend\modules\personalCenter\models
 * @User : king
 * @Time: 2017-03-01
 */
class UserInformation extends BaseRecord
{
    const ActiveUser = UserAccount::ACTIVEUSER;
    const InActiveUser = UserAccount::INACTIVEUSER;

    public static $config = [
        "status" => [
            self::ActiveUser => '活跃',
            self::InActiveUser => '不活跃',
        ],
    ];

    public static $alias = [
        "default" => [
            "user_information" => "t1",
            "user" => "t2",
        ],
    ];
    public $selector = [
        "user_information" => [
            "location",
            "nick_name",
            "contact",
            "wechat",
            "qq",
            "finance_information",
        ],
        "user" => [
            "phone",
            "create_time",
            "status",
        ],
    ];

    public static function tableName()
    {
        return self::UserInformation;
    }

    public function getUserAccount()
    {
        return $this->hasOne(UserAccount::className(),['uuid'=>'user_uuid']);
    }

    public function buildRecordListRules() {
        return [
            'default'=>[
                'rules'=>[
                    'user_information'=>[
                        'alias'=>'t1',
                        'table_name'=>self::UserInformation,
                        'join_condition'=>false,
                        'select_build_way'=>0,
                    ],
                    'user'=>[
                        'alias'=>'t2',
                        'table_name'=>self::USERACCOUNT,
                        'join_condition'=>'t2.uuid = t1.user_uuid',
                        'select_build_way'=>0,
                    ],
                ],
            ]
        ];
    }

    public function loadInformation() {
        if(isset(Yii::$app->session['user_information'])) {
            return ;
        }

        Yii::$app->session['user_information'] = $this->getRecord(
            [
                'user_information'=>[
                    'id',
                    'nick_name',
                    "finance_information",
                ],
                'user'=>[
                    'phone',
                ]
            ],
            [
                '=',
                't1.user_uuid',
                Yii::$app->user->identity->uuid,
            ]
        );
    }

    public function invalidInformationSession() {
        if(isset(Yii::$app->session['user_information'])) {
            unset(Yii::$app->session['user_information']);
        }
    }

    public function afterUpdateRecord($e)
    {
        parent::afterUpdateRecord($e);
        $this->invalidInformationSession();
        $this->loadInformation();
    }

    public function myUpdateRecord($formData)
    {
        return $this->updateRecord($formData);
    }

    public function getUserInfo($user_uuid)
    {
        $user_info = $this->getRecord($this->selector,$this->getDefaultCondition($user_uuid));
        $user_info = $this->dealUserInfo($user_info);
        return $user_info;
    }

    public function getDefaultCondition($user_uuid)
    {
        return [
            '=',
            self::$alias["default"]["user_information"].".user_uuid",
            $user_uuid,
        ];
    }

    public function dealUserInfo($user_info)
    {
        if(empty($user_info)){
            return $user_info;
        }

        foreach($user_info as $key => $item){
            switch ($key){
                case "create_time":
                    $user_info[$key] = date("Y.m.d",$item);
                    break;
                case "status":
                    $user_info[$key] = self::$config["status"][$item];
                    break;
                case "finance_information":
                    $user_info[$key] = json_decode($item,true);
                    break;
                default :
                    if(empty($item)){
                        $user_info[$key] = '--';
                    }
                    break;
            }
        }

        return $user_info;
    }
}