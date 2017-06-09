<?php
use common\widgets\pagination\AjaxLinkPager;
use yii\helpers\Url;

?>
<div class="task-table">
    <div class="task-tb-info table-wrap table">
        <?php if(!empty($paying_settled_lists["list"])):?>
        <table>
            <thead>
            <tr>
                <th width="100">结算单号</th>
                <th width="100">用户手机号</th>
                <th width="100">付款金额</th>
                <th width="90">任务数量</th>
                <th width="100">结算时间</th>
                <th width="215">收款银行</th>
                <th width="150">收款账号</th>
                <th width="80">收款人</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($paying_settled_lists["list"])):?>
                <?php foreach ($paying_settled_lists["list"] as $value):?>
                    <tr>
                        <td><?= $value["ser_number"]?></td>
                        <td><?= $value["received_phone"]?></td>
                        <td><?= $value["money"]?></td>
                        <td><?= $value["number_of_order"]?></td>
                        <td><?= $value["create_time"]?></td>
                        <td class="plain-text-length-limit" data-limit="16"><?= $value["bank_of_deposit"]?></td>
                        <td><?= $value["received_account"]?></td>
                        <td><?= $value["received_name"]?></td>
                        <td class="operate">
                            <a href="<?= Url::to(['/fin-manage/finance/payment-detail','finance_id'=>$value['id']])?>" target="_blank">详情</a>
                            <a href="#" data-toggle="modal" data-target="#modal-payment" class="get-payment-info" url="<?= Url::to(['/fin-manage/finance/get-payment-info','finance_id'=>$value['id'],'paid_status'=>$value['paid_status']])?>" finance_id="<?= $value['id']?>">付款</a>
                        </td>
                    </tr>
                <?php endforeach;?>
            <?php endif;?>
            </tbody>
        </table>
        <?php else:?>
        <div class="no-task show">暂无付款信息</div>
        <?php endif;?>
    </div>
    <div class="page-wb system_page clearfix" data-value="20287">
        <?php
        if(isset($ser_filter) && !empty($ser_filter)){
            $pageParams = [
                'pagination' => $paying_settled_lists['pagination'],
                "ser_filter" =>$ser_filter,
            ];
        }else{
            $pageParams = [
                'pagination' => $paying_settled_lists['pagination'],
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