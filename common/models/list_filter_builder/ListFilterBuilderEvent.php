<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-1-9
 * Time: 下午5:03
 */

namespace common\models\list_filter_builder;




use yii\base\Event;

class ListFilterBuilderEvent extends Event
{
    public $isValid = true;
    public $record;
    public $filter;
}