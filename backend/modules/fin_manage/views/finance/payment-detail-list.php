<?php
use common\widgets\pagination\AjaxLinkPager;
use yii\helpers\Url;
?>
<div class="task-table">
    <div class="task-tb-info table-wrap table">
        <table>
            <thead>
            <tr>
                <th width="100">序号</th>
                <th width="150">订单编号</th>
                <th width="320">任务名称</th>
                <th width="150">确认时间</th>
                <th width="150">任务金额</th>
                <th width="150">确认金额</th>
                <th width="150">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($order_lists["list"])):?>
            <?php $i = 1; foreach ($order_lists["list"] as $key => $value): ?>
            <tr>
                <td><?= $i?></td>
                <td><?= $value["serial_number"]?></td>
                <td><a class="plain-text-length-limit" data-limit="20" href="<?= Url::to(['/task-manage/task/detail','uuid'=>$value['task_uuid']])?>" target="_blank"><?= $value["title"]?></a></td>
                <td><?= $value["insure_time_cn"]?></td>
                <td><?= $value["unit_money"]?></td>
                <td><?= $value["insure_money"]?></td>
                <td class="operate">
                    <a href="<?= Url::to(['/order-manage/order/executing-detail','id'=>$value['task_map_id']])?>" target="_blank">详情</a>
                </td>
            </tr>
            <?php $i++; endforeach;?>
            <?php endif;?>
            </tbody>
        </table>
        <div class="no-task">暂无付款详情</div>
    </div>
    <div class="page-wb system_page clearfix" data-value="20287">
        <?php
        if(isset($ser_filter) && !empty($ser_filter)){
            $pageParams = [
                'pagination' => $order_lists['pagination'],
                "ser_filter" =>$ser_filter,
            ];
        }else{
            $pageParams = [
                'pagination' => $order_lists['pagination'],
            ];
        }
        $pageParams["prevPageLabel"] = '上一页';
        $pageParams["nextPageLabel"] = '下一页';
        $pageParams["firstPageLabel"] = '首页';
        $pageParams["lastPageLabel"] = '尾页';
        ?>
        <?= AjaxLinkPager::widget($pageParams);?>
    </div>
</div>