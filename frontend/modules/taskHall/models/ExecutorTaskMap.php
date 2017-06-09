<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 3/23/17
 * Time: 1:29 PM
 */

namespace frontend\modules\taskHall\models;

use Yii;
use yii\db\Expression;
use common\models\BaseRecord;
use common\helpers\ExternalFileHelper;
use common\models\DistributorExecutorMap;
use frontend\modules\taskHall\models\Task;
use backend\modules\order_manage\models\Order;

class ExecutorTaskMap extends BaseRecord
{
    const alias = [
        'default' => [
            'task_map' => 't1',
            "task" => "t2",
            "finance_record" => "t3",
        ]
    ];

    public $selector = [
        "task_map"=>
            [
                "id",
                "task_uuid",
                "serial_number",
                "status",
                "task_uuid",
                "insure_time",
                "insure_money",
            ],
        "task" =>
            [
                "title",
                "start_execute_time",
                "end_execute_time",
                "unit_money",
            ],
    ];

    const ExecutingStatusWaiting = Order::ExecutingStatusWaiting;
    const ExecutingStatusExecuting = Order::ExecutingStatusExecuting;
    const ExecutingStatusConfirm = Order::ExecutingStatusConfirm;
    const ExecutingStatusReceivingWaiting = Order::ExecutingStatusReceivingWaiting;
    const ExecutingStatusReceived = Order::ExecutingStatusReceived;

    const ExecutingStatusDefeated = 128;
    const ExecutingStatusExpired = Order::ExecutingStatusExpired;
    const ExecutingStatusGiveUp = Order::ExecutingStatusGiveUp;
    const ExecutingStatusNotPass = Order::ExecutingStatusNotPass;
    const ExecutingStatusTerminated = Order::ExecutingStatusTerminated;

    const GettingStatusWaitingStart = \backend\modules\task_manage\models\Task::GettingStatusWaitingStart;
    const GettingStatusEnable = \backend\modules\task_manage\models\Task::GettingStatusEnable;
    const GettingStatusEnd = \backend\modules\task_manage\models\Task::GettingStatusEnd;


    public $pageSize = 20;
    public static $config = [
        'status'=>[
            self::ExecutingStatusWaiting => '待执行',
            self::ExecutingStatusExecuting => '执行中',
            self::ExecutingStatusConfirm => '待确认',
            self::ExecutingStatusReceivingWaiting => '待收款',
            self::ExecutingStatusReceived => '已收款',

            self::ExecutingStatusExpired => '失败-已过期',
            self::ExecutingStatusGiveUp => '失败-已放弃',
            self::ExecutingStatusNotPass => '失败-未通过',
            self::ExecutingStatusTerminated => '失败-已终止',
        ],
        "getting_status" => [
            self::GettingStatusWaitingStart => '未开始',
            self::GettingStatusEnable => '可领取',
            self::GettingStatusEnd => '已结束',
        ],
    ];

    const AbandonScenario = "abandon";
    const submitImageScenario = "submitImage";
    const submitTaskScenario = "submitTask";
    const FileDeleteScenario = "fileDelete";
    const GettingOrdersScenario = "gettingOrders";

