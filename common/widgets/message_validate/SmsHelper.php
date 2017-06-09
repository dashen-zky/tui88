<?php
namespace common\helpers;

use yii;

require_once("SendTemplateSMS.php");
class SmsHelper{
    /**
     * 发送带模板的短信
     * @param unknown $sMobile
     * @param unknown $iTempID
     */
    public static function sendTemplateSms($sMobile, $aData, $iTempID)
    {
        return sendTemplateSMS($sMobile, $aData, $iTempID);
    }
}