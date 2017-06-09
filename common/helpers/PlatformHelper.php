<?php
/**
 * Created by PhpStorm.
 * User: Gamelife
 * Date: 2016/12/11
 * Time: 13:32
 */

namespace common\helpers;

use yii\base\Security;


class PlatformHelper
{
    /**
     * 生成UUID
     * @param string $prefix
     * @return string
     */
    public static function getUUID($prefix='')
    {
        $randomString = (new Security())->generateRandomString(7);
        $randomString = str_replace('-', '_', $randomString);
        $rawUUID = time() . $randomString;
        if ($prefix !== '') {
            return $prefix . '_' .$rawUUID;
        }
        return $rawUUID;
    }

    public static function getRankCategories()
    {
        return \Yii::$app->params['rankCategories'];
    }

    /**
     * json 编码变量
     * @return array
     */
    public static function jsonEncodeVar()
    {
        $vars = func_get_args();
        $handle_var = function (&$value, $key){
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        };
        array_walk($vars, $handle_var);
        return $vars;
    }

    /**
     * 生成微信账户二维码链接
     * @param $account
     * @return string
     */
    public static function generateWeixinAccountQRcode($account)
    {
        return \Yii::$app->params['weixinQRCodeURLPrefix'] . $account;
    }

    /**
     * 根据数组中$key生成头像链接
     * @param array $array 二维数组
     * @param string $key
     * @param string $qrcode_key
     */
    public static function addWeixinAccountQRcode(array &$array, $key = 'public_id', $qrcode_key='avatar_image_url')
    {
        foreach ($array as $k => $item) {
            $array[$k][$qrcode_key] = static::generateWeixinAccountQRcode($item[$key]);
        }
    }

    /**
     * 处理微信防盗链技术
     */
    public static function wechatImgHandler($url) {
        preg_match('/\?wx_fmt=png/', $url, $match);
        if (empty($match)) {
            return $url;
        }

        return str_replace($match[0], '', $url);
    }
}