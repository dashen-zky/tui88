<?php
use common\widgets\pagination\AjaxLinkPager;
use yii\helpers\Url;
?>
<div class="total-account clearfix">
    <span>共<i><?= isset($paid_settled_records["pagination"]) ? $paid_settled_records["pagination"]->totalCount : 0?></i>条信息</span>
</div>
<div class="task-table">
    <div class="task-tb-info table-wrap table">
        <?php if(!empty($paid_settled_records["list"])):?>
        <table>
            <thead>
            <tr>
                <th width="90">结算单号</th>
                <th width="100">用户手机号</th>
                <th width="120">付款金额</th>
                <th width="80">任务数量</th>
                <th width="120">付款时间</th>
                <th width="210">收款银行</th>
                <th width="140">收款账号</th>
                <th width="80">收款人</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($paid_settled_records["list"])):?>
            <?php foreach ($paid_settled_records["list"] as $value):?>
            <tr>
                <td><?= $value["ser_number"]?></td>
                <td><?= $value["received_phone"]?></td>
                <td><?= $value["money"]?></td>
                <td><?= $value["number_of_order"]?></td>
                <td><?= $value["paid_time"]?></td>
                <td class="plain-text-length-limit" data-limit="16"><?= $value["bank_of_deposit"]?></td>
                <td><?= str_replace(" ","",$value["received_account"])?></td>
                <td><?= $value["received_name"]?></td>
                <td class="operate">
                    <a href="<?= Url::to(['/fin-manage/finance/payment-detail','finance_id'=>$value['id']])?>" target="_blank">详情</a>
                    <a href="javascript:;" data-toggle="modal" data-target="#proof" class="get-payment-info" url="<?= Url::to(['/fin-manage/finance/get-payment-info','finance_id'=>$value['id'],'paid_status'=>$value['paid_status']]) ?>">凭证</a>
                </td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
            </tbody>
        </table>
        <?php else:?>
        <div class="no-task show">暂无付款记录</div>
        <?php endif;?>
    </div>
    <div class="page-wb system_page clearfix" data-value="20287">
        <?php
        if(isset($ser_filter) && !empty($ser_filter)){
            $pageParams = [
                'pagination' => $paid_settled_records['pagination'],
                "ser_filter" =>$ser_filter,
            ];
        }else{
            $pageParams = [
                'pagination' => $paid_settled_records['pagination'],
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