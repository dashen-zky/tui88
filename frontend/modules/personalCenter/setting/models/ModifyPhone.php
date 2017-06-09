<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-5-4
 * Time: 下午2:52
 */

namespace frontend\modules\personalCenter\setting\models;

use common\models\BaseRecord;

class ModifyPhone extends BaseRecord
{
    public static  function tableName()
    {
        return parent::USERACCOUNT;
    }

    public function modifyPhone($phone)
    {
        $record = self::findOne(["phone"=>$phone]);
        if($record){
            return json_encode(["code"=>1,"message"=>"该手机号已被注册！"]);
        }
        $data = $this->updateRecord(["phone"=>$phone,"uuid"=>\Yii::$app->user->identity->uuid]);
        if($data){
            return json_encode(["code"=>0,"message"=>"更改成功!"]);
        }
        return json_encode(["code"=>2,"message"=>"更改失败！"]);
    }

    public function isExistPhone($phone)
    {
        $record = self::findOne(["phone"=>$phone]);
        return $record;

    }


}