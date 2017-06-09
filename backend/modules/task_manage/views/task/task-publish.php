<?php
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

AppAsset::register($this);

$this->title = '任务发布';
?>
<?php AppAsset::addCss($this,"@web/dep/css/jquery.datetimepicker.css"); ?>
<?php AppAsset::addCss($this,'@web/dep/ueditor/themes/default/css/ueditor.css') ?>
<?php AppAsset::addCss($this,'@web/dep/ueditor/third-party/codemirror/codemirror.css') ?>
<?php AppAsset::addCss($this,'@web/src/css/reset.css?v=' . Yii::$app->params['static_version']) ?>
<?php AppAsset::addCss($this,'@web/src/css/site-stage-common.css?v=' . Yii::$app->params['static_version']); ?>
<?php AppAsset::addCss($this,"@web/src/css/task-manage/task-publish.css?v=" . Yii::$app->params['static_version'])?>

<div class="content-r fl">
    <?php $form = ActiveForm::begin([
            'id' => 'task-publish-form',
    ])?>
    <input type="hidden" name="TaskPublishForm[uuid]" value="<?= isset($model) ? $model['uuid']: ''?>">
        <div class="section">
            <h4>任务基础设置</h4>
            <div class="con-detail task-base-setting clearfix">
                <div class="fill-area receive-time fl">
                    <span class="title">任务领取时间:</span>
                    <?= Html::input("text","TaskPublishForm[start_getting_time]",isset($model['start_getting_time_cn'])? $model['start_getting_time_cn']: '',["class"=>"input-section text-input datetimepicker","placeholder"=>"请选择开始时间"])?>
                    <span class="line"></span>
                    <?= Html::input("text","TaskPublishForm[end_getting_time]",isset($model['end_getting_time_cn'])? $model['end_getting_time_cn']: '',["class"=>"input-section text-input datetimepicker","placeholder"=>"请选择结束时间"])?>
                    <i class="start-icon"></i>
                    <i class="end-icon"></i>
                </div>
                <div class="fill-area execute-time fl">
                    <span class="title">任务执行时间:</span>
                    <?= Html::input("text","TaskPublishForm[start_execute_time]",isset($model['start_execute_time_cn'])? $model['start_execute_time_cn']: '',["class"=>"input-section text-input datetimepicker","placeholder"=>"请选择开始时间"])?>
                    <span class="line"></span>
                    <?= Html::input("text","TaskPublishForm[end_execute_time]",isset($model['end_execute_time_cn'])? $model['end_execute_time_cn']: '',["class"=>"input-section text-input datetimepicker","placeholder"=>"请选择结束时间"])?>
                    <i class="start-icon"></i>
                    <i class="end-icon"></i>
                </div>
                <div class="fill-area customer-account fl">
                    <span class="title">任务可领取人数:</span>
                    <?= Html::input("text","TaskPublishForm[limit]",isset($model['limit'])? $model['limit']: '')?>
                </div>
                <div class="fill-area task-unit-price fl">
                    <span class="title">任务单价:</span>
                    <?= Html::input("text","TaskPublishForm[unit_money]",isset($model['unit_money'])? $model['unit_money']: '')?> 元
                    <span class="total-price">任务总额: <i>0</i> 元</span>
                </div>
            </div>
        </div>
        <div class="section">
            <h4>任务内容设置</h4>
            <div class="con-detail task-con-setting">
                <div class="fill-area task-name">
                    <span class="title">任务名称:</span>
                    <?= Html::input("text","TaskPublishForm[title]",isset($model['title_detailed'])? $model['title_detailed']: '',["maxLength"=>"50"])?>
                    <span style="margin-left: 10px;"><i class="font-num">0</i>/50</span>
                </div>
                <div class="fill-area task-content clearfix">
                    <span class="title fl">任务内容:</span>
                    <script id="editor" type="text/plain"></script>
                    <?= Html::input("hidden","TaskPublishForm[content]",'',["class"=>"task-content-detail"]) ?>
                </div>
                <div class="fill-area check-standard clearfix">
                    <span class="title fl">验收标准:</span>
                    <?= Html::Textarea("TaskPublishForm[check_standard]",isset($model['check_standard'])? $model['check_standard']: '',["class"=>"f1","cols"=>"30","rows"=>"10"])?>
                </div>
                <div class="fill-area remark clearfix">
                    <span class="title fl">备注:</span>
                    <?= Html::Textarea("TaskPublishForm[remarks]",isset($model['remarks'])? $model['remarks']: '',["class"=>"f1","cols"=>"30","rows"=>"10"])?>
                </div>
            </div>
        </div>
        <div class="section">
            <h4>任务提交内容</h4>
            <div class="con-detail task-submit">
                <ul class="clearfix">
                    <li><label><input type="checkbox" name="TaskPublishForm[executor_information_config][]" value="id_card" <?= isset($model["executor_information_config"]) && in_array("id_card",$model["executor_information_config"])? "checked='checked'":'';?> >身份证 </label><br />
                        <label class="unique-config"><input type="checkbox" name="TaskPublishForm[unique_config][]" value="id_card">唯一</label>
                    </li>
                    <li><label><input type="checkbox" name="TaskPublishForm[executor_information_config][]" value="phone" <?= isset($model["executor_information_config"]) && in_array("phone",$model["executor_information_config"])? "checked='checked'":'';?> >手机号</label><br />
                        <label class="unique-config"><input type="checkbox" name="TaskPublishForm[unique_config][]" value="phone">唯一</label>
                    </li>
                    <li><label><input type="checkbox" name="TaskPublishForm[executor_information_config][]" value="register_account" <?= isset($model["executor_information_config"]) && in_array("register_account",$model["executor_information_config"])? "checked='checked'":'';?>>注册账号</label><br />
                        <label class="unique-config"><input type="checkbox" name="TaskPublishForm[unique_config][]" value="register_account">唯一</label>
                    </li>
                    <li><label><input type="checkbox" name="TaskPublishForm[executor_information_config][]" value="screen_shots" <?= isset($model["executor_information_config"]) && in_array('screen_shots',$model["executor_information_config"])? "checked='checked'":'';?> >截图</label><br />
                        <label class="unique-config"><input type="checkbox" name="TaskPublishForm[unique_config][]" value="screen_shots">唯一</label>
                    </li>
                </ul>
            </div>
        </div>
        <div class="btn-section">
            <button type="button" class="save-draft btn submit" url="<?= Url::to(["/task-manage/task/save-draft"])?>" value="2">保存草稿</button>
            <button type="button" class="publish btn submit" url="<?= Url::to(['/task-manage/task/publish'])?>" validateUrl="<?= Url::to(['/task-manage/task/validate-publish'])?>" value="1">发布</button>
            <?= Html::input("hidden","TaskPublishForm[distribute_status]") ?>
        </div>
    <?php ActiveForm::end()?>
</div>

<?php AppAsset::addScript($this,"@web/dep/ueditor/ueditor.config.js"); ?>
<?php AppAsset::addScript($this,"@web/dep/ueditor/ueditor.all.js"); ?>
<?php AppAsset::addScript($this,"@web/dep/ueditor/third-party/codemirror/codemirror.js"); ?>
<?php AppAsset::addScript($this,"@web/dep/ueditor/lang/zh-cn/zh-cn.js"); ?>
<?php AppAsset::addScript($this,"@web/dep/ueditor/third-party/zeroclipboard/ZeroClipboard.js"); ?>
<?php AppAsset::addScript($this,"@web/src/js/task-manage/task-publish.js?v=" . Yii::$app->params['static_version']) ?>
