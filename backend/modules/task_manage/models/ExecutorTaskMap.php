<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-5-19
 * Time: 下午6:21
 */

namespace backend\modules\task_manage\models;

use common\models\BaseRecord;
use backend\modules\order_manage\models\Order;


class ExecutorTaskMap extends BaseRecord
{
    const alias = [
        "default" => [
            "task_map" => "t1",
        ],
    ];
    const ExecutingStatusConfirm = Order::ExecutingStatusConfirm; // 待确认
    const ExecutingStatusReceivingWaiting = Order::ExecutingStatusReceivingWaiting; // 待收款
    const ExecutingStatusReceived = Order::ExecutingStatusReceived; // 已收款
    const ExecutingStatusNotPass = Order::ExecutingStatusNotPass; // 未通过
    const ExecutingStatusExpired = Order::ExecutingStatusExpired; // 已过期
    const ExecutingStatusTerminated = Order::ExecutingStatusTerminated; // 已终止

    public static function tableName()
    {
        return parent::EXECUTOR_TASK_MAP;
    }

    public function buildRecordListRules()
    {
        return [
            'default'=>[
                'rules'=>[
                    'task_map'=>[
                        'alias'=>'t1',
                        'table_name'=>self::EXECUTOR_TASK_MAP,
                        'join_condition'=>false,
                        'select_build_way'=>0,
                    ]
                ],
            ]
        ];
    }

    public function getTaskCorrespondOrder($task_uuid_arr)
    {
        $task_orders = $this->recordList(
            [
                "task_map" => [
                    "task_uuid",
                    "status",
                ],
            ],
            [
                "and",
                [
                    "in",
                    self::alias["default"]["task_map"].".task_uuid",
                    $task_uuid_arr,
                ],
                [
                    "in",
                    self::alias["default"]["task_map"].".status",
                    [self::ExecutingStatusConfirm,self::ExecutingStatusReceivingWaiting,self::ExecutingStatusReceived,self::ExecutingStatusNotPass],
                ],
            ],
            false,
            null,
            false
        );

        $task_orders_info = $this->dealTaskCorrespondOrder($task_orders,$task_uuid_arr);
        return $task_orders_info;

    }

    public function dealTaskCorrespondOrder($task_orders,$task_uuid_arr)
    {
        $data = [];
        foreach ($task_uuid_arr as $key => $value){
            $data[$value] = [];
            $data[$value]["received_num"] = 0;
            $data[$value]["submit_num"] = 0;
            unset($task_uuid_arr[$key]);
        }
        foreach ($task_orders as $key => $value){
            if(isset($data[$value["task_uuid"]])){
                $data[$value["task_uuid"]]["submit_num"] += 1;
                if ($value["status"] != self::ExecutingStatusConfirm){
                    $data[$value["task_uuid"]]["received_num"] += 1;
                }
            }
        }
        unset($task_orders);
        return $data;
    }
}