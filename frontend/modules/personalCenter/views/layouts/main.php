<?php
use yii\helpers\Url;

?>
<?php $this->beginContent('@app/views/layouts/main.php');?>
<!-- 主要内容部分-->
<div class="main clearfix">
    <div class="sidebar fl">
        <h2 class="font-18" style="display: none;">推客中心</h2>
        <ul class="menu">
            <li class="<?= $this->context->menu == 'task'?'current-side-nav':''?>">
                <a class="first-nav" href="<?= Url::to(["/personal-center/task-manage/task/index"])?>"><i></i><span>任务管理</span><em>0</em></a>
            </li>
            <li class="personal-setting <?= $this->context->menu == 'setting' ? 'current-side-nav' : ''?>">
                <a class="first-nav" href="javascript:;"><i></i><span>个人设置</span><em></em></a>
                <div class="menu-list">
                    <a href="<?= Url::to(["/personal-center/setting/setting/base-info"])?>" class="<?= isset( $this->context->active) && $this->context->active == 'base_info' ? 'current-list-nav': '';?>">基础信息</a>
                    <a href="<?= Url::to(["/personal-center/setting/setting/amend-psd"])?>" class="<?= isset( $this->context->active) && $this->context->active == 'amend-psd' ? 'current-list-nav': '';?>">修改登录密码</a>
                    <a href="<?= Url::to(["/personal-center/setting/setting/proceed-method"])?>" class="<?= isset( $this->context->active) && $this->context->active == 'proceed' ? 'current-list-nav': '';?>">收款方式</a>
                </div>
            </li>
            <li class="<?= $this->context->menu == 'finance'?'current-side-nav':''?>">
                <a class="first-nav" href="<?= Url::to(['/personal-center/finance-manage/finance/index'])?>"><i></i><span>财务管理</span></a>
            </li>
        </ul>
    </div>
    <?= $content ?>
</div>
<?php $this->endContent();?>

