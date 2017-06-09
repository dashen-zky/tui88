<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-1-2
 * Time: 下午5:58
 */

namespace common\models\record_operator;

use yii\db\Exception;
use yii\db\ActiveRecordInterface;
use Yii;

/**
 * Class RecordOperatorBasedOnRules 继承recordOperator, 重写insertRecord 和updateRecord的方法
 * 这两个方法将会基于insertRecordRules 和　updateRecordRules 来写
 * @package common\models\record_operator
 */
class RecordOperatorBasedOnRules extends RecordOperator
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

    public function insertRecord($record, &$formData)
    {
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
                throw new Exception('beforeInsertRecord failed');
            }
            $record->insert();
            if (!$this->insertRulesHandler($event->record, $event->formData)) {
                throw new Exception('insertRulesHandler failed');
            }
            if(!$this->afterInsertRecord($event)) {
                throw new Exception('afterInsertRecord failed');
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $transaction->commit();
        return true;
    }

    public function insertRulesHandler($record, $formData) {
        $rules = $this->getRules($record, $formData, 'insertRecordRules');
        if (empty($rules)) {
            return true;
        }

        foreach ($rules as $rule) {
            if (!isset($rule['class']) || empty($rule['class'])) {
                throw new Exception('the class is needed in this rule');
            }

            if (!isset($rule['operator']) || empty($rule['operator'])) {
                throw new Exception('the operator is needed in this rule');
            }

            if (!isset($rule['params']) || empty($rule['params'])) {
                throw new Exception('the params is needed in this rule');
            }

            if (isset($rule['operator_condition']) && $rule['operator_condition']) {
                $model = new $rule['class'];
                if(isset($rule['scenario'])) {
                    $model->setScenario($rule['scenario']);
                }
                call_user_func([$model, $rule['operator']], $rule['params']);
            }
        }
        return true;
    }

    public function updateRulesHandler($record, $formData) {
        $rules = $this->getRules($record, $formData, 'updateRecordRules');
        if (empty($rules)) {
            return true;
        }

        foreach ($rules as $rule) {
            if (!isset($rule['class']) || empty($rule['class'])) {
                throw new Exception('the class is needed in this rule');
            }

            if (!isset($rule['operator']) || empty($rule['operator'])) {
                throw new Exception('the operator is needed in this rule');
            }

            if (!isset($rule['params']) || empty($rule['params'])) {
                throw new Exception('the params is needed in this rule');
            }

            if (isset($rule['operator_condition']) && $rule['operator_condition']) {
                $model = new $rule['class'];
                if(isset($rule['scenario'])) {
                    $model->setScenario($rule['scenario']);
                }
                call_user_func([$model, $rule['operator']], $rule['params']);
            }
        }
        return true;
    }


    public function updateRecord($record, &$formData)
    {
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
                throw new Exception('beforeInsertRecord failed');
            }
            $record->update();
            if (!$this->updateRulesHandler($event->record, $event->formData)) {
                throw new Exception('insertRulesHandler failed');
            }
            if(!$this->afterUpdateRecord($event)) {
                throw new Exception('afterInsertRecord failed');
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $transaction->commit();
        return true;
    }

    public function getRules($record, $formData, $ruleFunction) {
        $scenario = $record->getScenario();
        if (!method_exists($record, $ruleFunction)) {
            return null;
        }

        $rules = $record->$ruleFunction($formData, $record);
        if(empty($rules)) {
            return null;
        }

        if(!isset($rules[$scenario]) || empty($rules[$scenario])) {
            return null;
        }

        return $rules[$scenario];
    }
}