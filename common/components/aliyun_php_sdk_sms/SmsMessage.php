<?php
namespace common\components\aliyun_php_sdk_sms;

include_once 'send.php';
use Yii;
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-3-18
 * Time: 下午10:05
 */
class SmsMessage {
    const Location = 'cn-shanghai';
    const AccessToken = "LTAI5xcV9fMnxWvx";
    const AccessSecurity = "75ZusDqWCiAveNOkDr4zcgNS9QM5Kl";
    const TemplateCode = 'SMS_67316247';
    const FreeSignName = "推发发";


    public static function sendMessage($phone, $code) {
        $ipMonitor = new IpMonitor();
        $ipMonitor->ip = Yii::$app->getRequest()->getUserIP();
        if(!$ipMonitor->insert()) {
            return -1;
        }

        return send($phone,
            $code,
            self::Location,
            self::AccessToken,
            self::AccessSecurity,
            self::FreeSignName,
            self::TemplateCode);
    }
}