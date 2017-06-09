<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 16-12-25
 * Time: 下午8:21
 */

namespace common\models\record_operator;


use yii\base\Component;
use common\models\record_operator\ActiveRecordEvent;
use Yii;
use yii\db\Exception;
use yii\db\ActiveRecordInterface;

class RecordOperator extends Component  implements RecordOperatorInterFace
{
    public function beforeInsertRecord($event) {
        $this->trigger(self::BeforeInsertRecord, $event);
        return $event->isValid;
    }

    public function afterInsertRecord($event) {
        $this->trigger(self::AfterInsertRecord, $event);
        return $event->isValid;
    }

    public function beforeUpdateRecord($event) {
        $this->trigger(self::BeforeUpdateRecord, $event);
        return $event->isValid;
    }

    public function afterUpdateRecord($event) {
        $this->trigger(self::AfterUpdateRecord, $event);
        return $event->isValid;
    }

    public function insertRecord($record, &$formData) {
        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            if (empty($formData)) {
                throw new Exception('$formData should not be empty');
            }

            if (!$record instanceof ActiveRecordInterface) {
                throw new Exception('$record should be instanceof ActiveRecordInterface');
            }
            $event = new ActiveRecordEvent($record, $formData);
            if(!$this->beforeInsertRecord($event)) {
                throw new \Exception('beforeInsertRecord failed');
            }
            $record->insert();
            if(!$this->afterInsertRecord($event)) {
                throw new \Exception('afterInsertRecord failed');
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::trace($e->getMessage());
            return false;
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::trace($e->getMessage());
            return false;
        }

        $transaction->commit();
        return true;
    }

    public function updateRecord($record, &$formData) {

        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            if (empty($formData)) {
                throw new Exception('$formData should not be empty');
            }

            if (!$record instanceof ActiveRecordInterface) {
                throw new Exception('$record should be instanceof ActiveRecordInterface');
            }

            $event = new ActiveRecordEvent($record, $formData);
            if(!$this->beforeUpdateRecord($event)) {
                throw new \Exception('beforeUpdateRecord failed');
            }
            $record->update();
            if(!$this->afterUpdateRecord($event)) {
                throw new \Exception('afterUpdateRecord failed');
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
            Yii::trace($e->getMessage());
            return false;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
            Yii::trace($e->getMessage());
            return false;
        }

        $transaction->commit();
        return true;
    }

    public function deleteRecord($record, $condition)
    {
        // TODO: Implement deleteRecord() method.
    }

    public function disableRecord($record, $condition)
    {
        // TODO: Implement disableRecord() method.
    }
}