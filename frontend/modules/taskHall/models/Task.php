<?php
/**
 * Created by PhpStorm
 * USER: dashe
 * Date: 2017/3/16
 */

namespace frontend\modules\taskHall\models;

use common\models\BaseRecord;
use common\models\DistributorExecutorMap;

class Task extends BaseRecord
{
    const GettingStatusWaitingStart = \backend\modules\task_manage\models\Task::GettingStatusWaitingStart; // 未开始状态
    const GettingStatusEnable = \backend\modules\task_manage\models\Task::GettingStatusEnable; // 可领取状态
    const GettingStatusEnd = \backend\modules\task_manage\models\Task::GettingStatusEnd; // 可领取状态
    const Distributed = \backend\modules\task_manage\models\Task::Distributed;
    public $pageSize = 20;
    const RemainEnable = 0;

    const ScenarioAbandon = 'ScenarioAbandon';
    const ScenarioGettingTask = 'ScenarioGettingTask';

    public static $config = [
        'getting_status'=>[
            self::GettingStatusWaitingStart => '未开始',
            self::GettingStatusEnable => '可领取',
            self::GettingStatusEnd => '已结束',
        ],
    ];

    const CompositeOrder = 1;
    const _CompositeOrder = 19;
    const DistributedTimeDesc = 12;
    const DistributedTimeAsc = 8;
    const RemainNumDesc = 13;
    const RemainNumAsc = 7;
    const UnitMoneyDesc = 14;
    const UnitMoneyAsc = 6;

    const alias = [
        "default"=>[
            "task"=>"t1",
        ],
    ];

    public $selector = [
        "task" =>[
            "uuid",
            "title",
            "unit_money",
            "limit",
            "remain_num",
            "distribute_time",
            "create_uuid",
            "start_getting_time",
            "end_getting_time",
            "start_execute_time",
            "end_execute_time",
            "getting_status",
        ],
    ];

