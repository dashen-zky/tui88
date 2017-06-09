<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-4-25
 * Time: 上午10:22
 */

namespace backend\modules\fin_manage\models;

use backend\modules\order_manage\models\Order;
use backend\modules\settle_manage\models\OrderFinanceMap;
use frontend\modules\taskHall\models\ExecutorTaskMap;
use Yii;
use common\models\BaseRecord;
use common\helpers\ExternalFileHelper;
use backend\modules\user_manage\models\DistributorExecutorMap;
use yii\db\Query;

class Finance extends BaseRecord
{
    public $pageSize = 20;

    const PaidStatusEnable = 1;
    const PaidStatusDisable = 2;
    const SeeStatus = 1;
    const SawStatus = 2;

    public $selector = [
        "finance_record" => [
            "id",
            "uuid as finance_uuid",
            "number_of_order",
            "create_time",
            "money",
            "paid_time",
            'paid_status',
            "received_name",
            "received_account",
            "bank_of_deposit",
            "received_phone",
            "ser_number",
            "dist_exec_map_id",
        ],
    ];

    public static $alias = [
        "default" => [
            "finance_record" => "t1",
            "order_finance_map" => "t2",
            "executor_task_map" => "t3",
        ],
    ];

    public static function tableName()
    {
        return self::Finance_Record;
    }

    public function buildRecordListRules()
    {
        return [
            'default'=>[
                'rules'=>[
                    "finance_record" => [
                        'alias'=>'t1',
                        'table_name'=>self::Finance_Record,
                        'join_condition'=>false,
                        'select_build_way'=>0,
                    ],
                ],
            ],
        ];
    }

    public function listFilterRules($filter)
    {
         return [
            "default" => [
                "fields" => [
                    "settlement_start_time" => [
                        '>=',
                        self::$alias["default"]["finance_record"].".create_time",
                        isset($filter["settlement_start_time"]) ? strtotime(trim($filter["settlement_start_time"])) : null,
                    ],
                    "settlement_end_time" => [
                        "<=",
                        self::$alias["default"]["finance_record"].".create_time",
                        isset($filter["settlement_end_time"]) ? strtotime(trim($filter["settlement_end_time"])): null,
                    ],

                    "paid_start_time" => [
                        '>=',
                        self::$alias["default"]["finance_record"].".paid_time",
                        isset($filter["paid_start_time"]) ? strtotime(trim($filter["paid_start_time"])) : null,
                    ],
                    "paid_end_time" => [
                        "<=",
                        self::$alias["default"]["finance_record"].".paid_time",
                        isset($filter["paid_end_time"]) ? strtotime(trim($filter["paid_end_time"])): null,
                    ],

                    "min_amount" => [
                        ">=",
                        self::$alias["default"]["finance_record"].".money",
                        isset($filter["min_amount"]) ? intval(trim($filter["min_amount"])): null,
                    ],
                    "max_amount" => [
                        "<=",
                        self::$alias["default"]["finance_record"].".money",
                        isset($filter["max_amount"]) ? intval(trim($filter["max_amount"])): null,
                    ],
                    "ser_number_or_phone_or_received_name" => [
                        "or",
                        [
                            "like",
                            self::$alias["default"]["finance_record"].".ser_number",
                            isset($filter["ser_number_or_phone_or_received_name"]) ? trim($filter["ser_number_or_phone_or_received_name"]) : null,
                        ],
                        [
                            "like",
                            self::$alias["default"]["finance_record"].".received_phone",
                            isset($filter["ser_number_or_phone_or_received_name"]) ? trim($filter["ser_number_or_phone_or_received_name"]) : null,
                        ],
                        [
                            "like",
                            self::$alias["default"]["finance_record"].".received_name",
                            isset($filter["ser_number_or_phone_or_received_name"]) ? trim($filter["ser_number_or_phone_or_received_name"]) : null,
                        ],
                    ],
                ],
            ],
        ];
    }

