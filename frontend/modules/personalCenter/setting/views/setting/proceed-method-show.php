<div class="card-show">
    <h3>收款方式</h3>
    <ul class="">
        <?php $finance = json_decode(Yii::$app->session["user_information"]["finance_information"],true)?>
        <li><span>账户银行：</span><span><?= $finance["bank_name_opening"]?></span></li>
        <li><span>账户卡号：</span><span><?= $finance["bank_card_num"]?></span></li>
        <li><span>账号姓名：</span><span><?= $finance["bank_card_name"]?></span></li>
    </ul>
    <button class="bind-btn amend-card-btn" data-toggle="modal" data-target="#bind-card">修改</button>
</div>