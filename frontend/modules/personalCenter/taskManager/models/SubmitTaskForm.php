<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-4-11
 * Time: 下午3:05
 */

namespace frontend\modules\personalCenter\taskManager\models;

use frontend\modules\taskHall\models\ExecutorTaskMap;
use yii\base\Model;


class SubmitTaskForm extends Model
{
    public $id;
    public $id_card;
    public $phone;
    public $register_account;
    public $message;
    public $task_screen_shots;
    public $task_uuid;


    public function rules()
    {
        $array = [];
        $array[] = ["id","required","message"=>"没有任务"];
        $array[] = ["id","number","message"=>"任务号码不正确"];
        $array[] = ["id_card","validateIdCard"];
        $array[] = ["phone","match","pattern"=>"/^1[34578]\d{9}$/","message"=>"手机号码格式错误"];
        $array[] = ["task_uuid","required","message"=>"没有任务"];
        $array[] = ["task_uuid","validateTaskUuid"];
        return $array;
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => [
                'id',
                'id_card',
                'phone',
                'message',
                'task_screen_shots',
                "register_account",
                "task_uuid",
            ],
        ];
    }


    public function validateIdCard($attribute, $params)
    {
        if ($this->hasErrors()) {
            return false;
        }
        $id = strtoupper($this->id_card);
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
        $arr_split = array();
        if (!preg_match($regx, $id)) {
            $this->addError($attribute, "身份证号码格式错误");
        }

        if (15 == strlen($id)) {
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
            @preg_match($regx, $id, $arr_split);
            //检查生日日期是否正确
            $dtm_birth = "19" . $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
            if (!strtotime($dtm_birth)) {
                $this->addError($attribute, "身份证号码格式错误");
            }
        } else {
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
            @preg_match($regx, $id, $arr_split);
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
            if (!strtotime($dtm_birth)) {
                $this->addError($attribute, "身份证号码格式错误");
            } else {
                //检验18位身份证的校验码是否正确。
                //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
                $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $sign = 0;
                for ($i = 0; $i < 17; $i++) {
                    $b = (int)$id{$i};
                    $w = $arr_int[$i];
                    $sign += $b * $w;
                }

                $n = $sign % 11;
                $val_num = $arr_ch[$n];

                if ($val_num != substr($id, 17, 1)) {
                    $this->addError($attribute, "身份证号码格式错误");
                }
            }
        }
    }

    public function validateTaskUuid($attribute)
    {
        if(!$this->hasErrors()){
            $task_map = new ExecutorTaskMap;
            $task_map_detail = $task_map->recordList(
                [
                    "task" => [
                        "unique_config",
                    ],
                    "task_map" => [
                        "submit_evidence",
                    ],
                ],
                [
                    "and",
                    [
                        "=",
                        ExecutorTaskMap::alias["default"]["task"].".uuid",
                        $this->task_uuid,
                    ],
                    [
                        "in",
                        ExecutorTaskMap::alias["default"]["task_map"].".status",
                        [ExecutorTaskMap::ExecutingStatusExecuting,ExecutorTaskMap::ExecutingStatusConfirm,ExecutorTaskMap::ExecutingStatusReceived,ExecutorTaskMap::ExecutingStatusReceivingWaiting],
                    ],
                    [
                        "!=",
                        ExecutorTaskMap::alias["default"]["task_map"].".id",
                        $this->id,
                    ],
                ],
                false,
                null,
                false
            );
            $unique_config = [];
            $submit_evidence = [];
            foreach ($task_map_detail as $item){
                foreach ($item as $key => $value){
                    switch ($key){
                        case "unique_config":
                            $unique_config = json_decode($value,true);
                            break;
                        case "submit_evidence":
                            if(empty($value)){
                                break;
                            }
                            $data = json_decode($value,true);
                            foreach ($data as $k => $v){
                                if($k != 'message'){
                                    $submit_evidence[$k][] = $v;
                                }
                            }
                            break;
                    }
                }
            }

            if(isset($unique_config["id_card"]) && $unique_config["id_card"] == 'unique' && isset($submit_evidence["id_card"])){
                if(!empty($this->id_card)){
                    if(in_array($this->id_card,$submit_evidence["id_card"])){
                        $this->addError("id_card","身份证号已经提交过了");
                    }
                }else{
                    $this->addError("id_card","请填写身份证号");
                }
            }

            if(isset($unique_config["phone"]) && $unique_config["phone"] == 'unique' && isset($submit_evidence["phone"])){
                if(!empty($this->phone)){
                    if(in_array($this->phone,$submit_evidence["phone"])){
                        $this->addError("phone","手机号已经提交过了");
                    }
                }else{
                    $this->addError("phone","请填写手机号");
                }
            }

            if(isset($unique_config["register_account"]) && $unique_config["register_account"] == 'unique' && isset($submit_evidence["register_account"])){
                if(!empty($this->register_account)){
                    if(in_array($this->register_account,$submit_evidence["register_account"])){
                        $this->addError("register_account","注册账号已经提交过了");
                    }
                }else{
                    $this->addError("register_account","请填写注册账号");
                }
            }
        }
    }

    public function dealMyErrors($errors)
    {
        if (empty($errors)){
            return '';
        }
        $message = '';
        foreach ($errors as $key => $value){
           switch ($key){
               default:
                   if(empty($message)){
                       $message = $value[0];
                   }
                   break;

           }
        }
        return $message;

    }

}