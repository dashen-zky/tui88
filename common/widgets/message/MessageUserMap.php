<?php
namespace common\widgets\message;
use Yii;
use yii\base\Event;
use yii\base\Exception;

/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-1-4
 * Time: 上午11:47
 */
class MessageUserMap extends BaseRecord
{
    const Checked = 1;
    const UnChecked = 0;

    const BeforeDeleteMessage = 'BeforeDeleteMessage';
    const AfterDeleteMessage = 'AfterDeleteMessage';

    public static function tableName()
    {
        return self::MessageUserMap;
    }

    public function attributes()
    {
        return [
            'id','message_id','checked','checked_time','user_id',
        ];
    }

    public function insertRecord($formData) {
        $this->loadData($formData, $this);
        return $this->insert();
    }

    public function checkMessage($message_id) {
        $record = self::find()->andWhere([
            'message_id'=>$message_id,
            'user_id'=>Yii::$app->getUser()->getId()
        ])->one();
        if(empty($record)) {
            throw new Exception('without this message');
        }

        $record->checked = self::Checked;
        $record->checked_time = time();
        return $record->update();
    }

    public function deleteMessage($message_id, $user_id = null) {
        $user_id = empty($user_id)?Yii::$app->getUser()->getId():$user_id;

        $record = self::find()->andWhere([
            'user_id'=>$user_id,
            'message_id'=>$message_id
        ])->one();
        if (empty($record)) {
            return true;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $event = new MessageEvent([
                'id'=>$message_id
            ]);
            $this->trigger(self::BeforeDeleteMessage, $event);
            $record->delete();
            $this->trigger(self::AfterDeleteMessage, $event);
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::trace($e->getMessage());
            return false;
        }

        $transaction->commit();
        return true;
    }
}