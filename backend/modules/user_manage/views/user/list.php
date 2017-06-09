<?php
use yii\helpers\Url;
use common\widgets\pagination\AjaxLinkPager;
use backend\modules\user_manage\models\DistributorExecutorMap;
?>
<p class="total-account">共 <span class="color-table-ele"><?= $user_lists["pagination"]->totalCount?></span> 条信息</p>
<div class="task-table">
    <div class="task-tb-info table-wrap table">
        <table>
            <thead>
            <tr>
                <th width="70">序号</th>
                <th width="130">手机号</th>
                <th width="150" class="register-time-order sort">注册时间
                    <span>
                        <i class="up <?= $orderBy == DistributorExecutorMap::RegisterTimeAsc ? 'up-choosed' : '' ?>" sort="<?= DistributorExecutorMap::RegisterTimeAsc?>" ></i>
                        <i class="down <?= $orderBy == DistributorExecutorMap::RegisterTimeDesc ? 'down-choosed' : '' ?>" sort="<?= DistributorExecutorMap::RegisterTimeDesc?>"></i>
                    </span>
                </th>
                <th width="130" class="total-revenue-order sort">总收入
                    <span>
                        <i class="up <?= $orderBy == DistributorExecutorMap::TotalRevenueAsc ? 'up-choosed' : '' ?>" sort="<?= DistributorExecutorMap::TotalRevenueAsc?>" ></i>
                        <i class="down <?= $orderBy == DistributorExecutorMap::TotalRevenueDesc ? 'down-choosed' : '' ?>" sort="<?= DistributorExecutorMap::TotalRevenueDesc?>" ></i>
                    </span>
                </th>
                <th width="130" class="received-revenue-order sort">已收款
                    <span>
                        <i class="up <?= $orderBy == DistributorExecutorMap::ReceivedRevenueAsc ? 'up-choosed' : '' ?>" sort="<?= DistributorExecutorMap::ReceivedRevenueAsc?>" ></i>
                        <i class="down <?= $orderBy == DistributorExecutorMap::ReceivedRevenueDesc ? 'down-choosed' : '' ?>" sort="<?= DistributorExecutorMap::ReceivedRevenueDesc?>" ></i>
                    </span>
                </th>
                <th width="130" class="wait-revenue-order sort">待收款
                    <span>
                        <i class="up <?= $orderBy == DistributorExecutorMap::WaitRevenueAsc ? 'up-choosed' : '' ?>" sort="<?= DistributorExecutorMap::WaitRevenueAsc?>" ></i>
                        <i class="down <?= $orderBy == DistributorExecutorMap::WaitRevenueDesc ? 'down-choosed' : '' ?>" sort="<?= DistributorExecutorMap::WaitRevenueDesc?>" ></i>
                    </span>
                </th>
                <th width="110">收款人</th>
                <th width="70">状态</th>
                <th width="128">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($user_lists["list"])):?>
            <?php $i = 1;?>
            <?php foreach ($user_lists["list"] as $key => $value):?>
            <tr>
                <td><?= $i?></td>
                <td class="color-table-ele"><?= $value["phone"]?></td>
                <td><?= $value["create_time"]?></td>
                <td><a class="color-table-ele" href="#"><?= $value["total_revenue"]?></a> / <?= $value["received_order_number"] + $value["wait_order_number"] ?></td>
                <td><a class="color-table-ele" href="#"><?= $value["received_revenue"]?></a> / <?= $value["received_order_number"]?></td>
                <td><a class="color-table-ele" href="#"><?= $value["wait_revenue"]?></a> / <?= $value["wait_order_number"]?></td>
                <td><?= $value["receiver"]?></td>
                <td class="user-status-item"><?= $value["enable_cn"]?></td>
                <td class="operate">
                    <a href="<?= Url::to(['/user-manage/user/detail','user_uuid'=>$value['executor_uuid']])?>" target="_blank">详情</a>
                    <?php if($value["enable"] == DistributorExecutorMap::Enable):?>
                    <a class="blacklist" href="javascript:;" url="<?= Url::to(['/user-manage/user/pull-black','id'=>$value['id']])?>">拉黑</a>
                    <?php endif;?>
                    <?php if($value["enable"] == DistributorExecutorMap::Disable):?>
                    <a class="resume" href="javascript:;" url="<?= Url::to(['/user-manage/user/restore','id'=>$value['id']])?>">恢复</a>
                    <?php endif;?>
                </td>
            </tr>
            <?php $i++;?>
            <?php endforeach;?>
            <?php endif;?>
            </tbody>
        </table>
        <??>
        <?php if(empty($user_lists["list"])):?>
        <div class="no-task show">暂无用户</div>
        <?php endif;?>
    </div>
    <div class="page-wb system_page clearfix" data-value="20287">
        <?php
            if(isset($ser_filter) && !empty($ser_filter)){
                $pageParams = [
                    'pagination' => $user_lists['pagination'],
                    "ser_filter" =>$ser_filter,
                ];
            }else{
                $pageParams = [
                    'pagination' => $user_lists['pagination'],
                ];
            }
            $pageParams["prevPageLabel"] = '上一页';
            $pageParams["nextPageLabel"] = '下一页';
            $pageParams["firstPageLabel"] = '首页';
            $pageParams["lastPageLabel"] = '尾页';
        ?>
        <?= AjaxLinkPager::widget($pageParams); ?>
    </div>
</div>