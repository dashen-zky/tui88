<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 16-12-25
 * Time: 下午8:17
 */

namespace common\models\record_operator;


interface RecordOperatorInterFace
{
    const Enable = 127;
    const Disable = 126;
    // define event
    const BeforeInsertRecord = 'BeforeInsertRecord';
    const AfterInsertRecord = 'AfterInsertRecord';
    const BeforeUpdateRecord = 'BeforeUpdateRecord';
    const AfterUpdateRecord = 'AfterUpdateRecord';
    const BeforeDeleteRecord = 'BeforeDeleteRecord';
    const AfterDeleteRecord = 'AfterDeleteRecord';

    public function insertRecord($record, &$formData);
    public function updateRecord($record, &$formData);
    // 按条件删除某个record
    public function deleteRecord($record, $condition);
    public function disableRecord($record, $condition);
}