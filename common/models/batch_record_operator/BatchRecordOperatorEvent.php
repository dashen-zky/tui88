<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 16-12-25
 * Time: 下午8:02
 */

namespace common\models\batch_record_operator;


use yii\base\Event;

class BatchRecordOperatorEvent extends Event
{
    public $record;
    public $formData;
    public $isValid = true;

    public function __construct($record, $formData, $config= [])
    {
        $this->record = $record;
        $this->formData = $formData;
        parent::__construct($config);
    }
}