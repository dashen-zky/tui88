<table>
    <thead>
    <tr>
        <th width="200">任务编号</th>
        <th width="500">任务名称</th>
        <th width="300">确认时间</th>
        <th width="150">任务金额</th>
        <th width="150">确认金额</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($task_map_list as $value): ?>
    <tr>
        <td><?= $value["serial_number"]?></td>
        <td><a class="plain-text-length-limit" data-limit="18" href="<?= \yii\helpers\Url::to(['/task-hall/task/task-detail','uuid'=>$value['task_uuid']])?>" target="_blank"><?= $value["title"]?></a></td>
        <td><?= $value["insure_time_cn"]?></td>
        <td><?= $value["unit_money"]?></td>
        <td><?= $value["insure_money"]?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div class="page-change">
    <span class="prev">上一页</span>
    <span class="next">下一页</span>
</div>