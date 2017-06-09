<?php
namespace backend\controllers;

use backend\models\UserAccount;
use common\helpers\PlatformHelper;
use Yii;
use backend\models\LoginForm;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * Class SiteController
 * @package backend\controllers
 */
class SiteController extends BaseAppController
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                    	'allow' => true,
                    	'actions' => ['login',"register","error"],
                    	'roles' => ["?"],
                    ],
                ]
            ]
        ];
    }

    /**
     * @author 张开圆
     * @return 后台的主页
     */
   	public function actionIndex()
    {
        return $this->redirect(["/task-manage/task/list"]);
   	}

   	public function actionError()
    {
        return $this->render("error");
    }
    /**
     * 用来后台用户登陆
     * @return
     */
   	public function actionLogin()
    {
        if(!Yii::$app->user->isGuest){
            return $this->goHome();
        }
        $model = new LoginForm;
        $request = Yii::$app->request;
        if($model->load($request->post()) && $model->login()){
            return $this->goHome();
        }
        return $this->render("login",[
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

//    public function actionRegister()
//    {
//        $formData = [];
//        $formData["phone"] = 15395100590;
//        $formData["uuid"] =  PlatformHelper::getUUID();
//        $formData["create_time"] = time();
//        $formData["update_time"] = time();
//        $formData["password"] = Yii::$app->security->generatePasswordHash("111111");
//        $formData["access_token"] = $formData["uuid"];
//        $formData["email"] = 'default@email.com';
//        $admin = new UserAccount;
//        var_dump($admin->insertRecord($formData));
//    }

}
