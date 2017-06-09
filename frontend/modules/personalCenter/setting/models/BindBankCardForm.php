<?php
/**
 * Created by PhpStorm
 * USER: dashe
 * Date: 2017/3/20
 */

namespace frontend\modules\personalCenter\setting\models;

use yii\base\Model;


class BindBankCardForm extends Model
{
    public $bank_card_num;
    public $bank_name_opening;
    public $bank_card_name;

    public function rules()
    {
        return [
            ["bank_card_num","required","message"=>"请填写银行卡账号"],
            ["bank_card_num","validateBankCardNum","message"=>"银行卡号错误"],

            ["bank_name_opening","required","message"=>"请填写所属银行"],
            ["bank_card_name","required","message"=>"请填写开户姓名"],
        ];
    }

    public function validateBankCardNum($attribute, $params)
    {
        if(!$this->hasErrors()){
            $trim_num = str_replace(' ','',$this->bank_card_num);
            $len = strlen($trim_num);
            if( $len != 16 && $len != 19){
                $this->addError($attribute,"银行卡不正确");
            }else{
                $last_num = substr($trim_num,-1,1);
                $sum = 0;
                $j = 1;
                for ($i=$len-2; $i>-1;$i--) {
                    $num = $trim_num[$i];
                    if ($j % 2 == 1) {
                        if ($num * 2 > 9) {
                            $sum += $num * 2 - 9;
                        } else {
                            $sum += $num * 2;
                        }
                    } else if ($j % 2 == 0) {
                        $sum += $num;
                    }
                    $j++;
                }
                $luhm = 10 - ($sum % 10 == 0 ? 10 : $sum % 10);
                if($last_num != $luhm){
                    $this->addError($attribute,"银行卡号不正确");
                }
            }
        }
    }

    public function dealFormData()
    {
        $formData = array();
        $formData["finance_information"] = json_encode([
            "bank_card_num" => preg_replace("/\s+/"," ",trim($this->bank_card_num)),
            "bank_name_opening" => $this->bank_name_opening,
            "bank_card_name" => $this->bank_card_name,
        ]);
        return $formData;
    }

    public function dealError($error)
    {
        $data = array();
        $data["code"] = 1;
        foreach ($error as $key => $item) {
            $data["message"]= $item[0];
            break;
        }
        return json_encode($data);
    }
}