    public function formDataPreHandler(&$formData, $record = null)
    {
        parent::formDataPreHandler($formData, $record);
        if(!empty($record)){
            $formData["paid_status"] = self::PaidStatusDisable;
            $formData["paid_time"] = time();
            $formData["attachment"] = trim(str_replace(",",",".ExternalFileHelper::getOtherAbsoluteDirectory(),",".trim($formData["img_names"],",")),",");
            unset($formData["img_names"]);
            $formData["received_details"] = [];
            $formData["received_details"]["received_money"] = $record->money;
            $formData["received_details"]["received_number"] = $record->number_of_order;
            $formData["received_details"]["dist_exec_map_id"] = $record->dist_exec_map_id;
            $formData["received_details"]["finance_uuid"] = $record->uuid;
        }
    }

    public function updateRecordRules($formData = null, $record = null)
    {
        return [
            "default" => [
                [
                    /**
                     * 通过审收款的时候 在distributor_executor_map表里面 更新数据
                     */
                    'class' => DistributorExecutorMap::className(),
                    'operator' => 'myReceivedMoneyUpdate',
                    'operator_condition' => true,
                    'params' => [
                        'id' => $formData["received_details"]["dist_exec_map_id"],
                        "received_money" => $formData["received_details"]["received_money"],
                        "received_number" => $formData["received_details"]["received_number"],
                    ],
                ],
                [

                    /**
                     * 通过审收款的时候 在executor_task_map 表里面 更新数据
                     */
                    'class' => Order::className(),
                    'operator' => 'myReceivedMoneyUpdate',
                    'operator_condition' => true,
                    'params' => [
                        'finance_uuid' => $formData["received_details"]["finance_uuid"],
                        'received_evidence' => $formData["attachment"],
                        'received_remarks' => $formData["remarks"],
                    ],
                ],
            ],
        ];
    }

    public function getMyFinInfo($finance_id)
    {
        $pay_info = $this->getRecord($this->selector,$this->getMyFinInfoDefaultCondition($finance_id));
        $pay_info = $this->dealPayInfo($pay_info);
        return $pay_info;
    }

    public function GetMyPaymentInfo($finance_id,$paid_status)
    {
        if (empty($finance_id)  || ($paid_status != self::PaidStatusDisable && $paid_status != self::PaidStatusEnable)){
            return json_encode(["code"=>1,"message"=>"系统异常！"]);
        }
        if($paid_status == self::PaidStatusEnable){
            $selector = [
                "finance_record" => [
                    "received_phone",
                    "money",
                    "received_name",
                    "bank_of_deposit",
                    "received_account",
                ],
            ];
        }else if($paid_status == self::PaidStatusDisable){
            $selector = [
                "finance_record" => [
                    "received_phone",
                    "money",
                    "received_name",
                    "bank_of_deposit",
                    "received_account",
                    "attachment",
                    "remarks",
                ],
            ];
        }else{
            $selector = [
                "finance_record" => [
                    "id",
                ],
            ];
        }

        $payment_info = $this->getRecord(
            $selector,
            [
                "and",
                [
                    "=",
                    self::$alias["default"]["finance_record"].".id",
                    $finance_id,
                ],
                [
                    "=",
                    self::$alias["default"]["finance_record"].".paid_status",
                    $paid_status,
                ],

            ]
        );

        if($paid_status == self::PaidStatusEnable){
            if(empty($payment_info)){
                return json_encode(["code"=>1,"message"=>"系统异常！"]);
            }
            return json_encode(["code"=>0,"payment_info"=>$this->dealPaymentInfo($payment_info)]);
        }else if ($paid_status == self::PaidStatusDisable) {
            return $this->dealPaymentInfo($payment_info);
        }
    }

    public function getMyFinInfoDefaultCondition($finance_id)
    {
        return [
            "=",
            self::$alias["default"]["finance_record"].".id",
            $finance_id,
        ];
    }

