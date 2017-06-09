<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-4-13
 * Time: 下午6:28
 */

namespace backend\modules\settle_manage\controllers;

use backend\modules\fin_manage\models\Finance;
use backend\modules\settle_manage\models\DistExecMap;
use Yii;
use common\helpers\CompressHtml;
use backend\controllers\BaseAppController;
use backend\modules\order_manage\models\Order;
use backend\modules\settle_manage\models\Settle;
use backend\modules\settle_manage\models\SettleTask;
use backend\modules\user_manage\models\DistributorExecutorMap;
use yii\helpers\Url;

class SettleController extends BaseAppController
{
    public $layout = '//main-login';

    public function actionSettlingIndex()
    {
        $settling = new Settle;
        $settling_lists = $settling->getAllSettlingRecord();

        if (!Yii::$app->getRequest()->isAjax) {
            return $this->render("settling-index", [
                "settling_lists" => $settling_lists,
            ]);
        }

        return CompressHtml::compressHtml($this->renderPartial('settling-list', [
            "settling_lists" => $settling_lists,
        ]));
    }

    public function actionOrderIndex()
    {
        $this->layout = '//main';

        $data = Yii::$app->request->get();
        $ser_filter = isset($data["ser_filter"])? unserialize($data["ser_filter"]): null;
        $executor = !isset($ser_filter["executor_uuid"])? $data["executor_uuid"] : $ser_filter["executor_uuid"];
        $phone = !isset($ser_filter["phone"])? $data["phone"] : $ser_filter["phone"];
        $dis_exe_map = new DistributorExecutorMap;
        if(!$dis_exe_map->isExistRecord($executor ,$phone)){
            return $this->redirect("/site/error");
        }
        $order = new Order;
        $order_lists = $order->getSettleOrderLists($executor);

        if (!Yii::$app->getRequest()->isAjax) {
            return $this->render("order-index", [
                "order_lists" => $order_lists,
                "ser_filter" => serialize(["executor_uuid"=>$executor,"phone"=>$phone]),
            ]);
        }

        return CompressHtml::compressHtml($this->renderPartial('order-list', [
            "order_lists" => $order_lists,
            "ser_filter" => serialize(["executor_uuid"=>$executor,"phone"=>$phone]),
        ]));
    }

    public function actionOrderListFilter()
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
        $order_lists = $order->getSettleOrderListFilter($filter);

        return CompressHtml::compressHtml($this->renderPartial('order-list',[
            'order_lists'=>$order_lists,
            'ser_filter'=>serialize($filter),
        ]));

    }

    public function actionSettlingListFilter()
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
        $settling = new Settle;
        $settling->clearEmptyField($filter);
        $settling_lists = $settling->getSettlingListFilter($filter);

        return CompressHtml::compressHtml($this->renderPartial('settling-list',[
            'settling_lists'=>$settling_lists,
            'ser_filter'=>serialize($filter),
        ]));

    }

    public function actionAllSettleOrder()
    {
        $dist_exec_map_ids = Yii::$app->request->get("ids");
        $dist_exec_map = new DistExecMap;
        $all_settles = $dist_exec_map->getAllSettleOrder($dist_exec_map_ids);
        $settle_task = new SettleTask;
        if($settle_task->myAllOrderSettleInsert($all_settles)){
            return json_encode(["code"=>0,"message"=>"结算成功"]);
        }
        return json_encode(["code"=>1,"message"=>"结算失败"]);
    }

    public function actionSettleOrderSome()
    {
        $settle = new SettleTask;
        $formData = [];
        $formData["executor_task_ids"] = Yii::$app->request->get("data"); // 直接 是 数组
        $formData["executor_uuid"] = Yii::$app->request->get("user_uuid"); // 执行人
        if($settle->mySomeSettleInsert($formData)){
            return json_encode(["code"=>0,"message"=>"结算成功","url"=>Url::to(["/settle-manage/settle/order-index","executor_uuid"=>$formData["executor_uuid"],"phone"=>Yii::$app->request->get("phone")])]);
        }
        return json_encode(["code"=>1,"message"=>"结算失败"]);
    }


    public function actionSettledIndex()
    {
        $settle = new Settle;
        $settled_records = $settle->getAllSettledRecord();
        if(!Yii::$app->request->isAjax){
            return $this->render("settled-index",[
                "settled_records" => $settled_records,
            ]);
        }
        return CompressHtml::compressHtml($this->renderPartial('settled-record', [
            "settled_records" => $settled_records,
        ]));
    }

    public function actionSettledRecordFilter()
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
        $settled = new Settle;
        $settled->clearEmptyField($filter);
        $settled_records = $settled->getSettledListFilter($filter);

        return CompressHtml::compressHtml($this->renderPartial('settled-record',[
            'settled_records'=>$settled_records,
            'ser_filter'=>serialize($filter),
        ]));
    }

}