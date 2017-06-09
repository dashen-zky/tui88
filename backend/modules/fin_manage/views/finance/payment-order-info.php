<!-- 面包屑 -->
<div class="bread">
    <ol class="breadcrumb font-500">
        <li><a href="#">首页</a></li>
        <li class="active">付款详情</li>
    </ol>
</div>
<div class="main-info content-h">
    <ul class="clearfix">
        <li><span class="title">结算时间:</span> <?= $fin_info["create_time"]?></li>
        <li><span class="title">结算单号:</span> <?= $fin_info["ser_number"]?></li>
        <li><span class="title">用户手机号:</span> <?= $fin_info["received_phone"]?></li>
        <li><span class="title">付款信息:</span> <?= $fin_info["money"]?> / <?= $fin_info["number_of_order"]?></li>
        <li><span class="title">付款时间:</span> <?= $fin_info["paid_time"]?></li>
        <li><span class="title">收款人:</span> <?= $fin_info["received_name"]?></li>
        <li><span class="title">收款账号:</span> <?= $fin_info["received_account"]?></li>
        <li><span class="title">收款银行:</span> <?= $fin_info["bank_of_deposit"]?></li>
    </ul>
</div>