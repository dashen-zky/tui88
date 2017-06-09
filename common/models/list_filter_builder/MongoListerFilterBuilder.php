<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-1-9
 * Time: 下午5:05
 */

namespace common\models\list_filter_builder;


use yii\base\Component;
use yii\base\Exception;
use yii\db\ActiveRecordInterface;
use Yii;

class MongoListerFilterBuilder extends Component implements ListFilterBuilder
{
    public function beforeListFilter($e) {
        $this->trigger(self::BeforeListFilter, $e);
        return $e->isValid;
    }

    public function afterListFilter($e) {
        $this->trigger(self::AfterListFilter, $e);
        return $e->isValid;
    }

    public function listFilterBuilder($record, $filter, $initial_condition, $selects = null) {
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
            $recordList = $record->recordList($condition, false, true, isset($rule['oderBy'])?$rule['oderBy']:null);
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