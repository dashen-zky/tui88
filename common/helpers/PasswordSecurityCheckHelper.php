<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 16-12-27
 * Time: 下午11:55
 */

namespace common\helpers;


class PasswordSecurityCheckHelper
{
    const LowSecurity = 1;
    const MiddleSecurity = 2;
    const HighSecurity = 3;

    /**
     * 规则
     * 只有数字或是只有字母，密码安全性为低
     * 有数字且有字母且长度小于等于７，密码安全性为中
     * 有数字且有字母且长度大于７，密码安全性为高
     */
    public static function passwordSecurityCheck($password) {
        $reg = "/(?:(\d+)?([a-zA-Z]+)?(\d+)?)/";
        preg_match($reg, $password, $match);
        $hasNumber = 0;
        $hasLetter = 0;
        for($i = 1; $i < count($match); $i++) {
            if(empty($match[$i])) {
                continue;
            }

            switch ($i) {
                case 1:
                case 3:
                    $hasNumber = 1;
                    break;
                case 2:
                    $hasLetter = 1;
                    break;
            }
        }

        if($hasLetter + $hasNumber < 2) {
            return self::LowSecurity;
        }

        $langEnough = 0;
        if (strlen($password) >= 8) {
            $langEnough = 1;
        }

        return $langEnough + $hasLetter + $hasNumber;
    }
}