<?php
use frontend\assets\AppAsset;
use frontend\modules\taskHall\models\ExecutorTaskMap;

$this->title = "执行详情";

AppAsset::addCss($this,'@web/src/css/site-stage-common.css?v=' . Yii::$app->params['static_version']);
AppAsset::addCss($this,"@web/src/css/order-manage/execute-detail.css?v=" . Yii::$app->params['static_version']);
?>
<!-- 主要内容部分-->
<div class="main clearfix">
    <!-- 面包屑 -->
    <div class="bread">
        <ol class="breadcrumb font-500">
            <li><a href="#">首页</a></li>
            <li><a href="#">订单管理</a></li>
            <li class="active">执行详情</li>
        </ol>
    </div>
    <div class="main-con-wrap">
        <div class="section-top section">
            <div class="exe-status">当前执行状态：<span><?= $detail["status_cn"]?></span><i><?php if($detail["status"] == ExecutorTaskMap::ExecutingStatusTerminated):?>很抱歉！该任务由于特殊原因已被终止<?php endif;?></i></div>
            <div class="tips-info">
                <h4>提交信息</h4>
                <ul class="clearfix">
                    <?php if(isset($detail["executor_information_config"]["id_card"])):?>
                        <li>身份证：<span><?= isset($detail["submit_evidence"]["id_card"]) ? $detail["submit_evidence"]["id_card"] : '--'?></span></li>
                    <?php endif;?>

                    <?php if(isset($detail["executor_information_config"]["phone"])):?>
                        <li>手机号：<span><?= isset($detail["submit_evidence"]["phone"]) ? $detail["submit_evidence"]["phone"] : '--'?></span></li>
                    <?php endif;?>

                    <?php if(isset($detail["executor_information_config"]["register_account"])):?>
                        <li>注册账号：<span><?= isset($detail["submit_evidence"]["register_account"]) ? $detail["submit_evidence"]["register_account"] : '--'?></span></li>
                    <?php endif;?>
                </ul>
                <div class="screenshot clearfix">
                    <span class="title fl">截图：</span>
                    <div class="fl">
                        <?php if(!empty($detail["screen_shots"])) :?>
                            <?php foreach ($detail["screen_shots"] as $value) :?>
                                <img src="<?= $value?>" alt="">
                            <?php endforeach;?>
                        <?php else:?>
                            <span>--</span>
                        <?php endif;?>
                    </div>
                </div>
                <?php if(isset($detail["submit_evidence"]["message"])):?>
                    <div class="message">
                        <span class="title">留言：</span><span><?= $detail["submit_evidence"]["message"]?></span>
                    </div>
                <?php endif;?>
            </div>
            <?php if(!empty($detail["check_remarks"])):?>
                <div class="check-message">
                    <h4>审核留言</h4>
                    <div><?= $detail["check_remarks"]?></div>
                </div>
            <?php endif;?>
        </div>
        <div class="section-bottom section">
            <div class="exe-info">
                <div class="exe-info-top">
                    <h4>执行信息</h4>
                    <ul class="clearfix">
                        <li>创建时间：<span><?= $detail["create_time_cn"]?></span></li>
                        <li>提交时间：<span><?= $detail["submit_evidence_time_cn"]?></span></li>
                        <li>确认时间：<span><?= $detail["insure_time_cn"]?></span></li>
                        <li>收款时间：<span><?= $detail["received_money_time_cn"]?></span></li>
                    </ul>
                </div>
                <div class="exe-info-bot clearfix">
                    <div class="exe-bot-l fl">
                        <div class="info-group">
                            <span>任务编号：</span>
                            <span><?= $detail["serial_number"]?></span>
                        </div>
                        <div class="info-group">
                            <span>确认金额：</span>
                            <span class="color-main"><?= $detail["insure_money"]?></span>
                        </div>
                        <div class="task-name info-group">
                            <span>任务名称：</span>
                            <span><?= $detail["title"]?></span>
                        </div>
                        <div class="info-group">
                            <span>任务金额：</span>
                            <span class="color-main"><?= $detail["unit_money"]?></span>
                        </div>
                        <div class="info-group">
                            <span>任务总量：</span>
                            <span><?= $detail["limit"]?></span>
                        </div>
                        <div class="info-group">
                            <span>领取状态：</span>
                            <span><?= $detail["getting_status_cn"]?></span>
                        </div>
                        <div class="info-group">
                            <span>发布时间：</span>
                            <span><?= $detail["distribute_time_cn"]?></span>
                        </div>
                        <div class="info-group">
                            <span>领取时间：</span>
                            <span><?= $detail["start_getting_time_cn"]?> 至 <?= $detail["end_getting_time_cn"]?></span>
                        </div>
                        <div class="info-group">
                            <span>执行时间：</span>
                            <span><?= $detail["start_execute_time_cn"]?> 至 <?= $detail["end_execute_time_cn"]?></span>
                        </div>
                    </div>
                    <div class="exe-bot-r fl">
                        <div class="info-group clearfix">
                            <span class="title fl">提交内容：</span>
                            <div class="submit-con fl">
                                <?php if(isset($detail["executor_information_config"]["id_card"])):?>身份证<?php endif;?> &nbsp;
                                <?php if(isset($detail["executor_information_config"]["phone"])):?>手机号<?php endif;?> &nbsp;
                                <?php if(isset($detail["executor_information_config"]["register_account"])):?>注册账号<?php endif;?> &nbsp;
                                <?php if(isset($detail["executor_information_config"]["screen_shots"])):?>截图<?php endif;?> &nbsp;
                                留言 &nbsp;
                            </div>
                        </div>
                        <div class="info-group">
                            <span class="title">任务内容：</span>
                            <span><?= $detail["content"]?></span>
                        </div>
                        <div class="info-group">
                            <span class="title">验收标准：</span>
                            <span><?= $detail["check_standard"]?></span>
                        </div>
                        <?php if(!empty($detail["remarks"])):?>
                        <div class="info-group">
                            <span class="title">备注：</span>
                            <span><?= $detail["remarks"]?></span>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>