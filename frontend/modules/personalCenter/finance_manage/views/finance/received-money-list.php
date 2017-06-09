<?php
use common\widgets\pagination\AjaxLinkPager;
use yii\helpers\Url;
use frontend\modules\personalCenter\finance_manage\models\Finance;
?>
<div class="collection-record-bot bg-fff">
    <?php if(!empty($finance_lists["list"])):?>
    <?php foreach ($finance_lists["list"] as $value):?>
    <div class="collection-item">
        <div class="collection-item-show">
            <ul class="collection-item-l fl">
                <li class="dot <?= $value['see_status'] == Finance::SeeStatus ? 'light' : '' ?>"></li>
                <li><?= $value["paid_time"]?></li>
                <li>收款金额: <span><?= $value["money"]?></span></li>
                <li>任务数量: <span><?= $value["number_of_order"]?></span></li>
            </ul>
            <div class="collection-item-r fr">
                <a class="paying-voucher" href="#" data-target="#paying-voucher" data-toggle="modal" url="<?= Url::to(['/personal-center/finance-manage/finance/get-payment-info','finance_id'=>$value['finance_id'],'paid_status'=>$value['paid_status']])?>">收款凭证</a>
                <a class="task-relevant" href="javascript:;" url="<?= Url::to(['/personal-center/finance-manage/finance/get-payment-record','finance_uuid'=>$value['finance_uuid']])?>">相关任务<i></i></a>
            </div>
        </div>
        <div class="collection-item-hide task-list-con">

        </div>
    </div>
    <?php endforeach;?>
    <?php endif;?>
</div>
<?php if(empty($finance_lists["list"])):?>
<div class="no-collection-record show">暂无收款记录</div>
<?php endif;?>
<div class="page-wb system_page clearfix" data-value="20287">
    <?php
    if(isset($ser_filter) && !empty($ser_filter)){
        $pageParams = [
            'pagination' => $finance_lists['pagination'],
            "ser_filter" =>$ser_filter,
        ];
    }else{
        $pageParams = [
            'pagination' => $finance_lists['pagination'],
        ];
    }
    $pageParams["prevPageLabel"] = '上一页';
    $pageParams["nextPageLabel"] = '下一页';
    $pageParams["firstPageLabel"] = '首页';
    $pageParams["lastPageLabel"] = '尾页';
    ?>
    <?= AjaxLinkPager::widget($pageParams);?>
</div>