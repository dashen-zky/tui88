<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-4-12
 * Time: 下午3:54
 */

namespace backend\modules\user_manage\controllers;

use frontend\modules\personalCenter\models\UserInformation;
use Yii;
use backend\controllers\BaseAppController;
use common\helpers\CompressHtml;
use backend\modules\user_manage\models\DistributorExecutorMap;
use yii\helpers\Url;


class UserController extends BaseAppController
{
    public $layout = '//main-login';

    public function actionUserList()
    {
        $user = new DistributorExecutorMap;
        $user_lists = $user->getAllRecord();
        if(!Yii::$app->request->isAjax){
            return $this->render("index",[
                "user_lists"=>$user_lists,
                "orderBy" => DistributorExecutorMap::RegisterTimeDesc,
            ]);
        }

        return CompressHtml::compressHtml($this->renderPartial('list', [
            'user_lists'=>$user_lists,
            "orderBy" => DistributorExecutorMap::RegisterTimeDesc,
        ]));
    }

    public function actionListFilter()
    {
        if(Yii::$app->request->isPost) {
            $filter = Yii::$app->request->post("ListFilterForm");
        } else {
            $ser_filter = Yii::$app->request->get('ser_filter');
            if(empty($ser_filter)) {
                return $this->redirect(['index']);
            }
            $filter = unserialize($ser_filter);
        }
        $user = new DistributorExecutorMap();
        $user->clearEmptyField($filter);
        $user_lists = $user->getFilterOrderLists($filter);

        return CompressHtml::compressHtml($this->renderPartial('list',[
            'user_lists'=>$user_lists,
            'ser_filter'=>serialize($filter),
            "orderBy" => isset($filter["orderBy"]) ? $filter["orderBy"] :DistributorExecutorMap::RegisterTimeDesc,
        ]));
    }

    public function actionPullBlack()
    {
        $formData = Yii::$app->request->get();
        $user = new DistributorExecutorMap;
        $user->setScenario(DistributorExecutorMap::PullBlack);
        if($user->pullBlackOrRestore($formData)){
            return json_encode([
                "code"=>0,
                "message"=>"拉黑操作成功",
                "operate_cn"=>DistributorExecutorMap::$config["enable"][DistributorExecutorMap::Restore],
                "enable_cn"=>DistributorExecutorMap::$config["enable"][DistributorExecutorMap::Disable],
                "url" => Url::to(["/user-manage/user/restore","id"=>$formData["id"]]),
            ]);
        }
        return json_encode(["code"=>1,"message"=>"未知错误"]);
    }

    public function actionRestore()
    {
        $formData = Yii::$app->request->get();
        $user = new DistributorExecutorMap;
        $user->setScenario(DistributorExecutorMap::Restore);
        if($user->pullBlackOrRestore($formData)){
            return json_encode([
                "code"=>0,
                "message"=>"恢复操作成功",
                "operate_cn"=>DistributorExecutorMap::$config["enable"][DistributorExecutorMap::PullBlack],
                "enable_cn"=>DistributorExecutorMap::$config["enable"][DistributorExecutorMap::Enable],
                "url" => Url::to(["/user-manage/user/pull-black","id"=>$formData["id"]]),
            ]);
        }
        return json_encode(["code"=>1,"message"=>"未知错误"]);
    }

    public function actionDetail()
    {
        $user_uuid = Yii::$app->request->get("user_uuid");
        $user_info = new DistributorExecutorMap;
        $user_info_detail = $user_info->getUserInfo($user_uuid);

        $this->layout = '//main';
        return $this->render("detail",[
            "user_info_detail" => $user_info_detail,
        ]);
    }
}