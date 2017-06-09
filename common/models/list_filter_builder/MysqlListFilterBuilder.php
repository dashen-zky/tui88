<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-2-21
 * Time: 上午11:37
 */

namespace common\models\list_filter_builder;
use yii\base\Component;
use yii\base\Exception;
use Yii;
use yii\db\ActiveRecordInterface;


class MysqlListFilterBuilder extends Component implements ListFilterBuilder
{
    public function beforeListFilter($e) {
        $this->trigger(self::BeforeListFilter, $e);
        return $e->isValid;
    }

    public function afterListFilter($e) {
        $this->trigger(self::AfterListFilter, $e);
        return $e->isValid;
    }

    public function listFilterBuilder($record, $filter, $initial_condition, $selects) {
        $recordList = null;
        try {
            $this->checkArgs($record, $filter);
            $event = new ListFilterBuilderEvent([
                'record'=>$record,
                'filter'=>$filter,
            ]);
            if (!$this->beforeListFilter($event)) {
                throw new Exception('beforeListFilter failed');
            }
            $rule = $this->getRules($record, $filter);
            $condition = $this->buildCondition($rule, $filter, $initial_condition);
            $recordList = $record->recordList($selects, $condition, false, isset($rule['orderBy'])?$rule['orderBy']:null);
            if (!$this->afterListFilter($event)) {
                throw new Exception('afterListFilter failed');
            }
        } catch (Exception $e) {
            Yii::trace($e->getMessage());
            return null;
        }

        return $recordList;
    }

    public function checkArgs($record, $filter) {
        if (!$record instanceof ActiveRecordInterface) {
            throw new Exception('$record should be instanceof ActiveRecordInterface');
        }

        if (!is_array($filter)) {
            throw new Exception('$filter should be array');
        }
    }

    public function buildCondition($rule, $filter, $initial_condition) {
        $_condition = [
            'and'
        ];

        if(!empty($initial_condition)) {
            $_condition[] = $initial_condition;
        }
        foreach ($rule['fields'] as $key=>$item) {
            if (!isset($filter[$key]) || empty($filter[$key])) {
                continue;
            }

            $_condition[] = $item;
        }

        return $_condition;
    }

    public function getRules($record, $filter) {
        $scenario = $record->getScenario();
        $rules = $record->listFilterRules($filter);
        if(!isset($rules[$scenario]) || empty($rules[$scenario])) {
            throw new Exception('invalid scenario');
        }

        return $rules[$scenario];
    }
}