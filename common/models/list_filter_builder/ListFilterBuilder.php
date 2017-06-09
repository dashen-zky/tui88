<?php
namespace common\models\list_filter_builder;
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-1-9
 * Time: 下午5:02
 */
interface ListFilterBuilder
{
    const BeforeListFilter = 'BeforeListFilter';
    const AfterListFilter = 'AfterListFilter';
    public function listFilterBuilder($record, $filter, $initial_condition, $selects);
}