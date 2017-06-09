<?php
use common\widgets\pagination\AjaxLinkPager;
use frontend\modules\taskHall\models\ExecutorTaskMap;
use yii\helpers\Url;
?>
<div class="task-tb-info table-wrap">
    <?php if(!empty($task_lists["list"])):?>
        <table>
        <thead>
        <tr>
            <th width="110">任务编号</th>
            <th width="240">任务名称</th>
            <th width="100">执行状态</th>
            <th width="200">执行时间</th>
            <th width="100">任务金额</th>
            <th width="100">确认金额</th>
            <th width="180">操作</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($task_lists["list"] as $item):?>
                <tr>
                    <td><?= $item["serial_number"]?></td>
                    <td><a class="plain-text-length-limit" data-limit="18" href="<?= Url::to(['/task-hall/task/task-detail','uuid'=>$item['task_uuid']]) ?>" target="_blank"><?= $item["title"]?></a></td>
                    <td><?= $item["status_cn"];?></td>
                    <td class="col-md-3"><?= $item["start_execute_time_cn"]?> 至 <?= $item["end_execute_time_cn"]?></td>
                    <td>￥<?= $item["unit_money"]?></td>
                    <td><?= $item["insure_money"]?></td>
                    <td class="operation" task_map_id="<?= $item['id']?>" task_uuid="<?= $item['task_uuid']?>">
                        <a class="execute-detail" href="<?= Url::to(['/personal-center/task-manage/task/executing-detail','id'=>$item['id']])?>" target="_blank"><i></i><span>执行详情</span></a>

                        <?php if($item["status"] == ExecutorTaskMap::ExecutingStatusExecuting && time() < $item["end_execute_time"]):?>
                            <a class="submit-result" href="javascript:;" data-toggle="modal" data-target="#modal-submit" src="<?= Url::to(['/personal-center/task-manage/task/submit-form','uuid'=>$item['task_uuid']])?>"><i></i><span>提交</span></a>
                        <?php endif;?>

                        <?php if($item["status"] == ExecutorTaskMap::ExecutingStatusConfirm):?>
                            <a class="modify" href="javascript:;" data-toggle="modal" data-target="#modal-modify" src="<?= Url::to(['/personal-center/task-manage/task/modify-form','id'=>$item['id']])?>"><i></i><span>修改</span></a>
                        <?php endif;?>

                        <?php if($item["status"] == ExecutorTaskMap::ExecutingStatusReceived):?>
                            <a class="evidence" href="javascript:;" data-toggle="modal" data-target="#paying-voucher" url="<?= Url::to(['/personal-center/task-manage/task/get-paying-voucher','id'=>$item['id']])?>"><i></i><span>收款凭证</span></a>
                        <?php endif;?>

                        <?php if($item["status"] == ExecutorTaskMap::ExecutingStatusWaiting || $item["status"] == ExecutorTaskMap::ExecutingStatusExecuting || $item["status"] == ExecutorTaskMap::ExecutingStatusConfirm):?>
                            <a task_id="<?= $item['id']?>" class="give-up" href="javascript:;" data-toggle="modal" data-target="#give-up"><i></i><span>放弃</span></a>
                        <?php endif;?>

                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
        <?php else:?>
        </table>
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


