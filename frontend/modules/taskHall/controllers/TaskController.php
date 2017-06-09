<?php
/**
 * Created by PhpStorm
 * USER: dashe
 * Date: 2017/3/16
 */

namespace frontend\modules\taskHall\controllers;

use frontend\modules\taskHall\models\DistExecMap;
use Yii;
use yii\helpers\Url;
use common\helpers\CompressHtml;
use frontend\controllers\BaseAppController;
use frontend\modules\taskHall\models\Task;
use frontend\modules\taskHall\models\ExecutorTaskMap;

class TaskController extends BaseAppController
{
    public $layout = '//main';


    public function actionTaskHall()
    {
        $task = new Task;
        $task_lists = $task->getTaskLists();
        if(!Yii::$app->getRequest()->isAjax) {
            return $this->render("index",[
                "task_lists" => $task_lists,
            ]);
        }
        return CompressHtml::compressHtml($this->renderPartial('list', [
            'task_lists'=>$task_lists,

        ]));
    }

    /**
     * 筛选后的列表
     */
    public function actionTaskListFilter()
    {
        if(Yii::$app->request->isPost) {
            $filter = Yii::$app->request->post('ListFilterForm');
        } else {
            $ser_filter = Yii::$app->request->get('ser_filter');
            if(empty($ser_filter)) {
                return $this->redirect(['index']);
            }
            $filter = unserialize($ser_filter);
        }
        $task = new Task();
        $task->clearEmptyField($filter);
        $task_lists = $task->getFilterTaskLists($filter);

        return CompressHtml::compressHtml($this->renderPartial('list',[
            'task_lists'=>$task_lists,
            'ser_filter'=>serialize($filter),
        ]));
    }

    /**
     * 任务详情页
     * @return string
     */
    public function actionTaskDetail()
    {
        $task = new Task;
        $task_detail = $task->getTaskRecord(Yii::$app->request->get("uuid"));
        if(empty($task_detail)){
            return $this->redirect(Url::to(["/site/error"]));
        }
        return $this->render("detail",[
            "task_detail" => $task_detail,
        ]);
    }

    /**
     * 检测是否可以领取任务 和　是否　已经领取过　任务
     * @return string
     */
    public function actionIsGettingTask()
    {
        if(Yii::$app->request->get("task_uuid") && empty(Yii::$app->request->get("task_uuid"))){
            return json_encode(['code'=>1,"message"=>'任务错误']);
        }

        $executor_uuid = Yii::$app->user->identity->uuid;
        $distributor_uuid = Yii::$app->request->get("distributor_uuid");
        $enable = (new DistExecMap)->isEnable($executor_uuid,$distributor_uuid);
        if($enable){
            return json_encode(['code'=>5,"message"=>'系统繁忙，请稍后再试']);
        }

        $task = new Task;
        $task_uuid = Yii::$app->request->get("task_uuid");
        $task_getting = $task->isTaskEnableGetting($task_uuid);
        if(!$task_getting){
            return json_encode(['code'=>2,"message"=>'任务已结束']);
        }

        $my_method = Yii::$app->request->get("method");
        if($my_method != 'check' && $my_method != 'getting'){
            return json_encode(['code'=>5,"message"=>'系统繁忙，请稍后再试']);
        }else if($my_method == 'check'){
            return json_encode(["code"=>0,"max_get_num"=>$task_getting["remain_num"],"my_method"=>"getting","url"=>Url::to(["/task-hall/task/is-getting-task","task_uuid"=>$task_uuid,"distributor_uuid"=>$distributor_uuid,"method"=>"getting"])]);
        }

        $formData = array();
        $formData['uuid'] = $task_uuid;
        $formData["dist"]["executor_uuid"] = $executor_uuid;
        $formData["dist"]["distributor_uuid"] = $distributor_uuid;
        $formData["get_task_num"] = Yii::$app->request->get("get_task_num");
        if(!is_numeric($formData["get_task_num"])){
            return json_encode(["code"=>6,"message"=>"任务数量出错"]);
        }
        $flag = $task->myGettingTask($formData);
        if($flag === true){
            return json_encode(["code"=>0,"message"=>"领取任务成功!"]);
        }
        return json_encode(["code"=>5,"message"=>"领取任务失败"]);
    }

    public function actionCheckQualificationSelf()
    {
        if(Yii::$app->request->get("task_uuid") && empty(Yii::$app->request->get("task_uuid"))){
            return json_encode(['code'=>1,"message"=>'任务错误']);
        }
        $task_uuid = Yii::$app->request->get("task_uuid");
        $dist_id = Yii::$app->request->get("dist_id");
        $executor_uuid = Yii::$app->user->identity->uuid;
        $task_map = new ExecutorTaskMap;
        $task_map_num =  $task_map->isExistRecord($task_uuid,$executor_uuid);
        if($task_map_num == 1){
            return json_encode(['code'=>2,"message"=>"任务已领取，请到任务管理页面查看"]);
        }
        return json_encode(["code"=>0,"url"=>Url::to(["/task-hall/task/is-getting-task","task_uuid"=>$task_uuid,"dist_id"=>$dist_id])]);
    }


}