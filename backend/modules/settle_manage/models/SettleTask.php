<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-4-20
 * Time: 下午2:53
 */

namespace backend\modules\settle_manage\models;

use Yii;
use yii\db\Expression;
use common\models\BaseRecord;
use common\helpers\PlatformHelper;
use backend\modules\order_manage\models\Order;
use backend\modules\user_manage\models\DistributorExecutorMap;


class SettleTask extends BaseRecord
{
    const SettleStatusNotSettle = 1;
    const SettleStatusSettled = 2;

    public static function tableName()
    {
        return self::Finance_Record;
    }

    public function formDataPreHandler(&$formData, $record = null)
    {
        parent::formDataPreHandler($formData, $record);
        if(empty($record)){
            $dist_exec_map = new DistributorExecutorMap();
            $dist_exec_map_info = $dist_exec_map->getRecord(
                [
                    "distributor_executor_map" => ["id as dist_exec_map_id"],
                    "frontend_user_information" => ["finance_information"],
                    "frontend_user" => ["phone"],
                ],
                [
                    "and",
                    [
                        "=",
                        DistributorExecutorMap::alias["default"]["distributor_executor_map"].".executor_uuid",
                        $formData["executor_uuid"],
                    ],
                    [
                        "=",
                        DistributorExecutorMap::alias["default"]["distributor_executor_map"].".distributor_uuid",
                        Yii::$app->user->identity->uuid,
                    ],

                ]
            );

            if(empty($dist_exec_map_info)){
                return false;
            }

            $order = new Order;
            $orders_info = $order->recordList(
                [
                    "task_map" => [
                        "id",
                        "insure_money"
                    ],
                ],
                [
                    "and",
                    [
                        "in",
                        Order::$alias["default"]["task_map"].".id",
                        $formData["executor_task_ids"],
                    ],
                    [
                        "=",
                        Order::$alias["default"]["task_map"].".settle_status",
                        self::SettleStatusNotSettle,
                    ],
                    [
                        "=",
                        Order::$alias["default"]["task_map"].".status",
                        Order::ExecutingStatusReceivingWaiting,
                    ],
                ],
                false,
                null,
                false
            );

            if(empty($orders_info)){
                return false;
            }

            $formData["executor_task_ids"] = [];
            $formData["money"] = 0;
            $formData["number_of_order"] = 0;
            foreach ($orders_info as $key => $item) {
                $formData["executor_task_ids"][] = $item["id"];
                $formData["money"] += $item["insure_money"];
                $formData["number_of_order"] += 1;
            }
            $formData["create_uuid"] = isset($formData["create_uuid"]) ? $formData["create_uuid"] : Yii::$app->user->identity->uuid;
            $formData["received_uuid"] = $formData["executor_uuid"];
            $formData["ser_number"] = "1".date("ymd").substr(time(),-5).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
            $formData["received_phone"] = $dist_exec_map_info["phone"];

            $formData["dist_exec_map_id"] = $dist_exec_map_info["dist_exec_map_id"];

            $formData["bank_card_info"] = json_decode($dist_exec_map_info["finance_information"],true);
            $formData["received_name"] = str_replace(" ","",$formData["bank_card_info"]["bank_card_name"]);
            $formData["bank_of_deposit"] = str_replace(" ","",$formData["bank_card_info"]["bank_name_opening"]);
            $formData["received_account"] = str_replace(" ","",$formData["bank_card_info"]["bank_card_num"]);
            unset($dist_exec_map,$dist_exec_map_info,$order,$orders_info,$formData["bank_card_info"]);
        }
    }

    public function insertRecordRules($formData = null, $record = null)
    {
        return [
            'default' => [
                [
                    /**
                     *jiesuan zai distributor_executor_map 任务的时候在表里面更新一条数据
                     */
                    "class" => DistributorExecutorMap::className(),
                    "operator" => "mySettleUpDate",
                    "operator_condition" => true,
                    "params" =>[
                        "id" => $formData["dist_exec_map_id"],
                        'money' => $formData["money"],
                        'number_of_order' => $formData["number_of_order"],
                    ],
                ],
                [
                    /**
                     *jiesuan zai executor_task_map biao biaolimina 更新数据
                     */
                    "class" => Order::className(),
                    "operator" => "mySettleStatusUpDate",
                    "operator_condition" => true,
                    "params" =>[
                        'id' => $formData["executor_task_ids"],
                        'settled_status' => self::SettleStatusSettled,
                        "finance_uuid" => $formData["uuid"],
                    ],
                ]
            ],
        ];

    }

    public function mySomeSettleInsert($formData)
    {
        if(empty($formData["executor_task_ids"])){
            return json_encode(["code"=>1,"message"=>"没有要执行的任务"]);
        }
        $flag = $this->insertRecord($formData);
        return $flag;
    }

    public function myAllOrderSettleInsert($dist_exec_map){

        $formData = [];
        $formData["create_uuid"] = Yii::$app->user->identity->uuid;
        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            foreach ($dist_exec_map as $key => $item){
                $formData["executor_task_ids"] = $item;
                $formData["executor_uuid"] = $key;
                (new SettleTask)->insertRecord($formData);
            }

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $transaction->commit();
        return true;
    }


}