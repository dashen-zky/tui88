<?php
use common\widgets\pagination\AjaxLinkPager;
use frontend\modules\taskHall\models\Task;
use yii\helpers\Url;
?>
<div class="task-list-con">
    <table class="table">
        <thead>
        <tr>
            <th width="280">任务名称</th>
            <th width="90">任务金额</th>
            <th width="90">任务总量</th>
            <th width="90">剩余数量</th>
            <th width="160">发布时间</th>
            <th width="160">领取时间</th>
            <th width="160">执行时间</th>
            <th width="90">领取状态</th>
            <th width="80">操作</th>
        </tr>
        </thead>
        <tbody>
            <?php if(!empty($task_lists['list'])) :?>
            <?php foreach ($task_lists["list"] as $key => $list):?>
            <tr>
                <td>
                    <a target="_blank" href="<?= Url::to(["/task-hall/task/task-detail","uuid"=>$list["uuid"]])?>">
                        <?= $list["title"]?>
                    </a>
                </td>
                <td><?= $list["unit_money"]?></td>
                <td><?= $list["limit"]?></td>
                <td class="remain-num" value="<?= $list["remain_num"]?>"><?= $list["remain_num"]?></td>
                <td><?= $list["distribute_time_cn"]?></td>
                <td>
                    <div><?= $list["start_getting_time_cn"]?></div>
                    <div><?= $list["end_getting_time_cn"]?></div>
                </td>
                <td>
                    <div><?= $list["start_execute_time_cn"]?></div>
                    <div><?= $list["end_execute_time_cn"]?></div>
                </td>
                <td><?= $list['getting_status_cn']?></td>
                <td class="operate">
                    <?php if($list["getting_status"] == Task::GettingStatusWaitingStart){?>
                        <span ><i ></i> 领任务</span>
                    <?php }else if($list["getting_status"] == Task::GettingStatusEnable){?>
                        <span class="click" my-method="check" data-target="<?= empty(Yii::$app->session['user_information']['finance_information']) ? '#add-collection-info': '#confirm-get-task';?>" data-toggle="modal" url="<?= Url::to(['/task-hall/task/is-getting-task','task_uuid'=>$list['uuid'],'distributor_uuid'=>$list['create_uuid'],'method'=>'check'])?>"><i class="click"></i> 领任务</span>
                    <?php }?>
                </td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
        </tbody>
    </table>
    <?php if(empty($task_lists['list'])): ?>
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