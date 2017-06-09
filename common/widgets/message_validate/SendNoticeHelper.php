<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 7/23/16 10:28 AM
 */
namespace common\widgets\message_validate;
use Yii;

/**
 * Class SendNotice
 * @package common\helpers
 * @author Tom <tom@51wom.com>
 * @since 1.0
 */
require_once("SendTemplateSMS.php");
class SendNoticeHelper{
    const TYPE_EMAIL = 1; // 邮箱
    const TYPE_SMS = 2; // 短信

    // 邮箱模板
    const EMAIL_TEMPLATE_WELCOME = 'welcome'; // 欢迎注册

    // 短信模板

    /**
     * @param $type 通知类型(邮件，短信等 ...)
     * @param $to 发送对象(邮箱，手机号码等 ...)
     * @param $template 模板
     * @param $params 模板中使用的参数
     * 调用示例：
     * 1.邮箱：
     * SendNoticeHelper::send(SendNoticeHelper::TYPE_EMAIL,'xxx@qq.com');
     * // 更多参数可直接添加在后面
     * 2.短信：
     * SendNoticeHelper::send(SendNoticeHelper::TYPE_SMS,'13800000000',0,array('6532','5'));
     */
    public static function send($type, $to, $template, $params=null){
        if($type == self::TYPE_EMAIL){
            self::sendEmail($to, $template, $params);
        }else if($type == self::TYPE_SMS){
            self::sendSms($to, $template, $params);
        }
    }

    /**
     * 发邮件
     * @param $to 要发送的邮箱
     * @param $template 要使用的模板
     * @param $params 要使用的参数
     */
    private static function sendEmail($to, $template, $params){

        Yii::$app->mailer->compose($template, [
            'html' => 'html', //key固定,value是模版文件名
            'title' => 333 ,
            'params' => $params
        ]) ->setTo($to)
            ->setSubject("51wom")
            ->send();

    }

    /**
     * 发短信
     * @param $to 要发送的手机号码
     * @param array $contentArray 要替换的内容
     * @param $template 模板id
     */
    private static function sendSms($to, $template, $contentArray = array()){

        sendTemplateSMS($to, $contentArray, $template);
    }
    //**************************************短信举例说明***********************************************************************
    //*假设您用测试Demo的APP ID，则需使用默认模板ID 1，发送手机号是13800000000，传入参数为6532和5，则调用方式为           *
    //*result = sendTemplateSMS("13800000000" ,array('6532','5'),"1");																		  *
    //*则13800000000手机号收到的短信内容是：【云通讯】您使用的是云通讯短信模板，您的验证码是6532，请于5分钟内正确输入     *
    //*********************************************************************************************************************
}