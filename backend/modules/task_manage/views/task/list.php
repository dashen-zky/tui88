<?php
use yii\helpers\Url;
use common\widgets\pagination\AjaxLinkPager;
use backend\modules\task_manage\models\Task;
?>
<div class="total-info clearfix">
    <span>共 <i><?= $task_lists["pagination"]->totalCount?></i> 条信息</span>
</div>
<div class="task-tb-info table-wrap table">
    <?php if(!empty($task_lists["list"])):?>
    <table>
        <thead>
        <tr>
            <th width="114">任务编号</th>
            <th width="280">任务名称</th>
            <th width="83">任务金额</th>
            <th width="95">领取状态</th>
            <th width="93">执行状态</th>
            <th width="137">领取时间</th>
            <th width="137">执行时间</th>
            <th width="137">发布时间</th>
            <th width="94">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($task_lists["list"] as $value):?>
            <tr>
                <td><?= $value["task_serial_number"]?></td>
                <td><a class="plain-text-length-limit" data-limit="16" href="<?= Url::to(['/task-manage/task/detail','uuid'=>$value['uuid']])?>" target="_blank"><?= $value["enable"] == Task::Disable ? "【异常】" : ''?><?= $value["title_thumbnail"] ?></a></td>
                <td><?= $value["unit_money"]?></td>
                <td>
                    <div><?= $value["getting_status_cn"]?></div>
                    <?php if($value["distribute_status"] == Task::Distributed):?>
                        <div><?= $value["number_of_gets"]."/".$value["limit"]?></div>
                    <?php endif;?>
                </td>
                <td>
                    <div><?= $value["executing_status_cn"]?></div>
                    <?php if($value["distribute_status"] == Task::Distributed):?>
                        <div><?= $value["received_submit"]["received_num"]."/".$value["received_submit"]["submit_num"] ?></div>
                    <?php endif;?>
                </td>
                <td>
                    <div><?= $value["start_getting_time_cn"]?></div>
                    <div><?= $value["end_getting_time_cn"]?></div>
                </td>
                <td>
                    <div><?= $value["start_execute_time_cn"]?></div>
                    <div><?= $value["end_execute_time_cn"]?></div>
                </td>
                <td>
                    <?= $value["distribute_time_cn"]?>
                </td>
                <td class="operation">
                    <?php if($value["distribute_status"] == Task::UnDistributed):?>
                        <a class="btn-modify" href="<?= Url::to(['/task-manage/task/publish','uuid'=>$value['uuid']])?>" target="_blank">修改</a>
                        <a class="btn-delete" href="javascript:;" url="<?= Url::to(['/task-manage/task/delete-task','uuid'=>$value['uuid']])?>">删除</a>
                    <?php endif;?>

                    <?php if($value["getting_status"] != Task::StatusUnKnow && ($value["getting_status"] != Task::GettingStatusEnd || $value["executing_status"] != Task::ExecutingStatusEnd)):?>
                        <a class="btn-stop" href="javascript:;" url="<?= Url::to(['/task-manage/task/terminate-task','uuid'=>$value['uuid']])?>">停止</a>
                    <?php endif;?>

                    <?php if($value["distribute_status"] == Task::Distributed && $value["limit"] != $value["remain_num"] && !empty($value["limit"])) :?>
                        <a class="btn-order" href="<?= Url::to(['/order-manage/order/index','task_uuid'=>$value['uuid']])?>" target="_blank">订单</a>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <?php else:?>
    <div class="no-task show">暂无任务</div>
    <?php endif;?>
</div>
<div class="page-wb system_page clearfix" data-value="20287">
    <?php
    if(isset($ser_filter) && !empty($ser_filter)){
        $pageParams = [
            'pagination' => $task_lists['pagination'],
            "ser_filter" =>$ser_filter,
        ];
    }else{
        $pageParams = [
            'pagination' => $task_lists['pagination'],
        ];
    }
    $pageParams["prevPageLabel"] = '上一页';
    $pageParams["nextPageLabel"] = '下一页';
    $pageParams["firstPageLabel"] = '首页';
    $pageParams["lastPageLabel"] = '尾页';
    ?>
    <?= AjaxLinkPager::widget($pageParams);?>
</div>