    public static function tableName()
    {
        return self::TASKTABLE;
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(),[
            static::ScenarioAbandon => ['id'],
            static::ScenarioGettingTask => ['id'],
        ]);
    }

    public function listFilterRules($filter)
    {
        $order_by_map = [
            self::CompositeOrder => [
                self::alias["default"]["task"].'.order_by_distribute_time'=>SORT_DESC,
                self::alias["default"]["task"].".remain_num"=>SORT_ASC,
            ],
            self::_CompositeOrder => [
                self::alias["default"]["task"].'.order_by_distribute_time'=>SORT_DESC,
                self::alias["default"]["task"].".remain_num"=>SORT_ASC,
            ],
            self::DistributedTimeDesc => [
                self::alias["default"]["task"].'.distribute_time'=>SORT_DESC,
            ],
            self::DistributedTimeAsc => [
                self::alias["default"]["task"].'.distribute_time'=>SORT_ASC,
            ],
            self::RemainNumDesc => [
                self::alias["default"]["task"].'.remain_num'=>SORT_DESC,
            ],
            self::RemainNumAsc => [
                self::alias["default"]["task"].'.remain_num'=>SORT_ASC,
            ],
            self::UnitMoneyDesc => [
                self::alias["default"]["task"].'.unit_money'=>SORT_DESC,
            ],
            self::UnitMoneyAsc => [
                self::alias["default"]["task"].'.unit_money'=>SORT_ASC,
            ],
        ];

        return [
            'default' => [
                'fields'=>[
                    'getting_status'=>[
                        '=',
                        't1.getting_status',
                        isset($filter['getting_status'])?$filter['getting_status']:null,
                    ],
                    'min_price' => [
                        '>=',
                        't1.unit_money',
                        isset($filter['min_price'])? trim($filter['min_price']):null,
                    ],
                    'max_price' => [
                        '<=',
                        't1.unit_money',
                        isset($filter['max_price'])? trim($filter['max_price']):null,
                    ],
                    'task_title'=> [
                        'like',
                        't1.title',
                        isset($filter['task_title'])? trim($filter['task_title']) :null,
                    ]
                ],
                'orderBy'=>isset($filter['orderBy']) && isset($order_by_map[$filter['orderBy']])
                    ?$order_by_map[$filter['orderBy']]:$order_by_map[self::CompositeOrder],
            ],
        ];
    }

    /**
     * @return array
     */
    public function buildRecordListRules()
    {
        return [
            'default'=>[
                'rules'=>[
                    'task'=>[
                        'alias'=>'t1',
                        'table_name'=>self::TASKTABLE,
                        'join_condition'=>false,
                        'select_build_way'=>0,
                    ]
                ],
            ]
        ];
    }

    public function formDataPreHandler(&$formData, $record = null)
    {
        parent::formDataPreHandler($formData, $record);
        if(empty($record)){

        }else{
            $flag = false;
            switch ($this->getScenario()){
                case self::ScenarioGettingTask:
                    if($formData["get_task_num"] > $record->remain_num){
                        $flag = false;
                        $formData["dist"]["flag"] = false;
                        break;
                    }
                    $flag = true;
                    if($formData["get_task_num"] == $record->remain_num){
                        $formData["getting_status"] = static::GettingStatusEnd;
                    }
                    $record->remain_num -= $formData["get_task_num"];
                    $record->number_of_gets += $formData["get_task_num"];
                    $formData["dist"]["get_task_num"] = $formData["get_task_num"];
                    $formData["dist"]["flag"] = true;
                    unset($formData["get_task_num"]);
                    break;
                default :
                    $flag = true;
                    break;
            }
        }
        if(!$flag){
            echo json_encode(["code"=>6,"message"=>"领取数量过多"]);die;
        }
    }

    public function updateRecordRules($formData = null, $record = null)
    {
        return [
            static::ScenarioGettingTask => [
                [
                    /**
                     * 领取任务的时候在distributor_executor_map表里面添加一条数据
                     */
                    'class' => DistributorExecutorMap::className(),
                    'operator' => 'myInsertRecord',
                    'operator_condition' => true,
                    'params' => [
                        'distributor_uuid' => isset($formData["dist"]['distributor_uuid']) ? $formData["dist"]['distributor_uuid'] : null,
                        'executor_uuid' => isset($formData["dist"]['executor_uuid']) ? $formData["dist"]['executor_uuid'] : null,
                        "enable" => self::Enable,
                        "flag" => isset($formData["dist"]["flag"]) ? $formData["dist"]["flag"] : null,
                    ],
                ],
                [
                    /**
                     * 领取任务的时候在 executor_task_map 表里面更新数据
                     */
                    "class" => ExecutorTaskMap::className(),
                    "operator" => "myGettingOrders",
                    "operator_condition" => true,
                    "params" =>[
                        'task_uuid' => isset($formData["uuid"]) ? $formData["uuid"] : null,
                        'distributor_uuid' => isset($formData["dist"]['distributor_uuid']) ? $formData["dist"]['distributor_uuid'] : null,
                        'executor_uuid' => isset($formData["dist"]['executor_uuid']) ? $formData["dist"]['executor_uuid'] : null,
                        'start_executor_time' => $record->start_execute_time,
                        'end_executor_time' => $record->end_execute_time,
                        "get_task_num" => isset($formData["dist"]["get_task_num"]) ? $formData["dist"]["get_task_num"] : null,
                        "flag" => isset($formData["dist"]["flag"]) ? $formData["dist"]["flag"] : null,
                    ],
                ]
            ],
        ];
    }

    /**
     * 获取任务列表
     * @param  $andWhere　筛选的条件
     * @return $recordList　　　任务列表
     */
    public function getTaskLists()
    {
        $myCondition = $this->getDisableDistributor();
        $recordList = $this->recordList($this->selector,$this->getDefaultCondition($myCondition),false,$this->getDefaultOrderBy());
        $recordList['list'] = $this->dealTaskLists($recordList['list']);

        return $recordList;
    }

    public function getDisableDistributor()
    {
        $dist = new DistExecMap;
        $dist_list = $dist->recordList(
            [
                "distributor_executor_map"=>[
                    "distributor_uuid",
                ]
            ],
            [
                "and",
                [
                    "=",
                    DistExecMap::alias["default"]["distributor_executor_map"].".enable",
                    self::Disable,
                ],
                [
                    "=",
                    DistExecMap::alias["default"]["distributor_executor_map"].".executor_uuid",
                    \Yii::$app->user->identity->uuid,
                ],
            ],
            false,
            null,
            false
        );
        $myCondition = $dist->dealDistributorOfExecutorDisable($dist_list);
        return $myCondition;
    }

    /**
     * 处理多个任务　
     * @param $task_lists
     * @return mixed
     */
    public function dealTaskLists($task_lists)
    {
        if(empty($task_lists)){
            return $task_lists;
        }

        foreach ($task_lists as $key => $item){
            $task_lists[$key] = $this->dealTask($item);
        }

        return $task_lists;
    }

    /**
     * 处理单个任务
     * @param $task
     * @return $task
     */
    public function dealTask($task) {
        if(empty($task)){
            return $task;
        }
        foreach ($task as $k => $value){
            switch ($k){
                case "title":
                    if (mb_strlen($value) >= 20){
                        $task[$k] = mb_substr($value,0,20)."...";
                    }
                    $task[$k."_cn"] = $value;
                    break;
                case "start_getting_time":
                    $task[$k."_cn"] = date("Y.m.d H:i",$value);
                    break;
                case "end_getting_time":
                case "start_execute_time":
                case "end_execute_time":
                case "distribute_time":
                    $task[$k."_cn"] = date("Y.m.d H:i",$value);
                    break;
                case "getting_status":
                    if(self::GettingStatusEnd == $value){
                        $task[$k."_cn"] = self::$config["getting_status"][self::GettingStatusEnd];
                        break;
                    }
                    if($task["start_getting_time"] < time() && $task["end_getting_time"] > time()){
                        $task[$k."_cn"] = self::$config["getting_status"][self::GettingStatusEnable];
                        $task[$k] = self::GettingStatusEnable;
                    }else if($task["start_getting_time"] > time()){
                        $task[$k."_cn"] = self::$config["getting_status"][self::GettingStatusWaitingStart];
                        $task[$k] = self::GettingStatusWaitingStart;
                    }else if($task["end_getting_time"] < time()){
                        $task[$k."_cn"] = self::$config["getting_status"][self::GettingStatusEnd];
                        $task[$k] = self::GettingStatusEnd;
                    }
                    break;
                case "executor_information_config":
                    $task[$k] = explode(",",trim($value,","));
                    $task[$k] = array_flip($task[$k]);
                    break;
            }
        }
        return $task;
    }


    /**
     * 查询　筛选　过后的　任务
     * @param $filter
     * @return filter_task_lists;　
     */
    public function getFilterTaskLists($filter)
    {
        $myCondition = $this->getDisableDistributor();
        $filter_task_lists = $this->listFilter(
            $this->selector,
            $filter,
            $this->getDefaultCondition($myCondition)
        );

        $filter_task_lists["list"] = $this->dealTaskLists($filter_task_lists["list"]);
        return $filter_task_lists;

    }

    /**
     * 返回默认的查询条件
     * @param $myCondition
     * @return defaultCondition
     */
    private function getDefaultCondition($myCondition)
    {
        $defaultCondition = [
            "and",
            [
                "in",
                self::alias["default"]["task"].".getting_status",
                [
                    self::GettingStatusEnable,
                    self::GettingStatusWaitingStart,
                ],
            ],
            [
                ">",
                self::alias["default"]["task"].".end_getting_time",
                time(),
            ],
            [
                "=",
                self::alias["default"]["task"].".distribute_status",
                self::Distributed,
            ],
            [
                ">",
                self::alias["default"]["task"].".remain_num",
                self::RemainEnable,

            ],
            [
                "not in",
                self::alias["default"]["task"].".create_uuid",
                $myCondition,
            ]
        ];

        return $defaultCondition;
    }


    /**
     * 返回默认　排序规则
     * @return array
     */
    public function getDefaultOrderBy()
    {
        return [
            self::alias["default"]["task"].".order_by_distribute_time"=>SORT_DESC,
            self::alias["default"]["task"].".remain_num"=>SORT_ASC,
        ];
    }

    public function getTaskRecord($uuid)
    {
        return $this->dealTask($this->getRecord(
            [
                "task"=>["*"],
            ],
            [
                "and",
                [
                    "=",
                    self::alias["default"]["task"].".distribute_status",
                    self::Distributed,
                ],
                [
                    "=",
                    self::alias["default"]["task"].".uuid",
                    $uuid,
                ]

            ]));
    }

    public function isTaskEnableGetting($task_uuid){
        return $this->getRecord(
            [
                "task" => [
                    "uuid as task_uuid",
                    "remain_num",
                ],
            ],
            [
                "and",
                [
                    "=",
                    self::alias["default"]["task"].'.uuid',
                    $task_uuid,
                ],
                [
                    ">",
                    self::alias["default"]["task"].'.remain_num',
                    self::RemainEnable,

                ],
                [
                    "in",
                    self::alias["default"]["task"].'.getting_status',
                    [
                        self::GettingStatusWaitingStart,
                        self::GettingStatusEnable,
                    ],
                ],
                [
                    ">",
                    self::alias["default"]["task"].'.end_getting_time',
                    time(),
                ]

            ]
        );
    }


    public function reduceRemainNum($formData)
    {
        $task= $this->getRecord(
            [
                "task"=>["remain_num","number_of_gets"],
            ],
            [
                "=",
                "t1.uuid",
                $formData["uuid"],
            ]
        );
        $formData["remain_num"] = $task["remain_num"] -1;
        $formData["number_of_gets"] = $task["number_of_gets"] +1;
        if($formData["remain_num"] == 0){
            $formData["getting_status"] = self::GettingStatusEnd;
        }

        $this->updateRecord($formData);
    }

    public function myGettingTask($formData)
    {
        $this->setScenario(static::ScenarioGettingTask);
        return $this->updateRecord($formData);

    }

    public function twitterAbandonTask($formData)
    {
        $this->setScenario(static::ScenarioAbandon);
        return $this->updateRecord($formData);
    }

    public function recordPreHandler(&$formData, $record = null)
    {
        if(!parent::recordPreHandler($formData, $record)) {
            return false;
        }

        if (empty($record)) {
            return true;
        }

        switch ($record->getScenario()) {
            case static::ScenarioAbandon:
                if ($record->number_of_gets <= 0) {
                    break;
                }

                if($record->end_getting_time < time()){
                    break;
                }

                $record->number_of_gets -= 1;
                $record->remain_num += 1;
                $record->getting_status = self::GettingStatusEnable;
                break;
        }

        return true;
    }
}