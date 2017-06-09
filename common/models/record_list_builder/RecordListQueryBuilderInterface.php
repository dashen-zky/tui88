<?php
namespace common\models\record_list_builder;
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 16-12-29
 * Time: 下午11:30
 */
interface RecordListQueryBuilderInterface
{
    const BeforeBuildQuery = 'BeforeBuildQuery';
    const AfterBuildQuery = 'AfterBuildQuery';
    /**
     * 让active record 类通过选择的字段，条件来构建record list query的方法
     * @param $record
     * @param $select
     * @param $condition
     * @return mixed　返回值是一个$query 对象
     */
    public function recordListBuilder($record, &$select, &$condition);
    public function beforeBuildQuery($record, &$select, &$condition);
    public function afterBuildQuery($record, &$select, &$condition);
}