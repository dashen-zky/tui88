<?php
use common\widgets\pagination\AjaxLinkPager;
use yii\helpers\Url;

?>
<div class="total-info clearfix">
    <span>共 <i><?= $order_lists["pagination"]->totalCount?></i> 条信息</span>
    <div class="fr">
        <span>已选择 <i class="order-num">0</i> 个订单，总金额为￥<i class="total-num">0.00</i></span>
        <button class="btn-settlement btn bg-main" type="button" url="<?= Url::to(['/settle-manage/settle/settle-order-some','user_uuid'=>Yii::$app->request->get('executor_uuid'),'phone'=>Yii::$app->request->get('phone')])?>" >结算</button>
    </div>
</div>
<div class="task-table">
    <div class="task-tb-info table-wrap table">
        <table>
            <thead>
            <tr>
                <th width="80"><input type="checkbox" class="all-select"></th>
                <th width="150">订单编号</th>
                <th width="320">任务名称</th>
                <th width="150">确认时间</th>
                <th width="150">接取金额</th>
                <th width="150">确认金额</th>
                <th width="150">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($order_lists["list"])):?>
            <?php foreach($order_lists["list"] as $key => $value):?>
            <tr>
                <td><input type="checkbox" class="order-checked" name="OrderId[]" value="<?= $value['id'] ?>"></td>
                <td><?= $value["serial_number"]?></td>
                <td><a class="plain-text-length-limit" data-limit="20" href="<?= Url::to(['/task-manage/task/detail','uuid'=>$value['uuid']])?>" target="_blank"><?= $value["title"]?></a></td>
                <td><?= $value["insure_time_cn"]?></td>
                <td><?= $value["unit_money"]?></td>
                <td class="insure_money"><?= $value["insure_money"]?></td>
                <td class="operate">
                    <a href="<?= Url::to(['/order-manage/order/executing-detail','id'=>$value['id']])?>" target="_blank">详情</a>
                </td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
            </tbody>
        </table>
        <?php if(empty($order_lists["list"])):?>
        <div class="no-task">暂无订单</div>
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
</div>