<?php
use frontend\assets\AppAsset;
use yii\helpers\Url;
use frontend\modules\taskHall\models\Task;

$this->title = "任务详情";
AppAsset::addCss($this,"@web/src/css/task-hall/task-manage-detail.css?v=" . Yii::$app->params['static_version']);
?>
<div class="main">
<!-- 面包屑 -->
<div class="bread">
    <ol class="breadcrumb font-500">
        <li><a href="<?= Url::to(['/site/index'])?>">首页</a></li>
        <li><a href="<?= Url::to(['/task-hall/task/task-hall'])?>">任务大厅</a></li>
        <li class="active">任务详情</li>
    </ol>
</div>
<div class="main-con">
    <div class="section">
        <div class="exe-info-wrap clearfix">
            <div class="exe-wrap-l fl">
                <div class="task-name info-group">
                    <span>任务名称：</span>
                    <span><?= $task_detail["title_cn"]?></span>
                </div>
                <div class="info-group">
                    <span>任务金额：</span>
                    <span class="color-main"><?= $task_detail["unit_money"] ?></span>
                </div>
                <div class="info-group">
                    <span>任务总量：</span>
                    <span><?= $task_detail["limit"]?></span>
                </div>
                <div class="info-group">
                    <span>领取状态：</span>
                    <span><?= $task_detail['getting_status_cn']?></span>
                </div>
                <div class="info-group">
                    <span>发布时间：</span>
                    <span><?= $task_detail["distribute_time_cn"]?></span>
                </div>
                <div class="info-group">
                    <span>领取时间：</span>
                    <span><?= $task_detail["start_getting_time_cn"]?> 至 <?= $task_detail["end_getting_time_cn"]?></span>
                </div>
                <div class="info-group">
                    <span>执行时间：</span>
                    <span><?= $task_detail["start_execute_time_cn"]?> 至 <?= $task_detail["end_execute_time_cn"]?></span>
                </div>
            </div>
            <div class="exe-wrap-r fl">
                <div class="info-group clearfix">
                    <span class="title fl">提交内容：</span>
                    <div class="submit-con fl">
                        <?php if(isset($task_detail["executor_information_config"]["id_card"])):?>身份证<?php endif;?> &nbsp;
                        <?php if(isset($task_detail["executor_information_config"]["phone"])):?>手机号<?php endif;?> &nbsp;
                        <?php if(isset($task_detail["executor_information_config"]["register_account"])):?>注册账号<?php endif;?> &nbsp;
                        <?php if(isset($task_detail["executor_information_config"]["screen_shots"])):?>截图<?php endif;?> &nbsp;
                    </div>
                </div>
                <div class="info-group">
                    <span class="title">任务内容：</span>
                    <span><?= $task_detail["content"]?></span>
                </div>
                <div class="info-group">
                    <span class="title">验收标准：</span>
                    <span><?= $task_detail["check_standard"]?></span>
                </div>
                <?php if(!empty($task_detail["remarks"])):?>
                <div class="info-group">
                    <span class="title">备注：</span>
                    <span><?= $task_detail["remarks"]?></span>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
</div>