    public static function tableName()
    {
        return self::EXECUTOR_TASK_MAP;
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(),[
            self::AbandonScenario=>[self::AbandonScenario],
            self::submitTaskScenario=>[self::submitTaskScenario],
            self::FileDeleteScenario=>[self::FileDeleteScenario],
            self::GettingOrdersScenario=>['id'],
        ]);
    }

    public function buildRecordListRules()
    {
        return [
            "default" => [
                'rules' => [
                    'task_map' => [
                        'alias' => 't1',
                        'table_name' => self::EXECUTOR_TASK_MAP,
                        'join_condition' => false,
                        'select_build_way' => 0,
                    ],
                    "task" => [
                        'alias' => 't2',
                        'table_name' => self::TASKTABLE,
                        'join_condition' => "t1.task_uuid = t2.uuid",
                        'select_build_way' => 0,
                    ],
                    "finance_record" => [
                        'alias' => self::alias["default"]["finance_record"],
                        'table_name' => self::Finance_Record,
                        'join_condition' => self::alias["default"]["task_map"].".finance_uuid = ".self::alias["default"]["finance_record"].".uuid",
                        'select_build_way' => 0,
                    ],
                ],
            ],
        ];
    }

    public function getOrderRecord($task_uuid,$executor_uuid)
    {
        $record = $this->getRecord(
            [
                "task_map" => [
                    "*"
                ],
            ],
            [
                "and",
                [
                    "=",
                    self::alias['default']["task_map"] . '.task_uuid',
                    $task_uuid,
                ],
                [
                    "=",
                    self::alias['default']["task_map"] . '.executor_uuid',
                    $executor_uuid,
                ],

            ]
        );

        return $record;
    }

    public function isExistRecord($task_uuid, $executor_uuid)
    {
        $exist = $this->getOrderRecord($task_uuid, $executor_uuid);
        if(empty($exist)){
            return 0;
        }
        return 1;
    }

    public function myInsertRecord($formData)
    {
        return $this->insertRecord($formData);
    }

    public function myGettingOrders($formData)
    {
        $this->setScenario(static::GettingOrdersScenario);
        return $this->batchInsertRecords($formData);
    }

    public function beforeBatchInsertRecord($e)
    {
        $operator = $e->sender;
        $operator->table = static::tableName();
        switch ($this->getScenario()){
            case static::GettingOrdersScenario:
                $operator->column = ["serial_number","task_uuid","executor_uuid","status","create_time","update_time","distributor_uuid"];
                $_rows = [];
                $_time = time();
                if($e->formData["start_executor_time"] > $_time){
                    $status = static::ExecutingStatusWaiting;
                }else if($e->formData["end_executor_time"] > $_time){
                    $status = static::ExecutingStatusExecuting;
                }else{
                    $status = static::ExecutingStatusExpired;
                }
                $serial_number = "1".date("ymd").substr(time(),-5).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
                for($i=0;$i<$e->formData["get_task_num"]; $i++){
                    $_rows[] = [$serial_number,$e->formData["task_uuid"],$e->formData["executor_uuid"],$status,$_time,$_time,$e->formData["distributor_uuid"]];
                    $serial_number += 1;
                }
                $operator->rows = $_rows;

        }
        return $e->isValid;
    }

    public function formDataPreHandler(&$formData, $record = null)
    {
        parent::formDataPreHandler($formData, $record);
        if (empty($record)) {
            $task = new Task;
            $task = $task->getRecord(["task"=>["*"]],["=","t1.uuid",$formData["task_uuid"]]);
            $formData["distributor_uuid"] = $task["create_uuid"];
            $formData["serial_number"] = "1".date("ymd").substr(time(),-5).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);

            if (time() < $task["start_execute_time"]) {
                $formData["status"] = self::ExecutingStatusWaiting;
            }else{
                $formData["status"] = self::ExecutingStatusExecuting;
            }

        } else {
            switch ($this->getScenario()) {
                case self::AbandonScenario:
                    $formData["execute_failed_reason"] = trim($formData["execute_failed_reason"]);
                    $formData["status"] = self::ExecutingStatusGiveUp;
                    break;
                case self::submitTaskScenario:
                    $this->clearEmptyField($formData);
                    $data = [];
                    foreach ($formData as $key => $value) {
                        switch ($key) {
                            case "id_card":
                            case "phone":
                            case "register_account":
                            case "message":
                                $data[$key] = $value;
                                unset($formData[$key]);
                                break;
                        }
                    }
                    $formData["submit_evidence"] = json_encode($data);
                    if(isset($formData["task_screen_shots"])){
                        $data = explode(",",trim($formData["task_screen_shots"],","));
                        foreach ($data as $key => $value){
                            $data[$key] = Yii::$app->params["imageBaseUrl"].ExternalFileHelper::getOtherAbsoluteDirectory().$value;
                        }
                        $formData["submit_screen_shots"] = implode(",",$data);
                    }
                    $formData["status"] = self::ExecutingStatusConfirm;
                    $formData["submit_evidence_time"] = time();
                    unset($formData["task_screen_shots"]);
                    break;
                case self::FileDeleteScenario:
                    $formData["submit_screen_shots"] = explode(",",$record->submit_screen_shots);
                    $path = Yii::$app->params["imageBaseUrl"].ExternalFileHelper::getOtherAbsoluteDirectory().$formData["img_name"];
                    $key = array_search($path,$formData["submit_screen_shots"]);
                    if($key === false){
                        unset($formData["submit_screen_shots"]);
                    }else{
                        unset($formData["submit_screen_shots"][$key]);
                        $formData["submit_screen_shots"] = implode(",",$formData["submit_screen_shots"]);
                    }
                    unset($formData["img_name"]);
                    break;
            }
        }
    }

    public function insertRecordRules($formData = null, $record = null)
    {
//        return [
//            'default' => [
//                [
//                    /**
//                     * 领取任务的时候在distributor_executor_map表里面添加一条数据
//                     */
//                    'class' => DistributorExecutorMap::className(),
//                    'operator' => 'myInsertRecord',
//                    'operator_condition' => true,
//                    'params' => [
//                        'distributor_uuid' => isset($formData['distributor_uuid']) ? $formData['distributor_uuid'] : null,
//                        'executor_uuid' => isset($formData['executor_uuid']) ? $formData['executor_uuid'] : null,
//                        "enable" => self::Enable,
//                    ],
//                ],
//                [
//                    /**
//                     * 领取任务的时候在task表里面更新一条数据
//                     */
//                    "class" => Task::className(),
//                    "operator" => "reduceRemainNum",
//                    "operator_condition" => true,
//                    "params" =>[
//                        'uuid' => isset($formData["task_uuid"]) ? $formData["task_uuid"] : null,
//                    ],
//                ]
//            ],
//        ];
    }

    public function updateRecordRules($formData = null, $record = null)
    {
        return [
            self::AbandonScenario => [
                [
                    /**
                     * 在task表里面更新一条数据
                     */
                    'class' => Task::className(),
                    'operator' => 'twitterAbandonTask',
                    'operator_condition' => true,
                    'params' => [
                        'uuid' => $record->task_uuid,
                    ],
                ],
            ],
        ];
    }

    public function listFilterRules($filter)
    {
        $data = array();
        $data["default"] = [];
        $data["default"]["fields"] = [];

        if(isset($filter["status"]) && $filter["status"] == self::ExecutingStatusWaiting){
            $status = [
                "and",
                [
                    '=',
                    't1.status',
                    $filter['status'],
                ],
                [
                    ">=",
                    "t2.start_execute_time",
                    time(),
                ],
            ];
        }else if(isset($filter["status"]) && $filter["status"] == self::ExecutingStatusExecuting){
            $status = [
                "or",
                [
                    "and",
                    [
                        "<=",
                        "t2.start_execute_time",
                        time(),
                    ],
                    [
                        ">=",
                        "t2.end_execute_time",
                        time(),
                    ],
                    [
                        '=',
                        't1.status',
                        $filter['status'],
                    ],

                ],
                [
                    "and",
                    [
                        "<=",
                        "t2.start_execute_time",
                        time(),
                    ],
                    [
                        ">=",
                        "t2.end_execute_time",
                        time(),
                    ],
                    [
                        "=",
                        "t1.status",
                        self::ExecutingStatusWaiting,
                    ],

                ]
            ];
        }else if(isset($filter["status"]) && $filter["status"] == self::ExecutingStatusDefeated){
            $status = [
                "or",
                [
                    'in',
                    't1.status',
                    [self::ExecutingStatusExpired,self::ExecutingStatusTerminated,self::ExecutingStatusGiveUp,self::ExecutingStatusNotPass],
                ],
                [
                    "and",
                    [
                        "<=",
                        "t2.end_execute_time",
                        time(),
                    ],
                    [
                        "in",
                        "t1.status",
                        [self::ExecutingStatusWaiting,self::ExecutingStatusExecuting],
                    ],

                ]
            ];
        }else if(isset($filter["status"])){
            $status = [
                '=',
                't1.status',
                $filter['status'],
            ];
        }

       if(isset($status)){
           $data["default"]["fields"]["status"] = $status;
       }
        $data["default"]["fields"]["titleOrOrderNum"] = [
            "or",
            [
                'like',
                't2.title',
                isset($filter['titleOrOrderNum'])? trim($filter['titleOrOrderNum']):null,
            ],
            [
                'like',
                't1.serial_number',
                isset($filter['titleOrOrderNum'])? trim($filter['titleOrOrderNum']):null,
            ],
        ];

        return $data;
    }


    public function getRecordLists()
    {
        $tasks =  $this->recordList(
            $this->selector,
            $this->getDefaultCondition()
        );

        $tasks["list"] = $this->dealTasks($tasks["list"]);
        return $tasks;
    }

    public function dealTasks($tasks)
    {
        foreach ($tasks as $key => $task) {
            $tasks[$key] = $this->dealTask($task);
        }
        return $tasks;
    }

    public function dealTask($task)
    {
        if(empty($task)){
            return $task;
        }
        foreach ($task as $key => $value){
            switch ($key){
                case "title":
                    if (mb_strlen($value) > self::BaseTitleLength){
                        $task[$key] = mb_substr($value,0,self::BaseTitleLength)."...";
                    }
                    break;
                case "status":
                    if(time() > $task["end_execute_time"] && ($value == self::ExecutingStatusExecuting || $value == self::ExecutingStatusWaiting)) {
                        $task[$key."_cn"] = self::$config["status"][self::ExecutingStatusExpired];
                        $task[$key] = self::ExecutingStatusExpired;
                        break;
                    }else{
                        $task[$key."_cn"] = self::$config["status"][$value];
                    }

                    if($value == self::ExecutingStatusWaiting && time() > $task["start_execute_time"]) {
                        $task[$key."_cn"] = self::$config["status"][self::ExecutingStatusExecuting];
                        $task[$key] = self::ExecutingStatusExecuting;
                    }

                    break;
                case "start_execute_time":
                    $task[$key."_cn"] = date("Y.m.d H:i",$value);
                    break;
                case "end_execute_time":
                    $task[$key."_cn"] = date("Y.m.d H:i",$value);
                    break;
                case "insure_time":
                    $task[$key."_cn"] = date("Y.m.d H:i",$value);
                    break;
                case "insure_money":
                    if($value == 0){
                        $task[$key] = '--';
                    }
                    if($task["status"] == static::ExecutingStatusNotPass){
                        $task[$key] = "0.00";
                    }
                    break;

            }
        }
        return $task;
    }

    public function getFilterTaskLists($filter)
    {
        $filter_task_lists = $this->listFilter(
            $this->selector,
            $filter,
            $this->getDefaultCondition()
        );

        $filter_task_lists["list"] = $this->dealTasks($filter_task_lists["list"]);
        return $filter_task_lists;
    }

    public function getDefaultCondition()
    {
        return [
            "=",
            self::alias["default"]["task_map"].".executor_uuid",
            Yii::$app->user->identity->uuid,
        ];
    }

    public function abandonTask($formData)
    {
        $this->setScenario(self::AbandonScenario);
        return $this->updateRecord($formData);

    }

    public function submitTaskEvidence($formData)
    {
        $this->setScenario(self::submitTaskScenario);
        return $this->updateRecord($formData);
    }

    public function getSubmitEvidence($id)
    {
        $data = $this->getRecord(
            [
                "task_map" => [
                    "submit_screen_shots",
                    "submit_evidence",
                ],
                "task"=>[
                    "executor_information_config",
                ]
            ],
            [
                "=",
                self::alias["default"]["task_map"].".id",
                $id,
            ]
        );
        if(empty($data)){
            return json_encode(["code"=>1,"message"=>"没有任务","data"=>$data]);
        }
        foreach ($data as $key => $value){
            switch ($key){
                case "submit_screen_shots":
                    if (empty($value)){
                        $data["screen_shots"] = null;
                    }else{
                        $data["screen_shots"] = explode(",",trim($value,","));
                    }
                    unset($data[$key]);
                    break;
                case "submit_evidence":
                    $data[$key] = json_decode($value,true);
                    break;
                case "executor_information_config":
                    if(in_array("screen_shots",explode(",",trim($value,",")))){
                        $data["screen_shots_config"] = true;
                    }else{
                        $data["screen_shots_config"] = false;
                    }
                    unset($data[$key]);
                    break;
            }
        }
        if(!empty($data["screen_shots"])) {
            foreach ($data["screen_shots"] as $key => $value) {
                $data["screen_shots"][$key] = ["img_name" => substr(strrchr($value, "/"), 1), "src" => $value];
            }
        }
        if(!empty($data["screen_shots"])){
            $data["submit_evidence"]["screen_shots"] = $data["screen_shots"];
        }
        if($data["screen_shots_config"]){
            $data["submit_evidence"]["screen_shots_config"] = true;
        }else{
            $data["submit_evidence"]["screen_shots_config"] = false;
        }
        unset($data["screen_shots"],$data["screen_shots_config"]);

        return json_encode(["code"=>0,"data"=>$data]);
    }

    public function getExecutingDetail($id)
    {
        $record = $this->getRecord(
            [
                "task_map" => [
                    "status",
                    "serial_number",
                    "submit_screen_shots",
                    "submit_evidence",
                    "check_remarks",
                    "insure_money",
                    "create_time",
                    "submit_evidence_time",
                    "insure_time",
                    "received_money_time",
                ],
                "task" => [
                    "title",
                    "unit_money",
                    "limit",
                    "getting_status",
                    "remain_num",
                    "distribute_time",
                    "start_getting_time",
                    "end_getting_time",
                    "start_execute_time",
                    "end_execute_time",
                    "content",
                    "check_standard",
                    "remarks",
                    "executor_information_config"
                ],

            ],
            [
                "=",
                self::alias["default"]["task_map"].".id",
                $id,
            ]
        );

        return $this->dealExecutorDetail($record);
    }

    public function dealExecutorDetail($record)
    {
        if(empty($record)){
            return $record;
        }
        foreach ($record as $key => $value){
            switch ($key){
                case "status":
                    if($value == self::ExecutingStatusWaiting || $value == self::ExecutingStatusExecuting){
                        if($record["end_execute_time"] < time()){
                            $record[$key."_cn"] = self::$config["status"][self::ExecutingStatusExpired];
                            $record[$key] = self::ExecutingStatusExpired;
                            break;
                        }else{
                            $record[$key."_cn"] = self::$config["status"][$value];
                            $record[$key] = $value;
                        }

                        if($record["start_execute_time"] < time()){
                            $record[$key."_cn"] = self::$config["status"][self::ExecutingStatusExecuting];
                            $record[$key] = self::ExecutingStatusExecuting;
                        }
                    }else{
                        $record[$key."_cn"] = self::$config["status"][$value];
                        $record[$key] = $value;
                    }
                    break;
                case "create_time":
                case "submit_evidence_time":
                case "received_money_time":
                case "insure_time":
                    if($value == 0){
                        $record[$key."_cn"] = "--";
                    }else{
                        $record[$key."_cn"] = date("Y-m-d H:i:s",$value);
                    }
                    unset($record[$key]);
                    break;
                case "distribute_time":
                    if($value == 0){
                        $record[$key."_cn"] = "--";
                    }else{
                        $record[$key."_cn"] = date("Y-m-d H:i",$value);
                    }
                    unset($record[$key]);
                    break;
                case "start_getting_time":
                case "end_getting_time":
                case "start_execute_time":
                case "end_execute_time":
                    if($value == 0){
                        $record[$key."_cn"] = "--";
                    }else{
                        $record[$key."_cn"] = date("Y-m-d H:i",$value);
                    }
                    unset($record[$key]);
                    break;
                case "executor_information_config":
                    $record[$key] = explode(",",trim($value,","));
                    $record[$key] = array_flip($record[$key]);
                    break;
                case "submit_screen_shots":
                    if(!empty($value)) {
                        $record["screen_shots"] = explode(",", trim($value, ","));
                    }else{
                        $record["screen_shots"] = [];
                    }
                    break;
                case "submit_evidence":
                    $record[$key] = json_decode($value,true);
                    break;
                case "getting_status":
                    $record[$key."_cn"] = self::$config["getting_status"][$value];
                    if($record["end_getting_time"] < time() && $value == self::GettingStatusEnable){
                        $record[$key."_cn"] = self::$config["getting_status"][self::GettingStatusEnd];
                        $record[$key] = self::GettingStatusEnd;
                        break;
                    }
                    if($record["remain_num"] <= 0){
                        $record[$key."_cn"] = self::$config["getting_status"][self::GettingStatusEnd];
                        $record[$key] = self::GettingStatusEnd;
                        break;
                    }
                    if($record["start_getting_time"] < time() && $value == self::GettingStatusWaitingStart){
                        $record[$key."_cn"] = self::$config["getting_status"][self::GettingStatusEnable];
                        $record[$key] = self::GettingStatusEnable;
                        break;
                    }
                    break;
                case "insure_money":
                    if($value == 0){
                        $record[$key] = '--';
                    }
                    if($record["status"] == static::ExecutingStatusNotPass){
                        $record[$key] = '0.00';
                    }
                    break;
                case "unit_money":
                    if($value == 0){
                        $record[$key] = '--';
                    }
                    break;

            }
        }

        return $record;

    }

    public function getPaymentOrder($finance_uuid)
    {
        $task = $this->recordList(
            $this->selector,
            [
                "=",
                self::alias["default"]["task_map"].".finance_uuid",
                $finance_uuid,
            ],
            false,
            null,
            false
        );
        $task = $this->dealTasks($task);
        return $task;
    }

    public function getMyReceivedVoucher($id)
    {
        $data = $this->getRecord(
            [
                "task_map" => [
                    "received_evidence",
                    "received_remarks",
                ],
                "finance_record" => [
                    "number_of_order",
                    "money",
                ],
            ],
            [
                "and",
                [
                    "=",
                    self::alias["default"]["task_map"].".id",
                    $id,
                ],
                [
                    "=",
                    self::alias["default"]["task_map"].".status",
                    self::ExecutingStatusReceived,
                ],
            ]
        );
        $data["received_evidence"] = explode(",",$data["received_evidence"]);
        return $data;
    }
}