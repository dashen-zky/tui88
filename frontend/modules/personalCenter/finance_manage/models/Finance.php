<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-4-28
 * Time: 下午1:52
 */

namespace frontend\modules\personalCenter\finance_manage\models;

use common\models\BaseRecord;

class Finance extends BaseRecord
{
    const PaidStatus = \backend\modules\fin_manage\models\Finance::PaidStatusDisable;
    const PayStatus = \backend\modules\fin_manage\models\Finance::PaidStatusEnable;
    const SeeStatus = \backend\modules\fin_manage\models\Finance::SeeStatus;
    const SawStatus = \backend\modules\fin_manage\models\Finance::SawStatus;

    const alias = [
        "default" => [
            "finance_record" => "t1",
            "order_finance_map" => "t2",
        ],
    ];

    public $selector = [
        "finance_record" => [
            "id as finance_id",
            "uuid as finance_uuid",
            "paid_time",
            "paid_status",
            "money",
            "number_of_order",
            "see_status",
        ],
    ];

    public $pageSize = 20;

    public static function tableName()
    {
        return self::Finance_Record;
    }

    public function buildRecordListRules()
    {
        return [
            'default'=>[
                'rules'=>[
                    'finance_record'=>[
                        'alias'=>'t1',
                        'table_name'=>self::Finance_Record,
                        'join_condition'=>false,
                        'select_build_way'=>0,
                    ],
                ],
            ]
        ];
    }

    public function listFilterRules($filter)
    {

        return [
            'default' => [
                'fields'=>[
                    'min_price' => [
                        '>=',
                        't1.money',
                        isset($filter['min_price'])?$filter['min_price']:null,
                    ],
                    'max_price' => [
                        '<=',
                        't1.money',
                        isset($filter['max_price'])?$filter['max_price']:null,
                    ],
                    'start_time'=> [
                        '>=',
                        't1.create_time',
                        isset($filter['start_time'])?strtotime($filter['start_time']):null,
                    ],
                    'end_time'=> [
                        '<=',
                        't1.create_time',
                        isset($filter['end_time'])?strtotime($filter['end_time']):null,
                    ]
                ],
            ],
        ];
    }

    public function getAllFinanceLists(){
        $all_fin_lists = $this->recordList($this->selector,$this->getAllFinanceCondition());
        $all_fin_lists["list"] = $this->dealAllFinanceLists($all_fin_lists["list"]);
        $this->batchUpdateRecords(["see_status"=>self::SawStatus]);
        return $all_fin_lists;
    }

    public function beforeBatchUpdateRecord($e)
    {
        $operator = $e->sender;
        $operator->table = static::tableName();
        $operator->column = [
            "see_status" => $e->formData["see_status"],
        ];
        $operator->condition = [
            "paid_status" => self::PaidStatus,
            "received_uuid" => \Yii::$app->user->identity->uuid,

        ];
        return $e->isValid;
    }

    public function getAllFinanceCondition()
    {
        return [
            "and",
            [
                "=",
                self::alias["default"]["finance_record"].".paid_status",
                self::PaidStatus,
            ],
            [
                "=",
                self::alias["default"]["finance_record"].".received_uuid",
                \Yii::$app->user->identity->uuid,
            ],
        ];
    }

    public function dealAllFinanceLists($all_finance_lists)
    {
        if (empty($all_finance_lists)){
            return null;
        }
        foreach ($all_finance_lists as $key => $item){
            $all_finance_lists[$key] = $this->dealFinanceList($item);
        }

        return $all_finance_lists;
    }

    public function dealFinanceList($finance_list)
    {
        if(empty($finance_list)){
            return null;
        }
        foreach ($finance_list as $key => $value) {
            switch ($key){
                case "paid_time" :
                    $finance_list[$key] = date("Y.m.d H:i",$value);
                    break;
            }
        }

        return $finance_list;
    }

    public function getFilterFinanceLists($filter)
    {
        $filter_lists = $this->listFilter(
            $this->selector,
            $filter,
            $this->getFilterCondition()
        );
        $filter_lists["list"] = $this->dealAllFinanceLists($filter_lists["list"]);
        return $filter_lists;
    }

    public function getFilterCondition()
    {
        return [
            "and",
            [
                "=",
                self::alias["default"]["finance_record"].".paid_status",
                self::PaidStatus,
            ],
            [
                "=",
                self::alias["default"]["finance_record"].".received_uuid",
                \Yii::$app->user->identity->uuid,
            ],
        ];
    }

    public function GetMyPaymentInfo($finance_id,$paid_status)
    {
        $selector = [
            "finance_record" => [
                "money",
                "attachment",
                "remarks",
                "number_of_order",
            ],
        ];

        $payment_info = $this->getRecord(
            $selector,
            [
                "and",
                [
                    "=",
                    self::alias["default"]["finance_record"].".id",
                    $finance_id,
                ],
                [
                    "=",
                    self::alias["default"]["finance_record"].".paid_status",
                    $paid_status,
                ],
            ]
        );
        return $this->dealPaymentInfo($payment_info);
    }

    public function dealPaymentInfo($payment_info)
    {
        if (empty($payment_info)){
            return null;
        }
        foreach ($payment_info as $key => $value){
            switch ($key){
                case "attachment":
                    $payment_info[$key] = explode(",",$value);
                    break;
            }
        }

        return $payment_info;
    }
}