<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 3/21/17
 * Time: 2:32 PM
 */

namespace frontend\modules\taskHall\models;

use yii\base\Model;


class TaskListFilterForm extends Model
{
    public $composite;
    public $distribute_time;
    public $remain_num;
    public $unit_money;
    public $getting_status;
    public $min_money;
    public $max_money;
    public $task_title;

    public function rules()
    {
        return [
            [["composite","distribute_time","remain_num","unit_money"],"validateExist","message"=>"筛选条件错误"],
            ["getting_status","match","pattern"=>"/^[12]$/","message"=>"领取状态错误"],
            ["task_title","filter","filter"=>"trim"],
        ];
    }

    public function validateExist($attribute, $params)
    {
        if(!$this->hasErrors()){
            if($this->composite=== null && $this->distribute_time && $this->remain_num && $this->unit_money){
                $this->addError($attribute, "筛选条件错误");
            }
        }
    }
}