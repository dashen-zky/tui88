<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 16-12-24
 * Time: 下午4:43
 */

namespace common\models;


use common\helpers\PlatformHelper;
use common\models\batch_record_operator\BatchRecordOperator;
use common\models\record_list_builder\RecordListQueryBuilder;
use common\models\record_operator\RecordOperatorBasedOnRules;
use common\models\record_operator\RecordOperatorInterFace;
use common\widgets\pagination\Pagination;
use yii\base\Exception;
use yii\db\ActiveRecord;
use common\models\list_filter_builder\MysqlListFilterBuilder;
use Yii;

class BaseRecord extends ActiveRecord
{
    const USERACCOUNT = 'frontend_user';
    const TASKTABLE = 'task';
    const EXECUTOR_TASK_MAP = 'executor_task_map';
    const ADMINACCOUNT = 'backend_user';
    const UserInformation = 'frontend_user_information';
    const DistributorExecutorMap = 'distributor_executor_map';
    const Finance_Record = 'finance_record';
    const OrderFinanceRecord = 'order_finance_map';

    const Enable = 1;
    const Disable = 2;
    const BaseTitleLength = 15;

    public $pageSize = 20;

    public $recordOperator;
    public $recordListBuilder;
    public $listFilterBuilder;
    public $batchRecordOperator;

    public function init()
    {
        $this->recordOperator = Yii::$container->get(RecordOperatorBasedOnRules::className());
        $this->recordListBuilder = Yii::$container->get(RecordListQueryBuilder::className());
        $this->listFilterBuilder = Yii::$container->get(MysqlListFilterBuilder::className());
        $this->batchRecordOperator = Yii::$container->get(BatchRecordOperator::className());
    }

    public function buildRecordListRules() {

    }

    public function listFilterRules($filter) {

    }

    public function insertRecordRules($formData= null, $record = null) {

    }

    public function updateRecordRules($formData= null, $record = null) {

    }

    /**
     * 对表单数据进行预处理，用于更新和插入数据使用
     * @param $formData
     * @param null $record
     */
    public function formDataPreHandler(&$formData, $record = null) {
        if(empty($record)) {
            if (!isset($formData['uuid']) || empty($formData['uuid'])) {
                $formData['uuid'] = PlatformHelper::getUUID();
            }
            if (!isset($formData['create_time']) || empty($formData['create_time'])) {
                $formData['create_time'] = time();
            }
            $this->clearEmptyField($formData);
        }
        $formData['update_time'] = time();
    }

    /**
     *
     * @param $formData
     * @param null $record
     * @return bool
     */
    public function updateRecordBuilder($formData, $record = null) {
        $columns = $this->getTableSchema()->columns;
        $flag = false;
        foreach ($columns as $key => $column) {
            if(!isset($formData[$key])) {
                continue;
            }

            $flag = true;
            if (!empty($record)) {
                $record->$key = trim($formData[$key], ' ');
                continue;
            }

            $this->$key = trim($formData[$key], ' ');
        }
        return $flag;
    }

    /**
     * 在更新或是插入数据之前对自己的一些处理
     * @param $formData
     * @param null $record
     */
    public function recordPreHandler(&$formData, $record = null) {
        return true;
    }

    /**
     * 将formData里面的空的地方删除掉,提升效率
     * @param $formData
     */
    public function clearEmptyField(&$formData) {
        foreach($formData as $index => $value) {
            if(empty($formData[$index])) {
                unset($formData[$index]);
            }
        }
    }

    /**
     *
     * @param $e
     * @return bool
     */
    public function beforeInsertRecord($e) {
        $this->formDataPreHandler($e->formData);

        if (!$this->updateRecordBuilder($e->formData)) {
            $e->isValid = false;
            return false;
        }

        $this->recordPreHandler($e->formData);

        return true;
    }

    /**
     *
     * @param $e
     */
    public function afterInsertRecord($e) {

    }

    /**
     * @param $e
     * @return bool
     */
    public function beforeUpdateRecord($e) {
        $this->formDataPreHandler($e->formData, $e->record);

        if (!$this->updateRecordBuilder($e->formData, $e->record)) {
            $e->isValid = false;
            return false;
        }

        $this->recordPreHandler($e->formData, $e->record);

        return true;
    }

    /**
     * @param $e
     */
    public function afterUpdateRecord($e) {

    }

    public function insertRecord($formData) {
        if(empty($formData)) {
            return true;
        }
        $this->recordOperator->on(RecordOperatorInterFace::BeforeInsertRecord,[$this, 'beforeInsertRecord']);
        $this->recordOperator->on(RecordOperatorInterFace::AfterInsertRecord,[$this, 'afterInsertRecord']);
        return $this->recordOperator->insertRecord($this, $formData);
    }

