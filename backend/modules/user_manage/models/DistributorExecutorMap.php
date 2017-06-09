<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-4-12
 * Time: 下午5:33
 */

namespace backend\modules\user_manage\models;

use Yii;
use yii\db\Expression;
use common\models\BaseRecord;
use backend\modules\order_manage\models\Order;

class DistributorExecutorMap extends  BaseRecord
{
    const Enable = 1;
    const Disable = 2;
    const PullBlack = "pull_black";
    const Restore = "restore";
    const SettleTask = 'settle_task';
    const CheckOrderSuccess = "check_order_success";
    const PaidTask = "paid_task";

    public static $config = [
        "enable" => [
            self::Enable => "正常",
            self::Disable => "黑名单",
            self::Restore => "恢复",
            self::PullBlack => "拉黑",
        ],
    ];
    const alias = [
        "default" => [
            "distributor_executor_map" => "t1",
            "frontend_user" =>"t2",
            "frontend_user_information"=>"t3",
//            "executor_task_map" => "t4",
        ],
    ];

    public function buildRecordListRules()
    {
        return [
            'default'=>[
                'rules'=>[
                    'distributor_executor_map'=>[
                        'alias'=>self::alias["default"]["distributor_executor_map"],
                        'table_name'=>self::DistributorExecutorMap,
                        'join_condition'=>false,
                        'select_build_way'=>0,
                    ],
                    "frontend_user" => [
                        'alias'=>self::alias["default"]["frontend_user"],
                        'table_name'=>self::USERACCOUNT,
                        'join_condition'=>self::alias["default"]["frontend_user"].".uuid = ".self::alias["default"]["distributor_executor_map"].".executor_uuid ",
                        'select_build_way'=>0,
                    ],
                    "frontend_user_information" => [
                        'alias'=>self::alias["default"]["frontend_user_information"],
                        'table_name'=>self::UserInformation,
                        'join_condition'=>self::alias["default"]["frontend_user_information"].".user_uuid = ".self::alias["default"]["distributor_executor_map"].".executor_uuid",
                        'select_build_way'=>0,
                    ],
                ],
            ]
        ];
    }


    public $selector = [
        "distributor_executor_map" => [
            "id",
            "executor_uuid",
            "total_revenue",
            "received_revenue",
            "received_order_number",
            "wait_revenue",
            "wait_order_number",
            "total_settlement",
            "total_settle_order_number",
            "enable",
        ],
        "frontend_user" => [
            "phone",
            "create_time",
        ],
        "frontend_user_information" => [
            "finance_information",
        ],
    ];

    const RegisterTimeAsc = 1;
    const RegisterTimeDesc = 2;
    const TotalRevenueAsc = 3;
    const TotalRevenueDesc = 4;
    const ReceivedRevenueAsc = 5;
    const ReceivedRevenueDesc = 6;
    const WaitRevenueAsc = 7;
    const WaitRevenueDesc = 8;
    const IdDesc = 9;

    const OrderBy = [
        self::IdDesc => [
            self::alias["default"]["distributor_executor_map"].'.id'=>SORT_DESC,
        ],
        self::RegisterTimeAsc => [
            self::alias["default"]["frontend_user"].'.create_time'=>SORT_ASC,
        ],
        self::RegisterTimeDesc => [
            self::alias["default"]["frontend_user"].'.create_time'=>SORT_DESC,
        ],
        self::TotalRevenueAsc => [
            self::alias["default"]["distributor_executor_map"].'.total_revenue'=>SORT_ASC,
        ],
        self::TotalRevenueDesc => [
            self::alias["default"]["distributor_executor_map"].'.total_revenue'=>SORT_DESC,
        ],
        self::ReceivedRevenueAsc => [
            self::alias["default"]["distributor_executor_map"].'.received_revenue'=>SORT_ASC,
        ],
        self::ReceivedRevenueDesc => [
            self::alias["default"]["distributor_executor_map"].'.received_revenue'=>SORT_DESC,
        ],
        self::WaitRevenueAsc => [
            self::alias["default"]["distributor_executor_map"].'.wait_revenue'=>SORT_ASC,
        ],
        self::WaitRevenueDesc => [
            self::alias["default"]["distributor_executor_map"].'.wait_revenue'=>SORT_DESC,
        ],
    ];

    public static  function tableName()
    {
        return self::DistributorExecutorMap;
    }


    public function scenarios()
    {
        return array_merge(parent::scenarios(),[
            self::PullBlack => [self::PullBlack],
            self::Restore => [self::Restore],
            self::SettleTask => [self::SettleTask],
            self::CheckOrderSuccess => [self::CheckOrderSuccess],
            self::PaidTask => [self::PaidTask],
        ]);
    }


    public function listFilterRules($filter)
    {
        $rules =  [
            "default" => [
                "fields" => [
                    "enable" => [
                        '=',
                        self::alias["default"]["distributor_executor_map"].".enable",
                        isset($filter["enable"]) ? $filter["enable"] : null,
                    ],
                    "register_time_start" => [
                        ">=",
                        self::alias["default"]["frontend_user"].".create_time",
                        isset($filter["register_time_start"]) ? strtotime(trim($filter["register_time_start"])): null,
                    ],
                    "register_time_end" => [
                        "<=",
                        self::alias["default"]["frontend_user"].".create_time",
                        isset($filter["register_time_end"]) ? strtotime(trim($filter["register_time_end"])): null,
                    ],
                    "phone" => [
                        "like",
                        self::alias["default"]["frontend_user"].".phone",
                        isset($filter["phone"]) ? trim($filter["phone"]): null,
                    ],
                ],
                'orderBy'=>self::OrderBy[$filter["orderBy"]],
            ],
        ];

        return $rules;
    }

