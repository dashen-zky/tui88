<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-4-27
 * Time: 下午4:44
 */

namespace frontend\modules\personalCenter\finance_manage\controllers;

use Yii;
use common\helpers\CompressHtml;
use frontend\modules\taskHall\models\DistExecMap;
use frontend\modules\taskHall\models\ExecutorTaskMap;
use frontend\modules\personalCenter\controllers\BaseController;
use frontend\modules\personalCenter\finance_manage\models\Finance;

class FinanceController extends BaseController
{
    public $menu = "finance";
    public function actionIndex()
    {
        $finance = new Finance;
        $finance_lists = $finance->getAllFinanceLists();
        $dist_exec_map = new DistExecMap;
        $dist_exec_map_money_info = $dist_exec_map->getMyMoneyInfo();
        if(!Yii::$app->getRequest()->isAjax) {
            return $this->render("index",[
                "finance_lists" => $finance_lists,
                "money_info" => $dist_exec_map_money_info,
            ]);
        }
        return CompressHtml::compressHtml($this->renderPartial('received-money-list', [
            "finance_lists" => $finance_lists,
        ]));
    }

    /**
     * 筛选后的列表
     */
    public function actionFinanceListFilter()
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
        $finance = new Finance();
        $finance->clearEmptyField($filter);
        $finance_filter_lists = $finance->getFilterFinanceLists($filter);

        return CompressHtml::compressHtml($this->renderPartial('received-money-list',[
            'finance_lists'=>$finance_filter_lists,
            'ser_filter'=>serialize($filter),
        ]));
    }

    public function actionGetPaymentInfo()
    {
        $data = Yii::$app->request->get();
        $payment = new Finance;
        $payment_info = $payment->GetMyPaymentInfo($data["finance_id"],$data["paid_status"]);

        $html = CompressHtml::compressHtml($this->renderPartial('received-money-proof',['payment_info' =>$payment_info]));
        return json_encode([
            "code"=>0,
            "html"=> $html,
        ]);
    }

    public function actionGetPaymentRecord()
    {
        $data = Yii::$app->request->get();
        $task_map = new ExecutorTaskMap();
        $task_map_list = $task_map->getPaymentOrder($data["finance_uuid"]);
        $html = CompressHtml::compressHtml($this->renderPartial('task_map_list',['task_map_list' =>$task_map_list]));
        return json_encode([
            "code"=>0,
            "html"=> $html,
        ]);
    }


}