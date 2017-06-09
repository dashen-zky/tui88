<?php
include_once 'aliyun-php-sdk-core/Config.php';
use Sms\Request\V20160927 as Sms;
use yii\base\Exception;

function send($phone, $code, $location, $access_token, $access_security, $sign, $template) {
    $iClientProfile = DefaultProfile::getProfile($location, $access_token, $access_security);
    $client = new DefaultAcsClient($iClientProfile);
    $request = new Sms\SingleSendSmsRequest();
    $request->setSignName($sign);/*签名名称*/
    $request->setTemplateCode($template);/*模板code*/
    $request->setRecNum($phone);/*目标手机号*/
    $request->setParamString("{\"code\":\"$code\"}");/*模板变量，数字一定要转换为字符串*/
    try {
        $response = $client->getAcsResponse($request);
    }
    catch (ClientException  $e) {
        throw new Exception($e->getErrorCode());
    }
    catch (ServerException  $e) {
        throw new Exception($e->getErrorCode());
    }

    return $response;
}