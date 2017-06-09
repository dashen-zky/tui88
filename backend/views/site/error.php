<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = "页面未找到";
?>

<div class="alert alert-danger" style="text-align:center">
    <?= Html::img('@web/src/images/404.jpg', ['alt' => '页面未找到']) ?>
</div>
