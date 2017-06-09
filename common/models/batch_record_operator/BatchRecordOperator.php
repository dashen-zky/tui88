<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-4-17
 * Time: 上午11:53
 */

namespace common\models\batch_record_operator;


use yii\base\Component;
use Yii;
use yii\base\Exception;
use yii\db\ActiveRecordInterface;


class BatchRecordOperator extends Component  implements BatchRecordOperatorInterFace
{
    public $table;
    public $rows;
    public $column;
    public $condition;
    public $params;

    public function beforeBatchInsertRecord($event) {
        $this->trigger(self::BeforeBatchInsertRecord, $event);
        return $event->isValid;
    }

    public function afterBatchInsertRecord($event) {
        $this->trigger(self::AfterBatchInsertRecord, $event);
        return $event->isValid;
    }

    public function beforeBatchUpdateRecord($event) {
        $this->trigger(self::BeforeBatchUpdateRecord, $event);
        return $event->isValid;
    }

    public function afterBatchUpdateRecord($event) {
        $this->trigger(self::AfterBatchUpdateRecord, $event);
        return $event->isValid;
    }

    public function batchInsertRecord($record, &$formData)
    {
        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            if (empty($formData)) {
                throw new Exception('$formData should not be empty');
            }

            if (!$record instanceof ActiveRecordInterface) {
                throw new Exception('$record should be instanceof ActiveRecordInterface');
            }
            $event = new BatchRecordOperatorEvent($record, $formData);
            if(!$this->beforeBatchInsertRecord($event)) {
                throw new \Exception('beforeInsertRecord failed');
            }
            if(empty($this->table)) {
                throw new Exception('invalid table');
            }

            if(empty($this->column)) {
                throw new Exception('invalid columns');
            }

            if(empty($this->rows)) {
                throw new Exception('invalid rows');
            }
            Yii::$app->db->createCommand()
                ->batchInsert($this->table, $this->column, $this->rows)
                ->execute();
            if (!$this->batchInsertRulesHandler($event->record, $event->formData)) {
                throw new Exception('insertRulesHandler failed');
            }

            if(!$this->afterBatchInsertRecord($event)) {
                throw new \Exception('afterInsertRecord failed');
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

    public function batchUpdateRecord($record, &$formData)
    {
        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            if (empty($formData)) {
                throw new Exception('$formData should not be empty');
            }

            if (!$record instanceof ActiveRecordInterface) {
                throw new Exception('$record should be instanceof ActiveRecordInterface');
            }
            $event = new BatchRecordOperatorEvent($record, $formData);
            if(!$this->beforeBatchUpdateRecord($event)) {
                throw new \Exception('beforeInsertRecord failed');
            }
            if(empty($this->table)) {
                throw new Exception('invalid table');
            }

            if(empty($this->column)) {
                throw new Exception('invalid columns');
            }

            Yii::$app->db->createCommand()
                ->update(
                    $this->table,
                    $this->column,
                    $this->condition,
                    $this->params)
                ->execute();
            if (!$this->batchUpdateRulesHandler($event->record, $event->formData)) {
                throw new Exception('insertRulesHandler failed');
            }

            if(!$this->afterBatchUpdateRecord($event)) {
                throw new \Exception('afterInsertRecord failed');
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

    public function batchDeleteRecord($record, $condition)
    {
        // TODO: Implement batchDeleteRecord() method.
    }

    public function batchDisableRecord($record, $condition)
    {
        // TODO: Implement batchDisableRecord() method.
    }

    public function batchInsertRulesHandler($record, $formData) {
        $rules = $this->getRules($record, $formData, 'batchInsertRecordRules');
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

    public function batchUpdateRulesHandler($record, $formData) {
        $rules = $this->getRules($record, $formData, 'batchUpdateRecordRules');
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