    public function beforeBatchInsertRecord($e) {
        $operator = $e->sender;
        $operator->table = static::tableName();
        return $e->isValid;
    }

    public function afterBatchInsertRecord($e) {

    }

    public function batchInsertRecordRules($formData= null, $record = null) {

    }

    public function batchInsertRecords($formData) {
        if(empty($formData)) {
            return true;
        }

        $this->batchRecordOperator->on(BatchRecordOperator::BeforeBatchInsertRecord,[$this, 'beforeBatchInsertRecord']);
        $this->batchRecordOperator->on(BatchRecordOperator::AfterBatchInsertRecord,[$this, 'afterBatchInsertRecord']);
        return $this->batchRecordOperator->batchInsertRecord($this, $formData);
    }

    public function beforeBatchUpdateRecord($e) {
        $operator = $e->sender;
        $operator->table = static::tableName();
        return $e->isValid;
    }

    public function afterBatchUpdateRecord($e) {

    }

    public function batchUpdateRecordRules($formData= null, $record = null) {

    }

    public function batchUpdateRecords($formData) {
        if(empty($formData)) {
            return true;
        }

        $this->batchRecordOperator->on(BatchRecordOperator::BeforeBatchUpdateRecord,[$this, 'beforeBatchUpdateRecord']);
        $this->batchRecordOperator->on(BatchRecordOperator::AfterBatchUpdateRecord,[$this, 'afterBatchUpdateRecord']);
        return $this->batchRecordOperator->batchUpdateRecord($this, $formData);
    }

    /**
     * @param $formData
     * @return bool
     */
    public function updateRecord($formData) {
        if (empty($formData) || (!isset($formData['uuid']) && !isset($formData['id']))) {
            return true;
        }

        $condition = isset($formData['uuid'])?[
            'uuid'=>$formData['uuid']
        ]:[
            'id'=>$formData['id']
        ];
        $record = self::find()->where($condition)->one();
        if (empty($record)) {
            return true;
        }
        $record->setScenario($this->getScenario());
        $this->recordOperator->on(RecordOperatorInterFace::BeforeUpdateRecord,[$this, 'beforeUpdateRecord']);
        $this->recordOperator->on(RecordOperatorInterFace::AfterUpdateRecord,[$this, 'afterUpdateRecord']);

        return $this->recordOperator->updateRecord($record, $formData);
    }

    /**
     * 连表查询list的方法
     * @param $selector 选择器
     * @param $condition　条件
     * @param bool $fechOne　是否只查一条数据
     */
    public function recordList($selects, $condition, $fetchOne = false, $orderBy = null, $enablePage = true) {
        try {
            $query = $this->recordListBuilder->recordListBuilder($this, $selects, $condition);
        } catch (Exception $e) {
            \Yii::trace($e->getMessage());
            return false;
        }

        if ($fetchOne) {
            $record = $query->asArray()->one();
            return $record;
        }

        if (!$enablePage) {
            return $query->asArray()->all();
        }

        $pagination = new Pagination([
            'totalCount'=>$query->count(),
            'pageSize' => $this->pageSize,
        ]);

        $_orderBy = empty($orderBy)?[
            't1.id' => SORT_DESC,
        ]:$orderBy;

        $list = $query->orderBy($_orderBy)->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
        return [
            'pagination' => $pagination,
            'list'=> $list,
        ];
    }

    public function getRecord($selects, $condition) {
        return $this->recordList($selects, $condition, true);
    }

    /**
     * 将formdata 的时间字符串变成时间戳
     * @param $formData
     * @param $index
     * @return false|int|null
     */
    public function formDataStrToTime($formData, $index) {
        return isset($formData[$index]) && !empty($formData[$index]) ? strtotime($formData[$index]) : null;
    }

    // 获得指定field的列表
    public function getAppointedFieldList($recordList, $field) {
        if (empty($recordList)) {
            return null;
        }

        $_list = null;
        foreach ($recordList as $record) {
            $_list[] = $record[$field];
        }

        return $_list;
    }

    public function listFilter($selects, $filter, $initial_condition = null) {
        if (empty($filter)) {
            return $this->recordList($selects, $initial_condition);
        }

        return $this->listFilterBuilder->listFilterBuilder($this, $filter, $initial_condition, $selects);
    }

    /**
     * 从数据库里面查出来的结果集里面构成键值对
     */
    public function buildKVP($recordList, $key, $value) {
        if (empty($recordList)) {
            return null;
        }

        $_list = null;
        foreach ($recordList as $record) {
            if (!isset($record[$key])) {
                continue;
            }

            $_list[$record[$key]] = $record[$value];
        }

        return $_list;
    }
}