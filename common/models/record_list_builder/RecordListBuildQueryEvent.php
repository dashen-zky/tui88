<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 16-12-30
 * Time: 上午12:09
 */

namespace common\models\record_list_builder;


use yii\base\Event;

class RecordListBuildQueryEvent extends Event
{
    public $record;
    public $select;
    public $condition;
    public $isValid = true;

    public function __construct($record, &$select, &$condition, $config= [])
    {
        $this->record = $record;
        $this->select = $select;
        $this->condition = $condition;
        parent::__construct($config);
    }
}