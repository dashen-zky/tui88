<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 16-12-25
 * Time: 下午8:17
 */

namespace common\models\batch_record_operator;


interface BatchRecordOperatorInterFace
{
    const Enable = 127;
    const Disable = 126;
    // define event
    const BeforeBatchInsertRecord = 'BeforeBatchInsertRecord';
    const AfterBatchInsertRecord = 'AfterBatchInsertRecord';
    const BeforeBatchUpdateRecord = 'BeforeBatchUpdateRecord';
    const AfterBatchUpdateRecord = 'AfterBatchUpdateRecord';
    const BeforeBatchDeleteRecord = 'BeforeBatchDeleteRecord';
    const AfterBatchDeleteRecord = 'AfterBatchDeleteRecord';

    public function batchInsertRecord($record, &$formData);
    public function batchUpdateRecord($record, &$formData);
    // 按条件删除某个record
    public function batchDeleteRecord($record, $condition);
    public function batchDisableRecord($record, $condition);
}