<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-5-17
 * Time: 上午10:07
 */

namespace backend\modules\order_manage\models;

use yii\base\Model;


class OrderNotPassToPassForm extends Model
{

    public $id;
    public $phone;
    public $id_card;
    public $register_account;
    public $task_screen_shots;
    public $insure_money;
    public $unit_money;
    public $message;

    public function rules()
    {
        return [
            ["id","required","message"=>"订单错误"],
            ["phone","match","pattern"=>"/^1[34578]\d{9}$/","message"=>"手机号码错误"],
            ["id_card","validateIdCard"],
            ["insure_money","CheckInsureMoney"],
        ];
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
                "insure_money",
                "unit_money",
            ],
        ];
    }

    public function validateIdCard($attribute)
    {
        if ($this->hasErrors()) {
            return false;
        }
        $id = strtoupper($this->id_card);
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
        $arr_split = array();
        if (!preg_match($regx, $id)) {
            $this->addError($attribute, "身份证号码不正确");
        }

        if (15 == strlen($id)) {
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
            @preg_match($regx, $id, $arr_split);
            //检查生日日期是否正确
            $dtm_birth = "19" . $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
            if (!strtotime($dtm_birth)) {
                $this->addError($attribute, "身份证号码不正确");
            }
        } else {
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
            @preg_match($regx, $id, $arr_split);
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
            if (!strtotime($dtm_birth)) {
                $this->addError($attribute, "身份证号码不正确");
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
                    $this->addError($attribute, "身份证号码不正确");
                }
            }
        }
    }

    public function CheckInsureMoney($attribute)
    {
        if (!$this->hasErrors()){
            if($this->insure_money > $this->unit_money){
                $this->addError($attribute,"确认金额大于任务金额");
            }
        }
    }

    public function dealMyError($errors)
    {
        $message = '';
        foreach ($errors as $key => $error){
            $message = $error[0];
            break;
        }
        return $message;
    }

}