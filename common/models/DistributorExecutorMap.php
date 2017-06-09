<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 3/23/17
 * Time: 3:47 PM
 */

namespace common\models;

use common\models\BaseRecord;


class DistributorExecutorMap extends BaseRecord
{
    public static function tableName()
    {
        return self::DistributorExecutorMap;
    }

    public function buildRecordListRules()
    {
        return [
            "default" => [
                'rules' => [
                    'distributor_executor_map' => [
                        'alias' => 't1',
                        'table_name' => self::DistributorExecutorMap,
                        'join_condition' => false,
                        'select_build_way' => 0,
                    ],
                ],
            ],
        ];
    }

    public function myInsertRecord($formData)
    {
        $record = $this->getRecord(
            [
                "distributor_executor_map"=> ["*"],
            ],
            [
                "and",
                [
                    "=",
                    "t1.distributor_uuid",
                    $formData["distributor_uuid"],
                ],
                [
                    "=",
                    "t1.executor_uuid",
                    $formData["executor_uuid"],
                ],
            ]
        );
        if(empty($record)){
            return $this->insertRecord($formData);
        }
    }
}