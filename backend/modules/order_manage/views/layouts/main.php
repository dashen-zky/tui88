<?php
use yii\helpers\Url;

?>
<?php $this->beginContent('@app/views/layouts/main.php');?>
<!-- 主要内容部分-->
<div class="content-wrap clearfix">
    <div class="sidebar fl" style="height:960px">
        <ul class="menu">
            <li>
                <a class="first-nav" href="#"><i></i><span>用户管理</span><em></em></a>
                <div class="menu-list">
                    <a href="<?= Url::to(['/user-manage/user/user-list'])?>">用户列表</a>
                </div>
            </li>
            <li class="task-manage">
                <a class="first-nav" href="#"><i></i><span>任务管理</span><em></em></a>
                <div class="menu-list">
                    <a href="<?= Url::to(['/task-manage/task/list'])?>">任务列表</a>
                    <a href="<?= Url::to(['/task-manage/task/publish'])?>">任务发布</a>
                </div>
            </li>
            <li class="current-side-nav order-manage">
                <a class="first-nav" href="#"><i></i><span>订单管理</span><em></em></a>
                <div class="menu-list">
                    <a href="<?= Url::to(['/order-manage/order/index'])?>" class="current-list-nav">订单列表</a>
                </div>
            </li>
            <li class="settle-manage">
                <a class="first-nav" href="#"><i></i><span>结算管理</span><em></em></a>
                <div class="menu-list">
                    <a href="#" >结算</a>
                    <a href="#" >结算记录</a>
                </div>
            </li>
            <li><a class="first-nav" href="#"><i></i><span>财务管理</span></a></li>
        </ul>
    </div>
    <?= $content ?>
</div>

<?php $this->endContent();?>
