<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-4-25
 * Time: 上午10:18
 */

namespace backend\modules\fin_manage\controllers;

use common\models\FileUploadAndDelete;
use backend\modules\order_manage\models\Order;
use common\helpers\CompressHtml;
use Yii;
use backend\controllers\BaseAppController;
use backend\modules\fin_manage\models\Finance;


class FinanceController extends BaseAppController
{
    public $layout = '//main-login';

    public function actionPaymentDetail()
    {
        $this->layout = '//main';

        $request = Yii::$app->request;
        if($request->isAjax){
            $ser_filter = [];
            $ser_filter = unserialize($request->get("ser_filter"));
        }else{
            $ser_filter = [];
            $ser_filter['finance_id'] = $request->get("finance_id");
        }
        $fin = new Finance;
        $fin_info = $fin->getMyFinInfo($ser_filter["finance_id"]);
        $fin_order = new Order;
        $fin_order_lists = $fin_order->getFinanceOrders($fin_info["finance_uuid"]);
        if(!$request->isAjax){
            return $this->render("payment-detail",[
                "order_lists"=>$fin_order_lists,
                "fin_info"=>$fin_info,
                "ser_filter"=>serialize($ser_filter),
            ]);
        }

        return CompressHtml::compressHtml($this->renderPartial('payment-detail-list', [
            "order_lists"=>$fin_order_lists,
            "ser_filter"=>serialize($ser_filter),
        ]));

    }

    public function actionPayingSettledIndex()
    {
        $paying_settled = new Finance;
        $paying_settled_lists = $paying_settled->getAllPayingSettledLists();
        if (!Yii::$app->request->isAjax){
            return $this->render("paying-settled-index",[
                "paying_settled_lists" => $paying_settled_lists,
            ]);
        }
        return CompressHtml::compressHtml($this->renderPartial('paying-settled-list',[
            'paying_settled_lists'=>$paying_settled_lists,
        ]));
    }

    public function actionPayingSettledListFilter()
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
        $paying_settled = new Finance;
        $paying_settled->clearEmptyField($filter);
        $paying_settled_lists = $paying_settled->getFilterPayingSettledLists($filter);

        return CompressHtml::compressHtml($this->renderPartial('paying-settled-list',[
            'paying_settled_lists'=>$paying_settled_lists,
            'ser_filter'=>serialize($filter),
        ]));
    }

    public function actionGetPaymentInfo()
    {
        $data = Yii::$app->request->get();
        $payment = new Finance;
        $payment_info = $payment->GetMyPaymentInfo($data["finance_id"],$data["paid_status"]);

       if($data["paid_status"] == Finance::PaidStatusDisable){
            if(empty($payment_info)){
                return json_encode(["code"=>1,"message"=>"系统异常！"]);
            }
            $html = CompressHtml::compressHtml($this->renderPartial('paid-settled-proof-detail',['payment_info' =>$payment_info]));

            return json_encode([
                "code"=>0,
                "html"=> $html,
            ]);
       }

        return $payment_info;
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

    public function actionPaymentSettled()
    {
        $formData = Yii::$app->request->post("Form");
        $payment_settled = new Finance;
        if($payment_settled->myPaymentSettled($formData)){
            return json_encode(["code"=>0,"message"=>"付款成功！"]);
        }
        return json_encode(["code"=>1,"message"=>"付款失败"]);
    }

    public function actionPaidSettledIndex()
    {
        $paid_settled = new Finance;
        $paid_settled_records = $paid_settled->getAllPaidSettledRecords();

        if(!Yii::$app->request->isAjax){
            return $this->render("paid-settled-index",[
                "paid_settled_records" =>$paid_settled_records,
            ]);
        }

        return CompressHtml::compressHtml($this->renderPartial('paid-settled-record',[
            'paid_settled_records'=>$paid_settled_records,
        ]));
    }

    public function actionPaidRecordListFilter()
    {
        if(Yii::$app->request->isPost) {
            $filter = Yii::$app->request->post('ListFilterForm');
        } else {
            $ser_filter = Yii::$app->request->get('ser_filter');
            if(empty($ser_filter)) {
                return $this->redirect(['/site/index']);
            }
            $filter = unserialize($ser_filter);
        }
        $finance = new Finance;
        $finance->clearEmptyField($filter);
        $paid_settled_records = $finance->getPaidSettledFilterRecords($filter);
        return CompressHtml::compressHtml($this->renderPartial('paid-settled-record',[
            'paid_settled_records'=>$paid_settled_records,
            'ser_filter'=>serialize($filter),
        ]));
    }

}