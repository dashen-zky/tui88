<?php
use yii\helpers\Url;
use common\widgets\pagination\AjaxLinkPager;
?>
<div class="total-account clearfix">
    <span>共<i><?= $settling_lists["pagination"]->totalCount?></i>条信息</span>
    <button  class="btn bg-main settlement-all fr" url="<?= Url::to(['/settle-manage/settle/all-settle-order'])?>">全部结算</button>
</div>
<div class="task-table">
    <div class="task-tb-info table-wrap table">
        <table>
            <thead>
            <tr>
                <th width="50"><input type="checkbox" class="all-select"></th>
                <th width="50">序号</th>
                <th width="150">用户手机号</th>
                <th width="150" class="sort">最近结算时间<span><i class="up"></i><i class="down down-choosed"></i></span></th>
                <th width="120">总结算订单</th>
                <th width="120">总结算金额</th>
                <th width="120">待收款订单</th>
                <th width="150" class="sort">待收款金额<span><i class="up"></i><i class="down"></i></span></th>
                <th width="150">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($settling_lists["list"])):?>
            <?php $i = 1;?>
            <?php foreach ($settling_lists["list"] as $key => $value):?>
            <tr>
                <td><input type="checkbox" class="order-checked" name="dist_exec_id[]" value="<?= $value['id']?>"></td>
                <td><?= $i?></td>
                <td><?= $value["phone"]?></td>
                <td><?= $value["recent_settle_time"]?></td>
                <td><?= $value["total_settle_order_number"]?></td>
                <td><?= $value["total_settlement"]?></td>
                <td><?= $value["wait_order_number"]?></td>
                <td><?= $value["wait_revenue"]?></td>
                <td class="operate">
                    <a class="unit-settlement-all" href="javascript:;" url="<?= Url::to(['/settle-manage/settle/all-settle-order'])?>">用户结算</a>
                    <a href="<?= Url::to(['/settle-manage/settle/order-index','executor_uuid'=>$value['executor_uuid'],'phone'=>$value['phone']])?>" target="_blank">订单</a>
                </td>
            </tr>
            <?php $i++; endforeach;?>
            <?php endif;?>
            </tbody>
        </table>
        <?php if(empty($settling_lists["list"])):?>
        <div class="no-task">暂无结算信息</div>
        <?php endif;?>
    </div>
    <div class="page-wb system_page clearfix" data-value="20287">
        <?php
        if(isset($ser_filter) && !empty($ser_filter)){
            $pageParams = [
                'pagination' => $settling_lists['pagination'],
                "ser_filter" =>$ser_filter,
            ];
        }else{
            $pageParams = [
                'pagination' => $settling_lists['pagination'],
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