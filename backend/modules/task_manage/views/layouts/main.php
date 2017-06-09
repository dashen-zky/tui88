<?php
use yii\helpers\Url;


?>
<?php $this->beginContent('@app/views/layouts/main.php');?>
<!-- 主要内容部分-->
<div class="content-wrap clearfix">
    <div class="sidebar fl">
        <ul class="menu">
            <li class="user-manage">
                <a class="first-nav" href="javascript:;"><i></i><span>用户管理</span><em></em></a>
                <div class="menu-list">
                    <a href="<?= Url::to(['/user-manage/user/user-list'])?>">用户列表</a>
                </div>
            </li>
            <li class="current-side-nav personal-setting">
                <a class="first-nav" href="javascript:;"><i></i><span>任务管理</span><em></em></a>
                <div class="menu-list">
                    <a href="<?= Url::to(['/task-manage/task/list'])?>" <?php if($this->context->action->id == 'list' && $this->context->action->controller->module->id == 'task-manage'):?>class="current-list-nav" <?php endif;?> >任务列表</a>
                    <a href="<?= Url::to(['/task-manage/task/publish'])?>" <?php if($this->context->action->id == 'publish' && $this->context->action->controller->module->id == 'task-manage'):?>class="current-list-nav" <?php endif;?>>任务发布</a>
                </div>
            </li>
            <li class="order-manage">
                <a class="first-nav" href="javascript:;"><i></i><span>订单管理</span><em></em></a>
                <div class="menu-list">
                    <a href="<?= Url::to(['/order-manage/order/index'])?>">订单列表</a>
                </div>
            </li>
            <li><a class="first-nav" href="#"><i></i><span>财务管理</span></a></li>
        </ul>
    </div>
    <?= $content ?>
</div>

<?php $this->endContent();?>
