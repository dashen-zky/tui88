<?php
use common\widgets\pagination\AjaxLinkPager;
use yii\helpers\Url;
?>
<div class="total-account clearfix">
    <span>共<i><?= $settled_records["pagination"]->totalCount ?></i>条信息</span>
</div>
<div class="task-table">
    <div class="task-tb-info table-wrap table">
        <?php if(!empty($settled_records["list"])):?>
        <table>
            <thead>
            <tr>
                <th width="100">序号</th>
                <th width="100">结算单号</th>
                <th width="130">用户手机号</th>
                <th width="70">总结算订单</th>
                <th width="120">总结算金额</th>
                <th width="100">订单数量</th>
                <th width="120">结算金额</th>
                <th width="150">结算时间</th>
                <th width="145">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($settled_records["list"])):?>
            <?php $i= 1;?>
            <?php foreach ($settled_records["list"] as $key => $value):?>
            <tr>
                <td><?= $i?></td>
                <td><?= $value["ser_number"]?></td>
                <td><?= $value["phone"]?></td>
                <td><?= $value["total_settle_order_number"]?></td>
                <td><?= $value["total_settlement"]?></td>
                <td><?= $value["number_of_order"]?></td>
                <td><?= $value["money"]?></td>
                <td><?= $value["create_time"]?></td>
                <td class="operate">
                    <a href="<?= Url::to(['/fin-manage/finance/payment-detail','finance_id'=>$value['finance_id']])?>" target="_blank">详情</a>
                    <?php if($value["paid_status"] == \backend\modules\fin_manage\models\Finance::PaidStatusDisable): ?>
                    <a href="javascript:;" data-toggle="modal" data-target="#proof" class="get-payment-info" url="<?= Url::to(['/fin-manage/finance/get-payment-info','finance_id'=>$value['finance_id'],'paid_status'=>$value['paid_status']]) ?>">凭证</a>
                    <?php endif;?>
                </td>
            </tr>
            <?php $i++;endforeach;?>
            <?php endif;?>
            </tbody>
        </table>
        <?php else:?>
        <div class="no-task show">暂无结算记录</div>
        <?php endif;?>
    </div>
    <div class="page-wb system_page clearfix" data-value="20287">
        <?php
        if(isset($ser_filter) && !empty($ser_filter)){
            $pageParams = [
                'pagination' => $settled_records['pagination'],
                "ser_filter" =>$ser_filter,
            ];
        }else{
            $pageParams = [
                'pagination' => $settled_records['pagination'],
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
