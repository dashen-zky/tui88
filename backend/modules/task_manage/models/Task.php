<?php
/**
 * Created by PhpStorm
 * USER: dashe
 * Date: 2017/3/14
 */

namespace backend\modules\task_manage\models;

use Yii;
use common\models\BaseRecord;
use backend\modules\order_manage\models\Order;

class Task extends BaseRecord
{
    public $pageSize = 20;

    const Distributed = 1;
    const UnDistributed = 2;

    const StatusUnKnow = 128;
    const GettingStatusWaitingStart = 1;
    const GettingStatusEnable = 2;

    const GettingStatusEnd = 3;
    const ExecutingStatusWaiting = 1;
    const ExecutingStatusExecuting = 2;

    const ExecutingStatusEnd = 3;

    const TaskEnable = 2;
    const ScenarioPublish = 'publish';

    const ScenarioDraft = 'draft';

    const ScenarioTerminateTask = 'terminate_task';

    const ScenarioFinishTask = "finish_task";

    public $selector = [
        "task" => [
            "*",
        ],
    ];

    public static $alias = [
        "default" => [
            "task" => "t1",
        ],
    ];

    const config = [
        "getting_status" => [
            self::GettingStatusWaitingStart => "未开始",
            self::GettingStatusEnable => "可领取",
            self::GettingStatusEnd => "已结束",
            self::StatusUnKnow => "待发布",
        ],
        "executing_status" => [
            self::ExecutingStatusWaiting => "待执行",
            self::ExecutingStatusExecuting => "执行中",
            self::ExecutingStatusEnd => "已结束",
            self::StatusUnKnow => "--",
        ],
    ];