    public function formDataPreHandler(&$formData, $record = null)
    {
        parent::formDataPreHandler($formData, $record);
        if(!empty($record)){
            switch ($this->getScenario()){
                case self::PullBlack:
                    $formData["enable"] = self::Disable;
                    break;
                case self::Restore:
                    $formData["enable"] = self::Enable;
                    break;
                case self::SettleTask:
                    $money = isset($formData["money"]) ? $formData["money"] : 0;
                    $number_of_order = isset($formData["number_of_order"]) ? $formData["number_of_order"] : 0;
                    $formData["total_settlement"] = $record->total_settlement + $money;
                    $formData["total_settle_order_number"] = $record->total_settle_order_number + $number_of_order;
                    $formData["wait_revenue"] = $record->wait_revenue - $money;
                    $formData["wait_order_number"] = $record->wait_order_number - $number_of_order;
                    $formData["recent_settle_time"] = time();
                    break;
                case self::PaidTask:
                    $formData["received_revenue"] = $record->received_revenue + $formData["received_money"];
                    $formData["received_order_number"] = $record->received_order_number + $formData["received_number"];
                    break;
            }
        }
    }

    public function getAllRecord()
    {
        $this->getDefaultOrderBy(true);
        $lists = $this->recordList($this->selector,$this->defaultCondition(),false,$this->getDefaultOrderBy(true));
        $lists["list"] = $this->dealRecordLists($lists["list"]);
        return $lists;
    }

    public function defaultCondition()
    {
        $user_uuid = Yii::$app->user->identity->uuid;
        $condition = [
            'and',
            [
                "=",
                self::alias["default"]["distributor_executor_map"].".distributor_uuid",
                $user_uuid,
            ],
        ];

        return $condition;
    }

    public function dealRecordLists($lists)
    {
        if(empty($lists)){
            return $lists;
        }
        foreach ($lists as $key => $list){
            $lists[$key] = $this->dealRecordList($list);
        }

        return $lists;
    }

    public function dealRecordList($list)
    {
        if(empty($list)){
            return $list;
        }

        foreach ($list as $key => $value) {
            switch ($key){
                case "create_time":
                    $list[$key] = date("Y.m.d H:i",$value);
                    break;
                case "enable":
                    $list[$key."_cn"] = self::$config["enable"][$value];
                    break;
                case "finance_information":
                    $list[$key] = json_decode($value,true);
                    $list["receiver"] = preg_replace("/\s+/","",$list[$key]["bank_card_name"]);
                    unset($list[$key]);
                    break;
            }
        }
        return $list;
    }

    public function getFilterOrderLists($filter)
    {
        if(!isset($filter["orderBy"]) || empty($filter["orderBy"])){
            $filter["orderBy"] = self::RegisterTimeDesc;
        }
        $user_lists = $this->listFilter($this->selector,$filter,$this->defaultCondition());
        $user_lists["list"] = $this->dealRecordLists($user_lists["list"]);
        return $user_lists;
    }

    public function pullBlackOrRestore($formData)
    {
        return $this->updateRecord($formData);
    }

    public function getDefaultOrderBy($orderBy=null)
    {
        if(!$orderBy){
            return null;
        }
         return self::OrderBy[self::RegisterTimeDesc];
    }

    public function isExistRecord($executor, $phone)
    {
            $record = $this->getRecord(
            [
                "distributor_executor_map"=>
                [
                    "id",
                ],
                "frontend_user"=>
                [
                    "phone",
                ],
            ],
            [
                "and",
                [
                    "=",
                    self::alias["default"]["distributor_executor_map"].".executor_uuid",
                    $executor,
                ],
                [
                    "=",
                    self::alias["default"]["frontend_user"].".phone",
                    $phone,
                ],
                [
                    "=",
                    self::alias["default"]["distributor_executor_map"].".distributor_uuid",
                    Yii::$app->user->identity->uuid,
                ]

            ]
        );

        if(empty($record)){
            return false;
        }

        return true;
    }

    public function mySettleUpDate($formData)
    {
        $this->setScenario(self::SettleTask);
        return $this->updateRecord($formData);
    }

    public function myReceivedMoneyUpdate($formData)
    {
        $this->setScenario(self::PaidTask);
        $this->updateRecord($formData);
    }

    public function getUserInfo($user_uuid)
    {
        $user_info = $this->getRecord(
            [
                "frontend_user" => [
                    "phone",
                    'create_time',
                ],
                "frontend_user_information" => [
                    "contact",
                    "nick_name",
                    "wechat",
                    "qq",
                    "location",
                    "finance_information"
                ],
                "distributor_executor_map" => [
                    "enable",
                ],
            ],
            [
                'and',
                [
                    '=',
                    self::alias["default"]["distributor_executor_map"].'.distributor_uuid',
                    Yii::$app->user->identity->uuid,
                ],
                [
                    '=',
                    self::alias["default"]["distributor_executor_map"].".executor_uuid",
                    $user_uuid,
                ],
            ]
        );
        $user_info = $this->dealUserInfo($user_info);
        return $user_info;
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
                case "enable":
                    $user_info[$key] = self::$config["enable"][$item];
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