<?php
use yii\helpers\Url;

?>
<?php $this->beginContent('@app/views/layouts/main.php');?>
<!-- 主要内容部分-->
<div class="content-wrap clearfix">
    <div class="sidebar fl">
        <ul class="menu">
            <li class="user-manage <?= $this->context->module->id == 'user-manage'? 'current-side-nav':''?>">
                <a class="first-nav" href="<?= Url::to(['/user-manage/user/user-list'])?>"><i></i><span>用户管理</span></a>
            </li>
            <li class="task-manage <?= $this->context->module->id == 'task-manage'? 'current-side-nav':''?>">
                <a class="first-nav" href="javascript:;"><i></i><span>任务管理</span><em></em></a>
                <div class="menu-list">
                    <a href="<?= Url::to(['/task-manage/task/list'])?>" <?php if($this->context->action->id == 'list' && $this->context->id == 'task'):?>class="current-list-nav" <?php endif;?>>任务列表</a>
                    <a href="<?= Url::to(['/task-manage/task/publish'])?>" <?php if($this->context->action->id == 'publish' && $this->context->id == 'task'):?>class="current-list-nav" <?php endif;?>>任务发布</a>
                </div>
            </li>
            <li class="order-manage <?= $this->context->module->id == 'order-manage'? 'current-side-nav':''?>">
                <a class="first-nav" href="<?= Url::to(['/order-manage/order/index'])?>"><i></i><span>订单管理</span></a>
            </li>
            <li class="settle-manage <?= $this->context->module->id == 'settle-manage'? 'current-side-nav':''?>">
                <a class="first-nav" href="javascript:;"><i></i><span>结算管理</span><em></em></a>
                <div class="menu-list">
                    <a href="<?= Url::to(['/settle-manage/settle/settling-index'])?>" <?php if($this->context->action->id == 'settling-index' && $this->context->id == 'settle'):?>class="current-list-nav" <?php endif;?>>结算</a>
                    <a href="<?= Url::to(['/settle-manage/settle/settled-index'])?>" <?php if($this->context->action->id == 'settled-index' && $this->context->id == 'settle'):?>class="current-list-nav" <?php endif;?>>结算记录</a>
                </div>
            </li>
            <li class="fin-manage <?= $this->context->module->id == 'fin-manage'? 'current-side-nav':''?>"">
                <a class="first-nav" href="javascript:;"><i></i><span>财务管理</span><em></em></a>
                <div class="menu-list">
                    <a href="<?= Url::to(['/fin-manage/finance/paying-settled-index'])?>" <?php if($this->context->action->id == 'paying-settled-index' && $this->context->id == 'finance'):?>class="current-list-nav" <?php endif;?>>付款</a>
                    <a href="<?= Url::to(['/fin-manage/finance/paid-settled-index'])?>" <?php if($this->context->action->id == 'paid-settled-index' && $this->context->id == 'finance'):?>class="current-list-nav" <?php endif;?>>付款记录</a>
                </div>
            </li>
        </ul>
    </div>
    <?= $content ?>
</div>

<?php $this->endContent();?>
