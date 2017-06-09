<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/19 0019
 * Time: 上午 11:09
 */

namespace common\helpers;


class ViewHelper
{
    const RequiredField = "*";
    public $requiredFields = [];
    static function defaultValueForDropDownList($edit = false, $formData, $index) {
        if($edit && isset($formData[$index])) {
            return [$formData[$index]=>['Selected'=>true]];
        }
    }

    public static function appendElementOnDropDownList($list, $append = null) {
        if(empty($append)) {
            return [0=>'未选择']+$list;
        }
        return $list+$append;
    }
}