    public static function tableName()
    {
        return parent::TASKTABLE;
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::ScenarioDraft => [self::ScenarioDraft],
            self::ScenarioPublish => [self::ScenarioPublish],
            self::ScenarioTerminateTask => [self::ScenarioTerminateTask],
            self::ScenarioFinishTask => [self::ScenarioFinishTask],
        ]);
    }

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

    /**
     * @param $formData
     * @param null $record
     *
     */
    public function formDataPreHandler(&$formData, $record = null)
    {
        if(!empty($record)) {
            switch ($this->getScenario()){
                case self::ScenarioPublish:
                    $formData = $this->dealFormData($formData);
                    $formData['distribute_time'] = time();
                    $formData['order_by_distribute_time'] = strtotime(date("Y-m-d"));
                    $formData['distribute_status'] = self::Distributed;
                    $formData['getting_status']
                        = $formData["start_getting_time"] > time() ? self::GettingStatusWaitingStart : self::GettingStatusEnable;

                    $formData['executing_status']
                        = $formData["start_execute_time"] > time() ? self::ExecutingStatusWaiting : self::ExecutingStatusExecuting;

                    $formData['remain_num'] = $formData['limit'];
                    break;
                case self::ScenarioDraft:
                    $formData = $this->dealFormData($formData);
                    break;
                case self::ScenarioTerminateTask:
                    $formData["enable"] = self::Disable;
                    $formData["getting_status"] = self::GettingStatusEnd;
                    $formData["executing_status"] = self::ExecutingStatusEnd;
                    break;
                case self::ScenarioFinishTask:
                    $formData["getting_status"] = self::GettingStatusEnd;
                    $formData["executing_status"] = self::ExecutingStatusEnd;
                    break;

            }

        } else {
            $formData = $this->dealFormData($formData);
            $formData["create_uuid"] = Yii::$app->user->identity->uuid;
            switch ($this->getScenario()) {
                case self::ScenarioDraft:
                    $formData['distribute_status'] = self::UnDistributed;
                    $formData['getting_status'] = self::StatusUnKnow;
                    $formData['executing_status'] = self::StatusUnKnow;
                    $formData["task_serial_number"] = "1".date("ymd").substr(time(),-5).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
                    break;

                case self::ScenarioPublish:
                    $formData['distribute_time'] = time();
                    $formData['order_by_distribute_time'] = strtotime(date("Y-m-d"));
                    $formData['distribute_status'] = self::Distributed;
                    $formData['getting_status']
                        = $formData["start_getting_time"] > time() ? self::GettingStatusWaitingStart : self::GettingStatusEnable;
                    $formData['executing_status']
                        = $formData["start_execute_time"] > time() ? self::ExecutingStatusWaiting : self::ExecutingStatusExecuting;
                    $formData['remain_num'] = $formData['limit'];
                    $formData["task_serial_number"] = "1".date("ymd").substr(time(),-5).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);

                    break;
            }

        }
        parent::formDataPreHandler($formData, $record);
    }

    public function updateRecordRules($formData = null, $record = null)
    {
        return [
            self::ScenarioTerminateTask => [
                [
                    /**
                     * zai executor_task_map biao biaolimina 更新数据
                     */
                    "class" => Order::className(),
                    "operator" => "orderTerminate",
                    "operator_condition" => true,
                    "params" =>[
                        'task_uuid' => $formData["uuid"],
                    ],
                ]
            ],
        ];

    }

    public function listFilterRules($filter)
    {
        $rules = [];
        $rules["default"] = [];
        $rules["default"]["fields"] = [];
        if(isset($filter["getting_status"]) && $filter["getting_status"] == self::GettingStatusWaitingStart){
            $rules["default"]["fields"]["getting_status"] = [
                "and",
                [
                    "=",
                    "t1.getting_status",
                    $filter["getting_status"],
                ],
                [
                    ">=",
                    "t1.start_getting_time",
                    time(),
                ]

            ];
        }else if(isset($filter["getting_status"]) && $filter["getting_status"] == self::GettingStatusEnable){
            $rules["default"]["fields"]["getting_status"] = [
                "or",
                [
                    "and",
                    [
                        "=",
                        "t1.getting_status",
                        $filter["getting_status"],
                    ],
                    [
                        ">=",
                        "t1.end_getting_time",
                        time(),
                    ],
                ],
                [
                    "and",
                    [
                        "=",
                        "t1.getting_status",
                        self::GettingStatusWaitingStart,
                    ],
                    [
                        "<=",
                        "t1.start_getting_time",
                        time(),
                    ],
                    [
                        ">=",
                        "t1.end_getting_time",
                        time(),
                    ],
                ]

            ];
        }else if(isset($filter["getting_status"]) && $filter["getting_status"] == self::GettingStatusEnd){
            $rules["default"]["fields"]["getting_status"] = [
                "or",
                [
                    "=",
                    "t1.getting_status",
                    $filter["getting_status"],

                ],
                [
                    "and",
                    [
                        "=",
                        "t1.getting_status",
                        self::GettingStatusEnable,
                    ],
                    [
                        "<=",
                        "t1.end_getting_time",
                        time(),
                    ],
                ]

            ];
        }else if(isset($filter["getting_status"]) && $filter["getting_status"] == self::StatusUnKnow){
            $rules["default"]["fields"]["getting_status"] = [
                "=",
                "t1.getting_status",
                $filter["getting_status"],
            ];
        }

        if(isset($filter["executing_status"]) && $filter["executing_status"] == self::ExecutingStatusWaiting){
            $rules["default"]["fields"]["executing_status"] = [
                "and",
                [
                    "=",
                    "t1.executing_status",
                    $filter["executing_status"],
                ],
                [
                    ">=",
                    "t1.start_execute_time",
                    time(),
                ]

            ];
        }else if(isset($filter["executing_status"]) && $filter["executing_status"] == self::ExecutingStatusExecuting){
            $rules["default"]["fields"]["executing_status"] = [
                "or",
                [
                    "and",
                    [
                        "=",
                        "t1.executing_status",
                        $filter["executing_status"],
                    ],
                    [
                        ">=",
                        "t1.end_execute_time",
                        time(),
                    ],
                ],
                [
                    "and",
                    [
                        "=",
                        "t1.executing_status",
                        self::ExecutingStatusWaiting,
                    ],
                    [
                        "<=",
                        "t1.start_execute_time",
                        time(),
                    ],
                    [
                        ">=",
                        "t1.end_execute_time",
                        time(),

                    ],
                ]

            ];
        }else if(isset($filter["executing_status"]) && $filter["executing_status"] == self::ExecutingStatusEnd){
            $rules["default"]["fields"]["executing_status"] = [
                "or",
                [
                    "=",
                    "t1.executing_status",
                    $filter["executing_status"],

                ],
                [
                    "and",
                    [
                        "=",
                        "t1.executing_status",
                        self::ExecutingStatusExecuting,
                    ],
                    [
                        "<=",
                        "t1.end_execute_time",
                        time(),
                    ],
                ]

            ];
        }

        if(isset($filter["titleOrSerialNumber"])){
            $rules["default"]["fields"]["titleOrSerialNumber"] = [
                "or",
                [
                    'like',
                    't1.title',
                    trim($filter['titleOrSerialNumber']),
                ],
                [
                    'like',
                    't1.task_serial_number',
                    trim($filter['titleOrSerialNumber']),
                ],
            ];
        }

        if(isset($filter["publish_start_time"])){
            $rules["default"]["fields"]["publish_start_time"] = [
                ">=",
                "t1.distribute_time",
                strtotime(trim($filter["publish_start_time"])),
            ];
        }

        if(isset($filter["publish_end_time"])){
            $rules["default"]["fields"]["publish_end_time"] = [
                "<=",
                "t1.distribute_time",
                strtotime(trim($filter["publish_end_time"])),
            ];
        }

        return $rules;
    }


    public function dealFormData($formData)
    {
        $this->clearEmptyField($formData);
        $data = array();
        foreach ($formData as $key => $value) {
            switch ($key){
                case "start_getting_time":
                case "end_getting_time":
                case "start_execute_time":
                case "end_execute_time":
                    if(!empty($value)){
                        $data[$key] = strtotime($value);
                    }
                    break;
                case "executor_information_config":
                    if(!empty($value)){
                        $data[$key] = ','.implode(",",$value).',';
                    }
                    break;
                case "unique_config":
                    foreach ($value as $k => $v){
                        $formData[$key][$v] = "unique";
                        unset($formData[$key][$k]);
                    }
                    $data[$key] = json_encode($formData[$key]);
                    unset($formData[$key]);
                    break;
                default :
                    $data[$key] = $value;
                    break;
            }
        }

        $data["total_money"] = $data["limit"] * $data["unit_money"];
        return $data;
    }

    public function getMyTaskLists()
    {
        $recordList = $this->recordList(
            [
                'task'=>[
                    '*'
                ]
            ],
            [
                '=',
                't1.create_uuid',
                Yii::$app->user->identity->uuid
            ]
        );
        $recordList['list'] = $this->dealTaskLists($recordList['list']);
        $task_uuid_arr = $this->dealTaskListsToTaskUuidArr($recordList["list"]);
        $exec_task_map = new ExecutorTaskMap;
        $exec_task_map_info = $exec_task_map->getTaskCorrespondOrder($task_uuid_arr);
        $recordList["list"] = $this->dealRecordListAndExecTaskMapInfo($recordList["list"],$exec_task_map_info);
        return $recordList;
    }


    public function dealTaskLists($task_lists)
    {
        if(empty($task_lists)) {
            return $task_lists;
        }

        foreach ($task_lists as $k =>  $v){
            $task_lists[$k] = $this->dealTask($task_lists[$k]);
        }

        return $task_lists;
    }


    public function dealTask($task) {
        if(empty($task)) {
            return null;
        }
        $data = [];
        foreach ($task as $key => $value) {
            switch ($key){
                case "uuid":
                    $data[] = $value;
                    break;
                case "title":
                    if(mb_strlen($value) > self::BaseTitleLength){
                        $task[$key."_thumbnail"] = mb_substr($value,0,self::BaseTitleLength)."...";
                    }else{
                        $task[$key."_thumbnail"] = $value;
                    }
                    $task[$key."_detailed"] = $value;
                    unset($task[$key]);
                    break;
                case "start_getting_time":
                case "end_getting_time":
                case "start_execute_time":
                case "end_execute_time":
                    $task[$key."_cn"] = empty($value)?'--':date("Y-m-d H:i",$value);
                    break;
                case "create_time":
                case "distribute_time":
                case "update_time":
                    $task[$key."_cn"] = empty($value)?'-':date("Y-m-d H:i",$value);
                    unset($task[$key]);
                    break;
                case "executor_information_config":
                    if(!empty($value)){
                        $task[$key] = explode(",",trim($value,","));
                        $task[$key] = array_flip($task[$key]);
                    }
                    break;
                case "unique_config":
                    if (!empty($value)){
                        $task[$key] = json_decode($value,true);
                    }
                    break;
                case "getting_status":
                    $_time = time();
                    if($task["start_getting_time"] < $_time && $task["end_getting_time"] > $_time && $value == self::GettingStatusWaitingStart){
                        $task[$key."_cn"] = empty($value) ? '待发布' : self::config["getting_status"][self::GettingStatusEnable];
                        $task[$key] = self::GettingStatusEnable;
                    }else if ($task["end_getting_time"] < $_time && $value == self::GettingStatusEnable){
                        $task[$key."_cn"] = empty($value) ? '待发布' : self::config["getting_status"][self::GettingStatusEnd];
                        $task[$key] = self::GettingStatusEnd;
                    }else{
                        $task[$key."_cn"] = empty($value) ? '待发布' : self::config["getting_status"][$value];
                        $task[$key] = $value;
                    }
                    unset($task["start_getting_time"]);
                    unset($task["end_getting_time"]);
                    break;
                case "executing_status":
                    $_time = time();
                    if($task["start_execute_time"] < $_time && $task["end_execute_time"] > $_time && $value == self::ExecutingStatusWaiting){
                        $task[$key."_cn"] = empty($value) ? '-' : self::config["executing_status"][self::ExecutingStatusExecuting];
                        $task[$key] = self::ExecutingStatusExecuting;
                    }else if ($task["end_execute_time"] < $_time && $value == self::ExecutingStatusExecuting){
                        $task[$key."_cn"] = empty($value) ? '-' : self::config["executing_status"][self::ExecutingStatusEnd];
                        $task[$key] = self::ExecutingStatusEnd;
                    }else{
                        $task[$key."_cn"] = empty($value) ? '-' : self::config["executing_status"][$value];
                        $task[$key] = $value;
                    }
                    unset($task["start_execute_time"]);
                    unset($task["end_execute_time"]);
                    break;
                default :
                    $task[$key] = $value;
                    break;
            }
        }


        return $task;
    }

    public function dealTaskListsToTaskUuidArr($task_lists)
    {
        $task_uuid_arr = [];
        foreach ($task_lists as $key => $task){
            foreach ($task as $k=> $value){
                switch ($k){
                    case "uuid":
                        $task_uuid_arr[] = $value;
                        break;
                }
            }
        }
        return $task_uuid_arr;
    }

    public function dealRecordListAndExecTaskMapInfo($lists,$exec_task_map_info)
    {
        foreach ($lists as $key => $list){
            $info = $exec_task_map_info[$list["uuid"]];
            $lists[$key]["received_submit"] = $info;
        }
        return $lists;
    }

    public function getFilterTaskLists($filter)
    {
        $filter_task_lists = $this->listFilter(
            $this->selector,
            $filter,
            $this->getDefaultCondition()
        );

        $filter_task_lists["list"] = $this->dealTaskLists($filter_task_lists["list"]);
        $task_uuid_arr = $this->dealTaskListsToTaskUuidArr($filter_task_lists["list"]);
        $exec_task_map = new ExecutorTaskMap;
        $exec_task_map_info = $exec_task_map->getTaskCorrespondOrder($task_uuid_arr);
        $filter_task_lists["list"] = $this->dealRecordListAndExecTaskMapInfo($filter_task_lists["list"],$exec_task_map_info);
        return $filter_task_lists;
    }

    public function getDefaultCondition()
    {
        $condition = [
            "=",
            self::$alias["default"]["task"].".create_uuid",
            Yii::$app->user->identity->uuid,
        ];
        return $condition;
    }

    public function getMyTaskDetail($uuid)
    {
        $detail = $this->getRecord(
            [
                "task"=>['*'],
            ],
            [
                "and",
                [
                    "=",
                    self::$alias["default"]["task"].".uuid",
                    $uuid,
                ],
            ]
        );

        return $this->dealTask($detail);
    }

    public function TerminateTask($formData)
    {
        $this->setScenario(self::ScenarioTerminateTask);
        return $this->updateRecord($formData);
    }
}