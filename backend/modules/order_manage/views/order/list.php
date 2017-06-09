<?php
use yii\helpers\Url;
use common\widgets\pagination\AjaxLinkPager;
use backend\modules\order_manage\models\Order;
?>
<div class="total-account clearfix">
    <span>共<i><?= $order_lists["pagination"]->totalCount?></i>条信息</span>
</div>
<div class="task-tb-info table-wrap table">
    <?php if(!empty($order_lists["list"])):?>
    <table>
        <thead>
        <tr>
            <th width="150">订单编号</th>
            <th width="350">任务名称</th>
            <th width="150">创建时间</th>
            <th width="150">用户手机号</th>
            <th width="120">执行状态</th>
            <th width="120">任务金额</th>
            <th width="120">确认金额</th>
            <th width="150">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach ($order_lists["list"] as $value):
        ?>
        <tr>
            <td><?= $value["serial_number"]?></td>
            <td><a class="plain-text-length-limit" data-limit="18" href="<?= Url::to(['/task-manage/task/detail','uuid'=>$value['uuid']])?>" target="_blank"><?= $value["title"]?></a></td>
            <td><?= $value["create_time_cn"]?></td>
            <td><?= $value["phone"]?></td>
            <td><?= $value["status_cn"]?></td>
            <td><?= $value["unit_money"]?></td>
            <td><?= $value["insure_money"]?></td>
            <td class="operate">
                <a href="<?= Url::to(['/order-manage/order/executing-detail','id'=>$value['id']])?>" target="_blank">详情</a>
                <a class="same-batch" href="javascript:;" task-uuid="<?= $value['uuid']?>">同批</a>
                <?php if($value["status"] == Order::ExecutingStatusConfirm):?>
                <a class="check" href="javascript:;" data-toggle="modal" data-target="#modal-check" url="<?= Url::to(['/order-manage/order/get-check-information','id'=>$value['id']])?>">审核</a>
                <?php endif;?>
                <?php if($value["status"] == Order::ExecutingStatusNotPass || $value["status"] == Order::ExecutingStatusTerminated):?>
                <a class="btn-modify" href="javascript:;" data-toggle="modal" data-target="#modal-modify" url="<?= Url::to(['/order-manage/order/get-check-information','id'=>$value['id']])?>">修改</a>
                <?php endif;?>
            </td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <?php else:?>
    <div class="no-task show">暂无订单</div>
    <?php endif;?>
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
