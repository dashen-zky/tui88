<?php
/**
 * Created by PhpStorm
 * USER: dashe
 * Date: 2017/3/8
 */
namespace frontend\modules\personalCenter\setting\controllers;

use Yii;
use frontend\models\UserAccount;
use frontend\modules\personalCenter\setting\models\ModifyPhone;
use common\components\aliyun_php_sdk_sms\SmsMessage;
use frontend\modules\personalCenter\models\UserInformation;
use frontend\modules\personalCenter\setting\models\AmendPsdForm;
use frontend\modules\personalCenter\setting\models\BaseInfoForm;
use frontend\modules\personalCenter\controllers\BaseController;
use frontend\modules\personalCenter\setting\models\BindBankCardForm;
use yii\base\Exception;


class SettingController extends BaseController
{
    public $menu = 'setting';
    public $active;
    public function actionBaseInfo()
    {
        $this->active = 'base_info';
        $request = Yii::$app->request;
        $model = new BaseInfoForm;
        $model->getUserInformation();
        if(!$request->isPost){
            return $this->render("base-info",[
                'model' => $model,
                'active' => $this->active,
            ]);
        }
        if($model->load($request->post()) && $model->validate()){
            $formData = $request->post("BaseInfoForm");
            $formData["id"] = $model->_userInfo->id;

            if($model->_userInfo->updateRecord($formData)){
                return $this->redirect(["/personal-center/setting/setting/base-info"]);
            }
        }
        return $this->render("base-info",[
            'model' => $model,
            'active' => $this->active,
        ]);
    }
    public function actionSendMessage()
    {
        $phone = Yii::$app->request->get("phone");
        if(!preg_match("/^1[34578]\d{9}$/",$phone)){
            return json_encode(["code"=>1,"message"=>"手机号码不正确"]);
        }
        $modify = new ModifyPhone;
        if($modify->isExistPhone($phone)){
            return json_encode(["code"=>2,"message"=>"该手机号已被注册"]);
        }
        $code = '';
        for ($i=0;$i<6;$i++){
            $code .= mt_rand(0,9);
        }
        $session = Yii::$app->session;
        $sendMessage = $session['sendMessage'];
        $sendMessage['phone'] = $phone;
        $sendMessage['verify'] = $code;
        $sendMessage['lifetime'] = 300;
        $session['sendMessage'] = $sendMessage;

        try {
            SmsMessage::sendMessage($phone, $code);
        } catch (Exception $e) {
            return json_encode(["code"=>0,"message"=>"短信发送失败"]);
        }

        return json_encode(["code"=>0]);
    }

    public function actionModifyPhone()
    {
        if(!Yii::$app->request->isAjax){
            return json_encode(["code"=>128,"message"=>"亲，你跑偏了！"]);
        }
        $phone = Yii::$app->request->post("phone");
        $code = Yii::$app->request->post("code");
        if($phone != Yii::$app->session["sendMessage"]["phone"]){
            return json_encode(["code"=>1,"message"=>"和获取验证码的手机号不同！！！"]);
        }
        if($code != Yii::$app->session["sendMessage"]["verify"]){
            json_encode(["code"=>2,"message"=>"验证码不正确！"]);
        }
        $modify = new ModifyPhone;
        return $modify->modifyPhone($phone);
    }

    public function actionAmendPsd()
    {
        $this->active = 'amend-psd';
        $model = new AmendPsdForm;
        if(!Yii::$app->request->isPost){
            return $this->render("amend-psd",[
                'model' => $model,
                'active' => $this->active,
            ]);
        }
        $request = Yii::$app->request;
        if($model->load($request->post()) && $model->validate()){
            $formData = array();
            $formData["uuid"] = Yii::$app->user->id;
            $formData["password"] = $model->newPassword;
            $account = new UserAccount;
            if($account->updateRecord($formData)){
                return $this->redirect(["/site/logout"]);
            }
        }
        return $this->render("amend-psd",[
            "model" => $model,
            'active' => $this->active,
        ]);
    }

    public function actionProceedMethod()
    {
        $this->active = 'proceed';
        return $this->render("proceed-method",[
            'active' => $this->active,
        ]);
    }

    public function actionCheckBindBank()
    {
        $request = Yii::$app->request;
        $model = new BindBankCardForm;
        if($model->load($request->post()) && $model->validate()){
            $user_info = new UserInformation;
            $formData = $model->dealFormData();
            $formData["user_uuid"] = Yii::$app->user->identity->uuid;
            $formData["id"] = Yii::$app->session["user_information"]['id'];
            if($user_info->myUpdateRecord($formData)){
                return json_encode(['code'=>0,"message"=>"绑定成功"]);
            }
            return json_encode(['code'=>2,"message"=>"绑定失败"]);
        }
        return $model->dealError($model->getErrors());
    }
}