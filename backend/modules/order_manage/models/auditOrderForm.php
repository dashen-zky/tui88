<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-4-11
 * Time: 上午11:02
 */

namespace backend\modules\order_manage\models;

use yii\base\Model;


class auditOrderForm extends Model
{
    public $id;
    public $method;
    public $unit_money;
    public $insure_money;
    public $check_remarks;

    public function rules()
    {
        return [
            ["id","required","message"=>"订单存在"],
            ["id","number","message"=>"订单id必须为数字"],

            ["method","validateMethod","message"=>"方法不正确"],

            ["unit_money","number","message"=>"任务金额必须为数字"],
            ["insure_money","number","message"=>"确认金额必须位为数字"],
            ["insure_money","validateInsureMoney"],
        ];
    }

    public function validateMethod($attribute,$params)
    {
        if(!$this->hasErrors()){
            if($this->method != 'pass' && $this->method != 'not_pass'){
                $this->addError($attribute,"方法不正确");
            }
        }
    }

    public function validateInsureMoney($attribute)
    {
        if(!$this->hasErrors()){
            if($this->insure_money > $this->unit_money){
                $this->addError($attribute,"确认金额不能大于任务金额");
            }
        }
    }

}