<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-4-6
 * Time: 上午10:09
 */

namespace backend\modules\order_manage\models;

use backend\modules\task_manage\models\Task;
use Yii;
use yii\db\Expression;
use common\models\BaseRecord;
use common\helpers\ExternalFileHelper;
use backend\modules\settle_manage\models\Settle;
use backend\modules\settle_manage\models\SettleTask;
use frontend\modules\taskHall\models\ExecutorTaskMap;
use backend\modules\user_manage\models\DistributorExecutorMap;

class Order extends BaseRecord
{
    public $pageSize = 20;

    const ExecutingStatusWaiting = 1; //待执行
    const ExecutingStatusExecuting = 2; // 执行中
    const ExecutingStatusConfirm = 3; // 待确认
    const ExecutingStatusReceivingWaiting = 4; // 待收款
    const ExecutingStatusReceived = 5; // 已收款

    const ExecutingStatusExpired = 129; // 已过期
    const ExecutingStatusGiveUp = 130; // 已放弃
    const ExecutingStatusNotPass = 131; // 未通过
    const ExecutingStatusTerminated = 132; // 已终止

    const SettleStatusNotSettle = SettleTask::SettleStatusNotSettle;
    const SettleStatusSettled = SettleTask::SettleStatusSettled;

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
    ];

    public static $alias = [
        "default" => [
            "task_map" => "t1",
            "task" => "t2",
            "frontend_user" => "t3",
            "user_information"=>"t4",
            "distributor_executor_map" => "t5",
        ],
    ];
    private $selector = [
        "task_map" => [
            "id",
            "serial_number",
            "status",
            "create_time",
            "insure_money",
        ],
        "task" => [
            "uuid",
            "title",
            "unit_money",
            "start_execute_time",
            "end_execute_time",
        ],
        "frontend_user" => [
            "phone",
        ],
    ];


    public static function tableName()
    {
        return self::EXECUTOR_TASK_MAP;
    }

    const Pass = "pass";
    const NotPass = "not_pass";
    const NotPassToPass = "not_pass_to_pass";
    const SettledTaskSuccess = "settled_task_success";
    const PaidTaskSuccess = "paid_task_success";
    const TerminateTask = "terminate_task";
    const BatchTaskMap = "batch_task_map";

    public function scenarios()
    {
        return array_merge(parent::scenarios(),[
            self::Pass => [self::Pass],
            self::NotPass => [self::NotPass],
            self::NotPassToPass => [self::NotPassToPass],
            self::SettledTaskSuccess => [self::SettledTaskSuccess],
            self::PaidTaskSuccess => [self::PaidTaskSuccess],
            self::TerminateTask => [self::TerminateTask],
            self::BatchTaskMap => [self::BatchTaskMap],
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
                    "frontend_user" => [
                        'alias' => 't3',
                        'table_name' => self::USERACCOUNT,
                        'join_condition' => "t1.executor_uuid = t3.uuid",
                        'select_build_way' => 0,
                    ],
                    "user_information" => [
                        'alias' => 't4',
                        'table_name' => self::UserInformation,
                        'join_condition' => "t1.executor_uuid = t4.user_uuid",
                        'select_build_way' => 0,
                    ],
                    "distributor_executor_map" => [
                        'alias' => 't5',
                        'table_name' => self::DistributorExecutorMap,
                        'join_condition' => "t1.executor_uuid = t5.executor_uuid and t1.distributor_uuid = t5.distributor_uuid",
                        'select_build_way' => 0,
                    ],
                ],
            ],
            self::PaidTaskSuccess => [
                'rules' => [
                    'task_map' => [
                        'alias' => self::$alias["default"]["task_map"],
                        'table_name' => self::EXECUTOR_TASK_MAP,
                        'join_condition' => false,
                        'select_build_way' => 0,
                    ],
                    'task' => [
                        'alias' => self::$alias["default"]["task"],
                        'table_name' => self::TASKTABLE,
                        'join_condition' => self::$alias["default"]["task_map"].".task_uuid = ".self::$alias["default"]["task"].".uuid",
                        'select_build_way' => 0,
                    ],
                ],
            ],
        ];
    }

    public function formDataPreHandler(&$formData, $record = null)
    {
        parent::formDataPreHandler($formData, $record);
        if(!empty($record)){
            switch ($this->getScenario()){
                case self::Pass:
                    $formData["status"] = self::ExecutingStatusReceivingWaiting;
                    $formData["insure_time"] = time();
                    $formData["insure_money_to_dis"] = $formData["insure_money"];
                    $formData["insure_number_to_dis"] = 1;
                    break;
                case self::NotPass:
                    $formData["insure_money"] = 0.00;
                    $formData["status"] = self::ExecutingStatusNotPass;
                    $formData["insure_time"] = time();
                    break;
                case self::NotPassToPass:
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

                    $formData["status"] = self::ExecutingStatusReceivingWaiting;
                    if(isset($formData["task_screen_shots"])){
                        $data = explode(",",trim($formData["task_screen_shots"],","));
                        foreach ($data as $key => $value){
                            $data[$key] = Yii::$app->params["adminImageBaseUrl"].ExternalFileHelper::getOtherAbsoluteDirectory().$value;
                        }
                        $formData["submit_screen_shots"] = implode(",",$data);
                    }
                    $formData["insure_time"] = time();
                    $formData["submit_evidence_time"] = time();
                    $formData["insure_money_to_dis"] = $formData["insure_money"];
                    $formData["insure_number_to_dis"] = 1;
                    unset($data,$formData["task_screen_shots"]);
            }

        }
    }

    public function updateRecordRules($formData = null, $record = null)
    {
        return [
            self::Pass => [
                [
                    /**
                     * 通过审核 的时候在 distributor_executor_map 表 里面更新一条数据
                     */
                    'class' => Settle::className(),
                    'operator' => 'CheckOrderSuccessToDisUpdate',
                    'operator_condition' => true,
                    'params' => [
                        'id' => isset($formData["dis_id"]) ? $formData["dis_id"] : "",
                        "money" => isset($formData["insure_money_to_dis"]) ? $formData["insure_money_to_dis"] : "",
                        "number_of_order" => isset($formData["insure_number_to_dis"]) ? $formData["insure_number_to_dis"] : '',
                    ],
                ],
            ],

            self::NotPassToPass => [
                [
                    /**
                     * 通过审核 的时候在 distributor_executor_map 表 里面更新一条数据
                     */
                    'class' => Settle::className(),
                    'operator' => 'CheckOrderSuccessToDisUpdate',
                    'operator_condition' => true,
                    'params' => [
                        'id' => isset($formData["dis_id"]) ? $formData["dis_id"] : "",
                        "money" => isset($formData["insure_money_to_dis"]) ? $formData["insure_money_to_dis"] : "",
                        "number_of_order" => isset($formData["insure_number_to_dis"]) ? $formData["insure_number_to_dis"] : '',
                    ],
                ],
            ],
        ];
    }

    public function listFilterRules($filter)
    {
        $rules = [];
        $rules["default"] = [];
        $rules["default"]["fields"] = [];
        if(isset($filter["status"]) && (in_array(self::ExecutingStatusWaiting,$filter["status"]) || in_array(self::ExecutingStatusExecuting,$filter["status"])) ){
            if(in_array(self::ExecutingStatusWaiting,$filter["status"]) && in_array(self::ExecutingStatusExecuting,$filter["status"])){
                unset($filter["status"][array_search(self::ExecutingStatusWaiting,$filter["status"])]);
                unset($filter["status"][array_search(self::ExecutingStatusExecuting,$filter["status"])]);
                $rules["default"]["fields"]["status"] = [
                    "or",
                    [
                        'in',
                        "t1.status",
                        $filter["status"],
                    ],
                    [
                        "or",
                        [
                            "and",
                            [
                                "=",
                                "t1.status",
                                self::ExecutingStatusWaiting,
                            ],
                            [
                                ">",
                                "t2.start_execute_time",
                                time(),
                            ],
                        ],
                        [
                            "and",
                            [
                                "=",
                                "t1.status",
                                self::ExecutingStatusExecuting,
                            ],
                            [
                                ">",
                                "t2.end_execute_time",
                                time(),
                            ],
                        ]
                    ],
                ];
            }else if(in_array(self::ExecutingStatusExecuting,$filter["status"])){
                unset($filter["status"][array_search(self::ExecutingStatusExecuting,$filter["status"])]);
                $rules["default"]["fields"]["status"] = [
                    "or",
                    [
                        'in',
                        "t1.status",
                        $filter["status"],
                    ],
                    [
                        "and",
                        [
                            "=",
                            "t1.status",
                            self::ExecutingStatusExecuting,
                        ],
                        [
                            ">",
                            "t2.end_execute_time",
                            time(),
                        ],

                    ],

                ];
            }else if(in_array(self::ExecutingStatusWaiting,$filter["status"])){
                unset($filter["status"][array_search(self::ExecutingStatusWaiting,$filter["status"])]);
                $rules["default"]["fields"]["status"] = [
                    "or",
                    [
                        'in',
                        "t1.status",
                        $filter["status"],
                    ],
                    [
                        "and",
                        [
                            "=",
                            "t1.status",
                            self::ExecutingStatusWaiting,
                        ],
                        [
                            ">",
                            "t2.start_execute_time",
                            time(),
                        ],
                    ],

                ];
            }

        }else if(isset($filter["status"])){
            $rules["default"]["fields"]["status"] = [
                'in',
                "t1.status",
                $filter["status"],
            ];
        }

        if (isset($filter["titleOrSerialNumber"])){
            $rules["default"]["fields"]["titleOrSerialNumber"] = [
                "or",
                [
                    'like',
                    't2.title',
                    trim($filter['titleOrSerialNumber']),
                ],
                [
                    'like',
                    't1.serial_number',
                    trim($filter['titleOrSerialNumber']),
                ],
            ];
        }

        if(isset($filter["start_create_time"])){
            $rules["default"]["fields"]["start_create_time"] = [
                ">=",
                "t1.create_time",
                strtotime($filter["start_create_time"]),
            ];
        }

        if(isset($filter["end_create_time"])){
            $rules["default"]["fields"]["end_create_time"] = [
                "<=",
                "t1.create_time",
                strtotime($filter["end_create_time"]),
            ];
        }

        if(isset($filter["task_uuid"])){
            $rules["default"]["fields"]["task_uuid"] = [
                "=",
                "t1.task_uuid",
                $filter["task_uuid"],
            ];
        }

        if(isset($filter["start_insure_time"])){
            $rules["default"]["fields"]["start_insure_time"] = [
                ">",
                "t1.insure_time",
                strtotime(trim($filter["start_insure_time"])),
            ];
        }

        if(isset($filter["end_insure_time"])){
            $rules["default"]["fields"]["end_insure_time"] = [
                "<",
                "t1.insure_time",
                strtotime(trim($filter["end_insure_time"])),
            ];
        }

        return $rules;
    }

    public function getAllOrders($task_uuid)
    {
        $orders = $this->recordList($this->selector,$this->getDefaultCondition($task_uuid));
        $orders["list"] = $this->dealOrderLists($orders["list"]);
        return $orders;
    }

    public function dealOrderLists($order_lists)
    {
        foreach ($order_lists as $key => $value){
            $order_lists[$key] = $this->dealOrder($value);
        }
        return $order_lists;
    }

    public function dealOrder($order)
    {
        if(empty($order)){
            return $order;
        }
        foreach ($order as $key => $value){
            switch ($key){
                case "title":
                    if(mb_strlen($value) > self::BaseTitleLength){
                        $order[$key] = mb_substr($value,0,self::BaseTitleLength)."...";
                    }
                    break;
                case "status":
                    $order[$key."_cn"] = self::$config["status"][$value];
                    break;

                case "start_execute_time":
                    $order[$key."_cn"] = date("Y.m.d",$value);
                    if($order["status"] == self::ExecutingStatusWaiting && time() > $value) {
                        $order["status_cn"] = self::$config["status"][self::ExecutingStatusExecuting];
                    }
                    unset($order[$key]);
                    break;
                case "end_execute_time":
                    $order[$key."_cn"] = date("Y.m.d",$value);
                    if(time() > $value && ($order["status"] == self::ExecutingStatusExecuting || $order["status"] == self::ExecutingStatusWaiting)) {
                        $order["status_cn"] = self::$config["status"][self::ExecutingStatusExpired];
                    }
                    unset($order[$key]);
                    break;
                case "create_time":
                    $order[$key."_cn"] = date("Y.m.d H:i",$value);
                    break;
                case 'insure_time':
                    $order[$key."_cn"] = date("Y.m.d H:i",$value);
                    break;
                case "insure_money":
                    if(0 == $value){
                        $order[$key] = '--';
                    }
                    if($order["status"] == static::ExecutingStatusNotPass){
                        $order[$key] = "0.00";
                    }
                    break;
            }
        }
        return $order;
    }

    private function getDefaultCondition($task_uuid = null)
    {
        if(empty($task_uuid)){
            $condition = [
                "and",
                [
                    "=",
                    self::$alias["default"]["task_map"].".distributor_uuid",
                    Yii::$app->user->identity->uuid,
                ],
                [
                    "=",
                    self::$alias["default"]["task_map"].".status",
                    self::ExecutingStatusConfirm,
                ],
            ];
        }else{
            $status = array_keys(self::$config["status"]);
            unset($status[array_search(self::ExecutingStatusWaiting,$status)]);
            unset($status[array_search(self::ExecutingStatusExecuting,$status)]);

            $condition = [
                "and",
                [
                    "=",
                    self::$alias["default"]["task_map"].".distributor_uuid",
                    Yii::$app->user->identity->uuid,
                ],
                [
                    "=",
                    self::$alias["default"]["task_map"].".task_uuid",
                    $task_uuid,
                ],
                [
                    "or",
                    [
                        "in",
                        self::$alias["default"]["task_map"].".status",
                        $status,
                    ],
                    [
                        "and",
                        [
                            "=",
                            "t1.status",
                            self::ExecutingStatusWaiting,
                        ],
                        [
                            ">=",
                            "t2.start_execute_time",
                            time(),
                        ],
                    ],
                    [
                        "and",
                        [
                            "=",
                            "t1.status",
                            self::ExecutingStatusExecuting,
                        ],
                        [
                            ">=",
                            "t2.end_execute_time",
                            time(),
                        ],
                    ],
                ],
            ];
        }
        return $condition;
    }

    public function getFilterOrderLists($filter)
    {
        if(empty($filter["status"])){
            $filter["status"] = array_keys(self::$config["status"]);
        }

        $filter_order_lists = $this->listFilter(
            $this->selector,
            $filter,
            [
                "=",
                self::$alias["default"]["task_map"].".distributor_uuid",
                Yii::$app->user->identity->uuid,
            ]
        );

        $filter_order_lists["list"] = $this->dealOrderLists($filter_order_lists["list"]);
        return $filter_order_lists;
    }

    public function getMyOrderCheck($id)
    {
        $order = $this->getRecord(
            [
                "task_map" => [
                    'id',
                    "status",
                    "submit_screen_shots",
                    "submit_evidence",
                    "insure_money",
                ],
                "task" => [
                    "unit_money",
                ],
                "distributor_executor_map"=>[
                    "id as dis_id"
                ],
            ],
            [
                '=',
                self::$alias["default"]["task_map"].".id",
                $id,
            ]
        );
        $order = (new ExecutorTaskMap)->dealExecutorDetail($order);
        $order = array_merge(["code"=>0],$order);
        return $order;
    }

    public function getMyOrderDetail($id)
    {
        $order = (new ExecutorTaskMap)->getExecutingDetail($id);
        return $order;
    }

    public function checkOrder($formData)
    {
        return $this->updateRecord($formData);
    }

    public function getSettleOrderLists($executor)
    {
        $order = $this->recordList($this->getSettleOrderSelector(),$this->getSettleOrderCondition($executor));
        $order["list"] = $this->dealOrderLists($order["list"]);
        return $order;
    }

    public function getSettleOrderSelector()
    {
        return [
            "task_map" => [
                "id",
                "status",
                "serial_number",
                "insure_time",
                "insure_money",
            ],
            "task" => [
                "uuid",
                "title",
                "unit_money"
            ],
        ];
    }
    public function getSettleOrderCondition($executor)
    {
        $condition = [
            "and",
            [
                "=",
                self::$alias["default"]["task_map"].".distributor_uuid",
                Yii::$app->user->identity->uuid,
            ],
            [
                "=",
                self::$alias["default"]["task_map"].".executor_uuid",
                $executor,
            ],
            [
                "=",
                self::$alias["default"]["task_map"].".status",
                self::ExecutingStatusReceivingWaiting,
            ],
            [
                "=",
                self::$alias["default"]["task_map"].".settle_status",
                self::SettleStatusNotSettle,
            ]
        ];

        return $condition;
    }

    public function getSettleOrderListFilter($filter)
    {
        $order_lists = $this->listFilter($this->getSettleOrderSelector(),$filter,$this->getSettleOrderCondition($filter["executor_uuid"]));
        $order_lists["list"] = $this->dealOrderLists($order_lists["list"]);

        return $order_lists;
    }

    public function mySettleStatusUpDate($formData)
    {
        $this->setScenario(self::SettledTaskSuccess);
        return $this->batchUpdateRecords($formData);
    }

    public function beforeBatchUpdateRecord($e)
    {
        $operator = $e->sender;
        $operator->table = static::tableName();

        switch ($this->getScenario()){
            case self::SettledTaskSuccess:
                $operator->column = [
                    "settle_status" => $e->formData["settled_status"],
                    "finance_uuid" => $e->formData["finance_uuid"],
                ];
                $operator->condition = [
                    "id" => $e->formData["id"],
                ];
                break;
            case self::PaidTaskSuccess:
                $operator->column = [
                    "received_money" => new Expression(" insure_money"),
                    "received_money_time" => time(),
                    "status" => self::ExecutingStatusReceived,
                    "received_evidence" => $e->formData["received_evidence"],
                    "received_remarks" => $e->formData["received_remarks"],
                ];
                $operator->condition = [
                    "finance_uuid" => $e->formData["finance_uuid"],
                ];
                break;
            case self::TerminateTask:
                $operator->column = [
                    "status" => self::ExecutingStatusTerminated,
                ];
                $operator->condition = [
                    "task_uuid" => $e->formData["task_uuid"],
                    "status" => [self::ExecutingStatusExecuting,self::ExecutingStatusWaiting],
                ];
                break;

        }
        return $e->isValid;

    }

    public function getFinanceOrders($finance_uuid)
    {
        $fin_order_lists = $this->recordList(
            [
               "task_map" => [
                   "id as task_map_id",
                   "status",
                   "serial_number",
                   "insure_time",
                   "insure_money",
               ],
                "task" => [
                    "uuid as task_uuid",
                    "title",
                    "unit_money"
                ],
            ],
            [
                "and",
                [
                    "=",
                    self::$alias["default"]["task_map"].'.finance_uuid',
                    $finance_uuid,
                ],
                [
                    "or",
                    [
                        "=",
                        self::$alias["default"]["task_map"].".status",
                        self::ExecutingStatusReceivingWaiting,
                    ],
                    [
                        "=",
                        self::$alias["default"]["task_map"].".status",
                        self::ExecutingStatusReceived
                    ],
                ],
            ]
        );

        $fin_order_lists["list"] = $this->dealOrderLists($fin_order_lists["list"]);
        return $fin_order_lists;
    }

    public function myReceivedMoneyUpdate($formData)
    {
        $this->setScenario(self::PaidTaskSuccess);
        return $this->batchUpdateRecords($formData);

    }

    public function orderTerminate($formData)
    {
        $this->setScenario(self::TerminateTask);
        return $this->batchUpdateRecords($formData);
    }

    public function afterBatchUpdateRecord($e)
    {
        switch ($this->getScenario()) {
            case self::PaidTaskSuccess:
                $data = $this->recordList(
                    [
                        "task_map"=>["task_uuid"]
                    ],
                    [
                        "=",
                        self::$alias["default"]["task_map"].".finance_uuid",
                        $e->formData["finance_uuid"],
                    ],
                    false,
                    null,
                    false
                );
                foreach ($data as $key => $value){
                    $data[$key] = $value["task_uuid"];
                }
                $task_maps = $this->recordList(
                    [
                        "task_map" => [
                            "task_uuid",
                        ],
                        "task" => [
                            "end_getting_time",
                            "remain_num",
                            "number_of_gets",
                        ],
                    ],
                    [
                        "and",
                        [
                            "in",
                            self::$alias["default"]["task_map"] . ".task_uuid",
                            $data,
                        ],
                        [
                            "=",
                            self::$alias["default"]["task_map"] . ".status",
                            self::ExecutingStatusReceived,
                        ],

                    ],
                    false,
                    null,
                    false
                );
                foreach ($task_maps as $key => $task) {
                    $task_maps[$task["task_uuid"]] = isset($task_maps[$task["task_uuid"]]) ? $task_maps[$task["task_uuid"]] : $task;
                    if (isset($task_maps[$task["task_uuid"]]["received_num"])) {
                        $task_maps[$task["task_uuid"]]["received_num"] += 1;
                    } else {
                        $task_maps[$task["task_uuid"]]["received_num"] = 1;
                    }
                    unset($task_maps[$key]);
                    unset($task_maps[$task["task_uuid"]]["task_uuid"]);
                }
                foreach ($task_maps as $key => $task) {
                    if ($task["remain_num"] == 0 && $task["number_of_gets"] == $task["received_num"]) {
                        $update_task = new Task;
                        $update_task->setScenario(Task::ScenarioFinishTask);
                        return $update_task->updateRecord(["uuid" => $key]);
                    }
                    if ($task["remain_num"] != 0 && $task["end_getting_time"] < time() && $task["received_num"] == $task["number_of_gets"]) {
                        $update_task = new Task;
                        $update_task->setScenario(Task::ScenarioFinishTask);
                        return $update_task->updateRecord(["uuid" => $key]);
                    }
                }
                break;
        }
        return true;
    }

}