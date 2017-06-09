<?php
namespace common\components\aliyun_php_sdk_sms;
use yii\db\ActiveRecord;

/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-3-24
 * Time: 上午11:12
 */
class IpMonitor extends ActiveRecord
{
    public static function tableName()
    {
        return 'ip_monitor';
    }
}