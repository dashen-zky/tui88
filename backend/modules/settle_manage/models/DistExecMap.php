<?php
/**
 * Created by PhpStorm
 * USER: dashe
 * Date: 2017/5/3
 */

namespace backend\modules\settle_manage\models;

use common\models\BaseRecord;

class DistExecMap extends BaseRecord
{

    const alias = [
        "default" => [
            "distributor_executor_map" => "t1",
            "task_map" => "t2",
        ],
    ];

    public static function tableName()
    {
        return self::DistributorExecutorMap;
    }

    public function buildRecordListRules()
    {
        return [
            "default" => [
                'rules'=>[
                    "distributor_executor_map" => [
                        'alias'=>self::alias["default"]["distributor_executor_map"],
                        'table_name'=>self::DistributorExecutorMap,
                        'join_condition'=>false,
                        'select_build_way'=>0,
                    ],
                    "task_map" => [
                        'alias'=>self::alias["default"]["task_map"],
                        'table_name'=>self::EXECUTOR_TASK_MAP,
                        'join_condition'=>self::alias["default"]["task_map"].".distributor_uuid = ".self::alias["default"]["distributor_executor_map"].".distributor_uuid and ".self::alias["default"]["task_map"].".executor_uuid = ".self::alias["default"]["distributor_executor_map"].".executor_uuid",
                        'select_build_way'=>0,
                    ],
                ],
            ],
        ];
    }

    public function getAllSettleOrder($dist_exec_map_ids)
    {
        $all_settle_order =  $this->recordList(
            [
                "distributor_executor_map" => ["executor_uuid as dist_id"],
                "task_map" => ["id as task_map_id"],
            ],
            [
                "and",
                [
                    "in",
                    self::alias["default"]["distributor_executor_map"].".id",
                    $dist_exec_map_ids,
                ],
                [
                    "=",
                    self::alias["default"]["task_map"].".settle_status",
                    self::Enable,
                ],

            ],
            false,
            null,
            false
        );

        $all_settle_order = $this->dealMyAllSettleOrder($all_settle_order);
        return $all_settle_order;
    }

    public function dealMyAllSettleOrder($all_settle_order)
    {
        $data = [];
        foreach ($all_settle_order as $k => $value){
            foreach ($value as $key => $item ){
                switch ($key){
                    case "task_map_id":
                        $data[$value["dist_id"]][] = $item;
                        break;
                }
            }
        }
        return $data;
    }

}