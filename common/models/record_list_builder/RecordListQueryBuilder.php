<?php
namespace common\models\record_list_builder;
use yii\base\Component;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecordInterface;

/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 16-12-29
 * Time: 下午11:31
 */
class RecordListQueryBuilder extends Component implements RecordListQueryBuilderInterface
{
    public function beforeBuildQuery($record, &$select, &$condition)
    {
        $e = new RecordListBuildQueryEvent($record, $select, $condition);
        $this->trigger(self::BeforeBuildQuery, $e);
        return $e->isValid;
    }

    public function afterBuildQuery($record, &$select, &$condition)
    {
        $e = new RecordListBuildQueryEvent($record, $select, $condition);
        $this->trigger(self::AfterBuildQuery, $e);
        return $e->isValid;
    }

    public function recordListBuilder($record, &$select, &$condition)
    {
        $this->validateParams($record, $select, $condition);
        if (!$this->beforeBuildQuery($record, $select, $condition)) {
            throw new Exception('beforeBuildQuery failed');
        }
        $query = $this->buildQuery($record, $select);
        if (!$this->afterBuildQuery($record, $select, $condition)) {
            throw new Exception('afterBuildQuery failed');
        }
        return $query->andWhere($condition);
    }

    public function buildQuery($record, $select) {
        $rules = $this->getRules($record);
        $selector = $this->buildSelector($select, $rules['rules']);
        $query = Yii::createObject(ActiveQuery::className(), [get_class($record)])
            ->select($selector)->alias('t1');
        foreach ($rules['rules'] as $rule) {
            if (!isset($rule['join_condition']) || !$rule['join_condition']) {
                continue;
            }
            $query->leftJoin($rule['table_name'] . ' ' . $rule['alias'], $rule['join_condition']);
        }
        if(isset($rules['groupBy']) && $rules['groupBy']) {
            $query->groupBy($rules['groupBy']);
        }

        return $query;
    }

    public function buildSelector($select, $rules) {
        $_select = [];

        foreach ($select as $key => $item) {
            if(!isset($rules[$key])) {
                continue;
            }

            $rule = $rules[$key];
            /**
             * 字段别名三种方式
             * １　不加任何修饰
             * ２　用key_field
             * 3  group_concat 形式　key_filed
             */
            switch ($rule['select_build_way']) {
                case 0:
                case false:
                    foreach ($select[$key] as $filed)
                        $_select[] = $rule['alias'] ."." . trim($filed);
                    break;
                case 1:
                    foreach ($select[$key] as $filed)
                        $selector[] = $rule['alias'] ."." . trim($filed) . " " . $key . "_" .trim($filed);
                    break;
                case 2:
                    foreach ($select[$key] as $filed)
                        $selector[] = "group_concat(".$rule['alias'] ."." . trim($filed) .") " . $key . "_" . trim($filed);
                    break;
            }
        }

        return $_select;
    }

    public function getRules($record) {
        $scenario = $record->getScenario();
        $rules = $record->buildRecordListRules();
        if(empty($rules)) {
            throw new Exception('dose not set build record list rules');
        }

        if(!isset($rules[$scenario]) || empty($rules[$scenario])) {
            throw new Exception('there is not suitable rules for the scenario');
        }

        return $rules[$scenario];
    }

    public function validateParams($record, $select, $condition) {
        if(!$record instanceof ActiveRecordInterface) {
            throw new Exception('the $record is not instanceof ActiveRecordInterface');
        }
        if (!is_array($select) && !is_array($condition)) {
            throw new Exception('the $select and $condition must be array');
        }
        if(empty($select)) {
            throw new Exception('the $select can not be empty');
        }
    }
}