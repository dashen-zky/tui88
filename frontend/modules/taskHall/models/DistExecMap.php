<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-5-3
 * Time: 上午9:37
 */

namespace frontend\modules\taskHall\models;

use common\models\BaseRecord;
use frontend\modules\personalCenter\finance_manage\models\Finance;

class DistExecMap extends BaseRecord
{
    const DistributorExecutorMoney = "distributor_executor_money";

    const alias = [
        "default" => [
            "distributor_executor_map" => "t1",
            "finance_record" => "t2",
        ],
        self::DistributorExecutorMoney => [
            "distributor_executor_map" => "t1",
        ],
    ];


    public static function tableName()
    {
        return self::DistributorExecutorMap;
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(),[
            self::DistributorExecutorMoney => [self::DistributorExecutorMoney],
        ]);
    }

    public function buildRecordListRules()
    {
        return [
            'default'=>[
                'rules'=>[
                    'distributor_executor_map'=>[
                        'alias'=> self::alias["default"]["distributor_executor_map"],
                        'table_name'=>self::DistributorExecutorMap,
                        'join_condition'=>false,
                        'select_build_way'=>0,
                    ],
                    'finance_record'=>[
                        'alias'=> self::alias["default"]["finance_record"],
                        'table_name'=>self::Finance_Record,
                        'join_condition'=>self::alias["default"]["finance_record"].".create_uuid = ".self::alias["default"]["distributor_executor_map"].".distributor_uuid and ".self::alias["default"]["finance_record"].".received_uuid = ".self::alias["default"]["distributor_executor_map"].".executor_uuid",
                        'select_build_way'=>0,
                    ]
                ],
            ],
            self::DistributorExecutorMoney => [
                'rules'=>[
                    'distributor_executor_map'=>[
                        'alias'=> self::alias[self::DistributorExecutorMoney]["distributor_executor_map"],
                        'table_name'=>self::DistributorExecutorMap,
                        'join_condition'=>false,
                        'select_build_way'=>0,
                    ],
                ],
            ],
        ];
    }


    public function isEnable($executor_uuid , $distributor_uuid)
    {
        return $this->getRecord(
            [
                "distributor_executor_map"=>["*"],
            ],
            [
                "and",
                [
                    "=",
                    self::alias["default"]["distributor_executor_map"].".executor_uuid",
                    $executor_uuid,
                ],
                [
                    "=",
                    self::alias["default"]["distributor_executor_map"].".distributor_uuid",
                    $distributor_uuid,
                ],
                [
                    "=",
                    self::alias["default"]["distributor_executor_map"].".enable",
                    self::Disable,
                ],
            ]
        );
    }

    public function getMyMoneyInfo()
    {

        $data = $this->recordList(
            [
                "finance_record" => [
                    "money",
                ],
            ],
            [
                "and",
                [
                    "=",
                    self::alias["default"]["finance_record"].".received_uuid",
                    \Yii::$app->user->identity->uuid,
                ],
                [
                    "=",
                    self::alias["default"]["finance_record"].".paid_status",
                    Finance::PaidStatus,
                ],
                [
                    ">",
                    self::alias["default"]["finance_record"].".paid_time",
                    strtotime(date("Y-m",time())),
                ],

            ],
            false,
            null,
            false
        );
        $this->setScenario(self::DistributorExecutorMoney);
        $dist_exec_map_info = $this->recordList(
            [
                "distributor_executor_map" => [
                    "total_revenue",
                    "received_revenue",
                    "wait_revenue",
                ],
            ],
            [
                "=",
                self::alias["default"]["distributor_executor_map"].".executor_uuid",
                \Yii::$app->user->identity->uuid,

            ],
            false,
            null,
            false
        );
        $data = $this->dealMoneyData($data);
        $dist_exec_map_info = $this->dealDistExecMapInfo($dist_exec_map_info);
        return array_merge($dist_exec_map_info ? $dist_exec_map_info : [],$data);
    }

    public function dealDistExecMapInfo($dist_exec_map_info)
    {
        if(empty($dist_exec_map_info)){
            return $dist_exec_map_info;
        }
        foreach ($dist_exec_map_info as $key => $item){
            foreach ($item as $k => $value){
                if(isset($dist_exec_map_info[$k])){
                    $dist_exec_map_info[$k] += $value;
                }else{
                    $dist_exec_map_info[$k] = $value;
                }
            }
            unset($dist_exec_map_info[$key]);
        }
        return $dist_exec_map_info;

    }

    public function dealMoneyData($data)
    {
        $moneyData["this_month_money"] = "0.00";
        if (empty($data)) {
            return $moneyData;
        }
        foreach ($data as $key => $item) {
            $moneyData["this_month_money"] += $item["money"];
            unset($data[$key]);
        }
        $this_month_money = explode(".",$moneyData["this_month_money"]);
        if(isset($this_month_money[1]) && strlen($this_month_money[1]) == 2){
            $moneyData["this_month_money"] = $this_month_money[0].".".$this_month_money[1];
        }else if(isset($this_month_money[1]) && strlen($this_month_money[1]) == 1){
            $moneyData["this_month_money"] = $this_month_money[0].".".$this_month_money[1]."0";
        }else {
            $moneyData["this_month_money"] = $this_month_money[0].".00";
        }
        return $moneyData;

    }

    /**
     * @param $data
     * @return array
     */
    public function dealDistributorOfExecutorDisable($data)
    {
        if(empty($data)){
            return [];
        }
        foreach ($data as $key => $item){
            $data[$key] = $item["distributor_uuid"];
        }
        return $data;
    }


}