    public function dealAllFinLists($lists)
    {
        if(empty($lists)){
            return $lists;
        }
        foreach ($lists as $key => $item){
            $lists[$key] = $this->dealPayInfo($item);
        }
        return $lists;
    }

    public function dealPayInfo($pay_info)
    {
        if(empty($pay_info)){
            return $pay_info;
        }
        foreach ($pay_info as $key => $value){
            switch ($key){
                case "create_time":
                    $pay_info[$key] = empty($value) ? '-' : date("Y-m-d H:i",$value);
                    break;
                case "paid_time":
                    $pay_info[$key] = empty($value) ? '-' : date("Y-m-d H:i",$value);
                    break;
            }
        }
        return $pay_info;
    }

    public function dealPaymentInfo($payment_info)
    {
        if (empty($payment_info)){
            return null;
        }
        foreach ($payment_info as $key => $value){
            switch ($key){
                case "received_phone":
                    $payment_info["base_info"][$key] = ["name"=>"手机号","value"=>$value];
                    unset($payment_info[$key]);
                    break;
                case "money":
                    $payment_info["base_info"][$key] = ["name"=>"付款金额","value"=>$value];
                    unset($payment_info[$key]);
                    break;
                case "received_name":
                    $payment_info["base_info"][$key] = ["name"=>"收款人","value"=>$value];
                    unset($payment_info[$key]);
                    break;
                case "bank_of_deposit":
                    $payment_info["base_info"][$key] = ["name"=>"收款银行","value"=>$value];
                    unset($payment_info[$key]);
                    break;
                case "received_account":
                    $payment_info["base_info"][$key] = ["name"=>"收款账号","value"=>$value];
                    unset($payment_info[$key]);
                    break;
                case "attachment":
                    $payment_info[$key] = explode(",",$value);
                    break;
            }
        }

        return $payment_info;
    }


    public function getAllPayingSettledLists()
    {
        $all_paying_settled_lists = $this->recordList($this->selector,$this->getAllPayingSettledCondition());
        $all_paying_settled_lists["list"] = $this->dealAllFinLists($all_paying_settled_lists["list"]);

        return $all_paying_settled_lists;
    }

    public function getAllPayingSettledCondition()
    {
        return [
            "and",
            [
                "=",
                self::$alias["default"]["finance_record"].".create_uuid",
                Yii::$app->user->identity->uuid,
            ],
            [
                "=",
                self::$alias["default"]["finance_record"].".paid_status",
                self::PaidStatusEnable,
            ],
        ];
    }

    public function getFilterPayingSettledLists($filter)
    {
        $paying_settled_filter_lists = $this->listFilter($this->selector,$filter,$this->getAllPayingSettledCondition());
        $paying_settled_filter_lists["list"] = $this->dealAllFinLists($paying_settled_filter_lists["list"]);

        return $paying_settled_filter_lists;
    }

    public function getAllPaidSettledRecords()
    {
        $paid_settled_records = $this->recordList($this->selector,$this->getAllPaidSettledCondition());
        $paid_settled_records["list"] = $this->dealAllFinLists($paid_settled_records["list"]);

        return $paid_settled_records;
    }

    public function getPaidSettledFilterRecords($filter)
    {
        $paid_settled_filter_lists = $this->listFilter($this->selector,$filter,$this->getAllPaidSettledCondition());
        $paid_settled_filter_lists["list"] = $this->dealAllFinLists($paid_settled_filter_lists["list"]);
        return $paid_settled_filter_lists;
    }

    public function getAllPaidSettledCondition()
    {
        return [
            "and",
            [
                "=",
                self::$alias["default"]["finance_record"].".create_uuid",
                Yii::$app->user->identity->uuid,
            ],
            [
                "=",
                self::$alias["default"]["finance_record"].".paid_status",
                self::PaidStatusDisable,
            ],
        ];
    }

    public function myPaymentSettled($formData)
    {
        return $this->updateRecord($formData);
    }



}

