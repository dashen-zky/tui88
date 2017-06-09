<?php
/**
 * Created by PhpStorm
 * USER: dashe
 * Date: 2017/3/13
 */

namespace backend\modules\task_manage\controllers;

use Yii;
use yii\base\Exception;
use common\helpers\CompressHtml;
use backend\controllers\BaseAppController;
use backend\modules\task_manage\models\Task;
use backend\modules\task_manage\models\TaskPublishForm;

class TaskController extends BaseAppController
{
    public $layout = '//main-login';

    public function actionList()
    {
        $task = new Task;
        $task_lists = $task->getMyTaskLists();
        if(!Yii::$app->getRequest()->isAjax){
            return $this->render("task-list",[
                "task_lists" => $task_lists,
            ]);
        }

        return CompressHtml::compressHtml($this->renderPartial('list', [
            'task_lists'=>$task_lists,
        ]));
    }

    /**
     * 发布任务的页面
     */
    public function actionPublish()
    {
        $request = Yii::$app->request;
        $task = new Task;

        $record = $task->getRecord(
            [
                'task'=>['*']
            ],
            [
                '=',
                'uuid',
                $request->get("uuid")
            ]
        );

        if($request->isPost) {
            $model = new TaskPublishForm;
            $formData = $request->post();
            $formData1 = $model->dealFormData($formData);
            if($model->load($formData1) && $model->validate()){
                $task->setScenario(Task::ScenarioPublish);
                if(empty($formData["TaskPublishForm"]["uuid"])) {
                    if ($task->insertRecord($formData["TaskPublishForm"])) {
                        return $this->redirect(['/task-manage/task/list']);
                    }
                }
                if($task->updateRecord($formData["TaskPublishForm"])){
                    return $this->redirect(['/task-manage/task/list']);
                }
            }
            throw new Exception('publish failed');
        }

        if(!$request->get("uuid") || empty($request->get("uuid"))){
            return $this->render("task-publish",[
                "model" => null,
            ]);
        }

        return $this->render("task-publish",[
            "model" => $task->dealTask($record),
        ]);
    }

    public function actionSaveDraft()
    {
        $request = Yii::$app->request;
        $formData = $request->post("TaskPublishForm");
        if(empty(trim($formData["title"]))){
            return json_encode(["code"=>1,"message"=>"请填写任务名称"]);
        }
        $task = new Task;
        $task->setScenario(Task::ScenarioDraft);

        if(empty($formData["uuid"])) {
            if ($task->insertRecord($formData)) {
                return $this->redirect(['/task-manage/task/list']);
            }
        }

        if($task->updateRecord($formData)){
            return $this->redirect(['/task-manage/task/list']);
        }

        return json_encode(["code"=>2,"message"=>"保存草稿失败"]);

    }

    public function actionValidatePublish()
    {
        $request = Yii::$app->request;
        $model = new TaskPublishForm;
        $formData = $request->post();
        $formData = $model->dealFormData($formData);
        if($model->load($formData) && $model->validate()){
            return $model->dealErrorMessage($model->getErrors());
        }
        return $model->dealErrorMessage($model->getErrors());
    }

    public function actionListFilter()
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

    public function actionDetail()
    {
        $uuid = Yii::$app->request->get("uuid");
        $task = new Task;
        $task_detail = $task->getMyTaskDetail($uuid);
        $this->layout = '//main';
        if($task_detail){
            return $this->render("detail",[
               "task_detail" => $task_detail,
            ]);
        }
        return $this->redirect(["/site/error"]);
    }

    public function actionTerminateTask()
    {
        $request = Yii::$app->request;
        if($request->get("uuid") == ''){
            return json_encode(["code"=>1,"message"=>"任务出错"]);
        }
        $formData = [];
        $formData["uuid"] = $request->get("uuid");

        $task = new Task;
        if($task->TerminateTask($formData)){
            return json_encode(["code"=>0,"message"=>"任务停止成功"]);
        }
        return json_encode(["code"=>2,"message"=>"任务停止失败"]);
    }

    public function actionDeleteTask()
    {
        if(!Yii::$app->request->get("uuid")){
            return json_encode(["code"=>1,"message"=>"任务出错"]);
        }
        $formData = [];
        $formData["uuid"] = Yii::$app->request->get("uuid");
        if(Task::find()->where($formData)->one()->delete()){
            return json_encode(["code"=>0,"message"=>"任务删除成功"]);
        }
        return json_encode(["code"=>2,"message"=>"任务删除失败"]);
    }

}