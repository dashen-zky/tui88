<?php

namespace frontend\modules\personalCenter\taskManager\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Response;
use common\helpers\CompressHtml;
use common\helpers\PlatformHelper;
use common\helpers\ExternalFileHelper;
use frontend\modules\taskHall\models\Task;
use frontend\modules\taskHall\models\ExecutorTaskMap;
use frontend\modules\personalCenter\models\UserInformation;
use frontend\modules\personalCenter\controllers\BaseController;
use frontend\modules\personalCenter\taskManager\models\SubmitTaskForm;
use yii\filters\auth\CompositeAuth;
use common\filter\QueryParamAuth;
use yii\filters\AccessControl;

/**
 * 
 */
class TaskController extends BaseController
{
    public function behaviors()
    {
        return array_merge([
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    QueryParamAuth::className(),
                ],
            ],
        ], parent::behaviors());
    }

    public function actionIndex()
	{
	    $task = new ExecutorTaskMap;
	    $task_lists = $task->getRecordLists();
	    if(!Yii::$app->request->isAjax){
            (new UserInformation())->loadInformation();
            return $this->render('index',[
                "task_lists" => $task_lists,
            ]);
        }
        return CompressHtml::compressHtml($this->renderPartial('list', [
            'task_lists'=>$task_lists,

        ]));
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
        $task = new ExecutorTaskMap;
        $task->clearEmptyField($filter);
        $task_lists = $task->getFilterTaskLists($filter);

        return CompressHtml::compressHtml($this->renderPartial('list',[
            'task_lists'=>$task_lists,
            'ser_filter'=>serialize($filter),
        ]));
    }

    public function actionExecutingDetail()
    {
        $task_id = Yii::$app->request->get("id");
        $detail = (new ExecutorTaskMap)->getExecutingDetail($task_id);
        $this->layout = '//main';
        if($detail){
            return $this->render("detail",[
                "detail" => $detail,
            ]);
        }

        return $this->redirect(["/site/error"]);
    }

    public function actionAbandonTask()
    {
        $formData = Yii::$app->request->post();
        if(empty($formData["execute_failed_reason"])){
            return json_encode(["code"=>2,"请填写放弃原因"]);
        }
        $execute = new ExecutorTaskMap;
        if($execute->abandonTask($formData)){
            return json_encode(["code"=>0,"message"=>"任务放弃成功"]);
        }
        return json_encode(["code"=>1,"message"=>"任务放弃失败"]);

    }

    public function actionSubmitForm()
    {
        $uuid = Yii::$app->request->get("uuid");
        $task = new Task;
        $list = $task->getRecord(
            [
                "task" => ["executor_information_config"],
            ],
            [
                "=",
                Task::alias["default"]["task"].".uuid",
                $uuid,
            ]
        );

        if($list){
            $config = explode(",",trim($list["executor_information_config"],","));
            foreach ($config as $key => $value){
                $config["submit_evidence"][$value] = $value;
                if($value == "screen_shots"){
                    $config["submit_evidence"][$value."_config"] = $value;
                    unset($config["submit_evidence"][$value]);
                }
                unset($config[$key]);
            }
            return json_encode(["code"=>0,"data"=>$config]);
        }
        return json_encode(["code"=>1,"message"=>"没有任务"]);


    }

    public function actionFileUpload()
    {
        $request = Yii::$app->request;
        $targetUploadDirectory = ExternalFileHelper::getOtherAbsoluteDirectory();
        // 文件夹不存在，则创建
        if (!is_dir($targetUploadDirectory)) {
            mkdir(iconv('UTF-8', 'GBK', $targetUploadDirectory), 0777, true);
        }
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $fileExt = substr(strrchr($_FILES['file']['name'], '.'), 1);
            $newFileName = PlatformHelper::getUUID() . '.' . $fileExt;
            $newFileFullPath = $targetUploadDirectory . $newFileName;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $newFileFullPath)) {
                return ['err_code' => 0, 'msg' => '上传成功', 'file_name' => $newFileName];
            }
            return ['err_code' => 1, 'msg' => '上传失败'];
        }
    }

    /**
     * 物理删除图片
     * @return array
     */
    public function actionFileDelete()
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            $task_map = new ExecutorTaskMap;
            $task_map->setScenario(ExecutorTaskMap::FileDeleteScenario);
            $formData = [];
            $formData["id"] = $request->get("id");
            $formData["img_name"] = $request->get("img_name");
            $task_map->updateRecord($formData);

            Yii::$app->response->format = Response::FORMAT_JSON;
            $imgName = $request->get('img_name');
            $targetUploadDirectory = ExternalFileHelper::getOtherAbsoluteDirectory();
            $fileFullPath = $targetUploadDirectory . $imgName;
            if (file_exists($fileFullPath)) {
                if (unlink($fileFullPath)) {
                    return ['err_code' => 0, 'err_msg' => '删除成功'];
                } else {
                    return ['err_code' => 1, 'err_msg' => '删除失败'];
                }
            } else {
                return ['err_code' => 2, 'err_msg' => '文件不存在'];
            }
        }
    }

    public function actionSubmitTask()
    {
        $request = Yii::$app->request;
        $model = new SubmitTaskForm;
        $formData = $request->post();
        foreach ($formData["SubmitTaskForm"] as $key => $value){
            if(empty($value)){
                unset($formData["SubmitTaskForm"][$key]);
            }
        }
        if(!$model->load($formData) || !$model->validate()){
            return json_encode(["code"=>2,"message"=>$model->dealMyErrors($model->getErrors())]);
        }
        $formData = $formData["SubmitTaskForm"];
        $task_map = new ExecutorTaskMap;
        if($task_map->submitTaskEvidence($formData)){
            return json_encode(["code"=>0,"message"=>"提交成功","modify_url"=>Url::to(["/personal-center/task-manage/task/modify-form","id"=>$formData["id"]]),"next"=>"待确认"]);
        }
        return json_encode(["code"=>1,"message"=>"提交失败"]);
    }

    public function actionModifyTask()
    {
        $formData = Yii::$app->request->post("modifyForm");
        $task_map = new ExecutorTaskMap;
        if($task_map->submitTaskEvidence($formData)){
            return json_encode(["code"=>0,"message"=>"提交成功","modify_url"=>Url::to(["/personal-center/task-manage/task/modify-form","id"=>$formData["id"]]),"next"=>"待确认"]);
        }
        return json_encode(["code"=>1,"message"=>"提交失败"]);
    }

    public function actionModifyForm()
    {
        $task_map_id = Yii::$app->request->get("id");
        $task_map = new ExecutorTaskMap;
        $modify = $task_map->getSubmitEvidence($task_map_id);
        return $modify;
    }

    public function actionGetPayingVoucher()
    {
        $id = Yii::$app->request->get("id");
        $paying_voucher = new ExecutorTaskMap;
        $paying_voucher_info = $paying_voucher->getMyReceivedVoucher($id);
        $html =  CompressHtml::compressHtml($this->renderPartial('paying-voucher-model',['paying_voucher'=>$paying_voucher_info]));
        return json_encode(["code"=>0,"html"=>$html]);
    }


}