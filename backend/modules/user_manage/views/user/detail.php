<?php
use backend\assets\AppAsset;

$this->title = "用户详情";
?>
<?php AppAsset::addCss($this,'@web/src/css/reset.css?v=' . Yii::$app->params['static_version']) ?>
<?php AppAsset::addCss($this,'@web/src/css/site-stage-common.css?v=' . Yii::$app->params['static_version']); ?>
<?php AppAsset::addCss($this,'@web/src/css/user-manage/user-detail.css?v=' . Yii::$app->params['static_version']); ?>

<div class="content-wrap clearfix">
    <div class="content">
        <!-- 面包屑 -->
        <div class="bread">
            <ol class="breadcrumb font-500">
                <li><a href="#">首页</a></li>
                <li><a href="#">用户管理</a></li>
                <li class="active">用户详情</li>
            </ol>
        </div>
        <h4>基础信息</h4>
        <div class="info base-info">
            <ul class="clearfix">
                <li>
                    <span>手机号 :</span>
                    <span><?= $user_info_detail["phone"]?></span>
                </li>
                <li>
                    <span>联系人 :</span>
                    <span><?= $user_info_detail["contact"]?></span>
                </li>
                <li>
                    <span>注册时间 :</span>
                    <span><?= $user_info_detail["create_time"]?></span>
                </li>
            </ul>
            <ul class="clearfix">
                <li>
                    <span>昵称 :</span>
                    <span><?= $user_info_detail["nick_name"]?></span>
                </li>
                <li>
                    <span>微信号 :</span>
                    <span><?= $user_info_detail["wechat"]?></span>
                </li>
                <li>
                    <span>Q Q :</span>
                    <span><?= $user_info_detail["qq"]?></span>
                </li>
            </ul>
            <ul class="clearfix">
                <li>
                    <span>所在地 :</span>
                    <span><?= $user_info_detail["location"]?></span>
                </li>
                <li>
                    <span>状态 :</span>
                    <span><?= $user_info_detail["enable"]?></span>
                </li>
            </ul>
        </div>
        <h4>收款信息</h4>
        <div class="info collection-info">
            <ul class="clearfix">
                <li>
                    <span>收款人 :</span>
                    <span><?= $user_info_detail["finance_information"]["bank_card_name"]?></span>
                </li>
                <li>
                    <span>收款银行 :</span>
                    <span><?= $user_info_detail["finance_information"]["bank_name_opening"]?></span>
                </li>
                <li>
                    <span>收款账号 :</span>
                    <span><?= str_replace(" ","",$user_info_detail["finance_information"]["bank_card_num"])?></span>
                </li>
            </ul>
        </div>
    </div>
</div>