<?php
namespace common\widgets\message;
use yii\db\ActiveRecord;

/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-1-4
 * Time: 上午11:42
 */
class BaseRecord extends ActiveRecord
{
    const MessageTable = 'dts_message';
    const MessageQueueSubscription = 'dts_message_queue_subscription';
    const MessageUserMap = 'dts_message_user_map';

    public function loadData($formData, $record) {
        $attributes = $this->attributes();
        foreach ($attributes as $attribute) {
            if (!isset($formData[$attribute])) {
                continue;
            }

            $record->$attribute = $formData[$attribute];
        }
    }
}