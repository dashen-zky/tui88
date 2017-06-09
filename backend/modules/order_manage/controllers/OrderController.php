<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-4-5
 * Time: 下午5:16
 */

namespace backend\modules\order_manage\controllers;

use backend\modules\order_manage\models\OrderNotPassToPassForm;
use Yii;
use common\helpers\CompressHtml;
use backend\controllers\BaseAppController;
use backend\modules\order_manage\models\Order;
use backend\modules\order_manage\models\auditOrderForm;
use common\models\FileUploadAndDelete;


class OrderController extends BaseAppController
{

    public $layout = "//main-login";

    public function actionIndex()
    {
        $task_uuid = Yii::$app->request->get("task_uuid");
        $orders = new Order;
        $order_lists = $orders->getAllOrders($task_uuid);

        if(!Yii::$app->getRequest()->isAjax) {
            return $this->render("list-panel",[
                "order_lists" => $order_lists,
                "task_uuid" => $task_uuid,
            ]);
        }

        return CompressHtml::compressHtml($this->renderPartial('list', [
            'order_lists'=>$order_lists,
            "task_uuid" => $task_uuid,
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
        $order = new Order();
        $order->clearEmptyField($filter);
        $order_lists = $order->getFilterOrderLists($filter);

        return CompressHtml::compressHtml($this->renderPartial('list',[
            'order_lists'=>$order_lists,
            'ser_filter'=>serialize($filter),
        ]));
    }

    public function actionGetCheckInformation()
    {
        $id = Yii::$app->request->get('id');
        $order = (new Order)->getMyOrderCheck($id);
        if($order){
            return json_encode($order);
        }

        return json_encode(["code"=>1,"message"=>"未找到提交信息"]);
    }


    public function actionExecutingDetail()
    {
        $id = Yii::$app->request->get("id");
        $order = (new Order)->getMyOrderDetail($id);
        $this->layout = "//main";
        if($order){
            return $this->render("detail",[
                "detail" =>$order,
            ]);
        }
        return $this->redirect(["/site/error"]);
    }

    public function actionCheckOrder()
    {
        $model = new auditOrderForm;
        $request = Yii::$app->request;
        $formData = $request->post();

        if(empty($formData["auditOrderForm"]["insure_money"]) && !empty($formData["auditOrderForm"]["unit_money"])){
            $formData["auditOrderForm"]["insure_money"] = $formData["auditOrderForm"]["unit_money"];
        }else if(!isset($formData["auditOrderForm"]["unit_money"])){
            return json_encode(["code"=>2,"message"=>"请填写任务金额"]);
        }else if($formData["auditOrderForm"]["insure_money"] > $formData["auditOrderForm"]["unit_money"]){
            return json_encode(["code"=>3,"message"=>"确认金额不能大于任务金额"]);
        }

        if($model->load($formData) && $model->validate()){
            $order = new Order;
            if($formData["auditOrderForm"]["method"] == Order::Pass){
                $order->setScenario(Order::Pass);
                if($order->checkOrder($formData["auditOrderForm"])){
                    return json_encode(["code"=>0,"message"=>"订单审核通过操作成功","status_cn"=>Order::$config["status"][Order::ExecutingStatusReceivingWaiting],"flag"=>1]);
                }
            }else if($formData["auditOrderForm"]["method"] == Order::NotPass){
                $order->setScenario(Order::NotPass);
                if($order->checkOrder($formData["auditOrderForm"])){
                    return json_encode(["code"=>0,"message"=>"订单审核不通过操作成功","status_cn"=>Order::$config["status"][Order::ExecutingStatusNotPass],"flag"=>0]);
                }
            }

        }
        return json_encode(array_merge(["code"=>1],$model->getErrors()));
    }

    public function actionFileUpload()
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;
        $data = (new FileUploadAndDelete)->fileUpload($request,$response);
        return $data;
    }

    public function actionFileDelete()
    {
        $request = Yii::$app->request;
        $data = (new FileUploadAndDelete)->fileDelete($request);
        return $data;
    }

    public function actionModifyOrderNotPassToPass()
    {
        $model = new OrderNotPassToPassForm;
        $request = Yii::$app->request;
        $formData = $request->post();
        if($model->load($formData) && $model->validate()){
            $order = new Order;
            $order->setScenario(Order::NotPassToPass);
            if($order->updateRecord($formData["OrderNotPassToPassForm"])){
                return json_encode(["code"=>0,"message"=>"订单修改成功"]);
            }
            return json_encode(["code"=>2,"message"=>"订单修改失败"]);

        }
        return json_encode(["code"=>1,"message"=>$model->dealMyError($model->getErrors())]);
    }
}