<?php
/**
 * Created by PhpStorm
 * USER: dashe
 * Date: 2017/3/13
 */

namespace backend\modules\task_manage\models;

use yii\base\Model;


class TaskPublishForm extends Model
{
    public $start_getting_time;
    public $end_getting_time;
    public $start_execute_time;
    public $end_execute_time;
    public $limit;
    public $unit_money;
    public $title;
    public $content;
    public $check_standard;
    public $remarks;
    public $executor_information_config;
    public $unique_config;
    public $distribute_status;

    public function rules()
    {
        return [
            [["start_getting_time","end_getting_time"],"required",'message'=>'请输入领取时间'],
            ["end_getting_time","compare","compareAttribute"=>"start_getting_time","operator"=>">=","message"=>"领取时间格式错误"],
            ["end_getting_time","compare","compareValue"=>time(),"operator"=>">=",'message'=>"领取结束时间无效"],

            [["start_execute_time","end_execute_time"],"required","message"=>"请输入执行时间"],
            ["start_execute_time","compare","compareAttribute"=>"start_getting_time","operator"=>">=","message"=>"任务执行时间必须大于任务领取时间"],

            ["end_execute_time","compare","compareAttribute"=>"start_execute_time","operator"=>">=","message"=>"任务执行时间格式错误"],
            ["end_execute_time","compare","compareAttribute"=>"end_getting_time","operator"=>">=","message"=>"任务执行时间必须大于任务领取时间"],

            ["limit","required","message"=>"请输入任务数量"],
            ["limit","match","pattern"=>"/\d+/","message"=>"任务数量不正确"],
            ["limit","compare","compareValue"=>1,"operator"=>">=","message"=>"任务数量不正确"],

            ["unit_money","required","message"=>"请输入任务金额"],
            ["unit_money","number","message"=>"任务金额不正确"],
            ["unit_money","compare","compareValue"=>0,"operator"=>">","message"=>"任务金额不正确"],

            ["title","required","message"=>"请输入任务名称"],
            ["title","string","length"=>[1,50],"tooLong"=>"任务名称过长"],

            ["content","required","message"=>"请输入任务内容"],
            ["check_standard","required","message"=>"请输入验收标准"],

            ["executor_information_config","required","message"=>"请选择提交内容"],
            ["unique_config","validateUniqueConfig","message"=>"任务提交内容唯一不匹配"],

            ["distribute_status","required","message"=>"发布状态不能为空"],
            ["distribute_status","match","pattern"=>"/[01]/","message"=>"发布状态不正确"],
        ];
    }

    /**
     * 处理错误信息
     * @param $error
     *
     */
    public function dealErrorMessage($error)
    {
        $data = array();
        if(empty($error)){
            $data["code"] = 0;
            $data["message"] = "成功";
        }else{
            $data["code"]= 1;
            foreach ($error as $key => $value){
                $data["message"][$key] = $value[0];
                break;
            }
        }
        return json_encode($data);
    }


    /**
     * 处理提交过来的表单
     * @param $formData
     * @return mixed
     */
    public function dealFormData($formData)
    {
        foreach ($formData["TaskPublishForm"] as $key => $value) {
            switch ($key){
                case 'start_getting_time':
                case 'end_getting_time':
                case 'start_execute_time':
                case 'end_execute_time':
                    if(!empty($value)){
                        $formData["TaskPublishForm"][$key] = strtotime($value);
                    }
                    break;
            }
        }
        return $formData;
    }

    public function validateUniqueConfig($attribute)
    {
        if(!$this->hasErrors()){
            foreach ($this->unique_config as $key => $value){
                if(!in_array($value,$this->executor_information_config)){
                    switch ($value){
                        case "id_card":
                            $this->addError($attribute,"身份证唯一不匹配");
                            break;
                        case "phone":
                            $this->addError($attribute,"手机号唯一不匹配");
                            break;
                        case "register_account":
                            $this->addError($attribute,"注册账号唯一不匹配");
                            break;
                        case "screen_shots":
                            $this->addError($attribute,"截图唯一不匹配");
                            break;
                    }
                    break;
                }
            }
        }
    }


}