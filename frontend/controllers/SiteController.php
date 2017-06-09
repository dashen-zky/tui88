<?php 
namespace frontend\controllers;

use common\helpers\CompressHtml;
use frontend\models\CaptchaForm;
use Yii;
use yii\base\Exception;
use yii\captcha\CaptchaAction;
use yii\helpers\Url;
use common\components\aliyun_php_sdk_sms\SmsMessage;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use frontend\models\LoginForm;
use frontend\models\SignUpForm;
use frontend\models\FindPsdForm;
use frontend\models\UserAccount;

class SiteController extends BaseAppController
{
	/**
	 * @Author	张开圆
	 * @DateTime   2017-03-02
	 * @param
	 * @return [] <acess 访问权限>
	 */
	public function behaviors(){
		return [
        	'access'=> [
            	'class' => AccessControl::className(),
            	'rules' => [
                  	[
                      	'actions' => [
                        	'login',
                        	'register',
                        	'error',
		                    'offline',
                            'find-psd',
                            'find-psd-one',
                            'find-psd-two',
                            'send-message',
                            'clear',
                            "captcha",
                      	],
                    	'allow' => true,
                    	'roles'=>['?'],
                  	],
                	[
                      'allow'=>true,
                      'roles'=>['@'],
                	],
            	],
        	],
        	'verbs'=>[
        		'class' => VerbFilter::className(),
        		"actions" => [
        			'logout' => ['get'],
        			'login' => ['get','post'],
                    'find-psd-one' => ["post"],
                    'find-psd-two' => ["post"],
                    'send-message' => ['post'],
        		],
        	],
    	];
	}

    public function actions()
    {
        return [
            'captcha' =>  [
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 36,
                'width' => 150,
                'minLength' => 5,
                'maxLength' => 5
            ],
        ];
    }


    /**
     * [index 主页]
     * @Author   张开圆
     * @DateTime 2017-03-03
     * @param
     * @return   
     */
    public function actionIndex()
    {
        return $this->redirect(["personal-center/task-manage/task/index"]);
    }

	/**
	 * @Author	张开圆
	 * @DateTime   2017-03-02
	 * @param
	 * @return    [error] <访问错误时返回的网页>
	 */
	public function actionError()
	{
		return $this->render('error',[
		    'name' => "页面不存在",
            "message" => "页面不存在",
        ]);
	}

	/**
	 * @Author	张开圆
	 * @DateTime   2017-03-02T15:44:29+0800
	 * @param
	 * @return     [this->rendr['login']] <登陆页>
	 */
	public function actionLogin()
	{
		//	如果是认证用户 则跳转到 个人中心的任务管理页面
		if(!Yii::$app->user->isGuest){
			return $this->redirect(["personal-center/task-manage/task/index"]);
		}

		// 判断 是否有 post 数据传过来 如果没有的话 就返回 login 页面
		$model = new LoginForm();
    	$request = Yii::$app->request;
    	if(!$request->isPost) {
    		return $this->render('login',[
    			'model' => $model,
        	]);
    	}
    	// 如果 有 post 数据传过来 并且登验证成功且登陆成功 就返回 个人中心的任务管理页面
    	if($model->load($request->post()) && $model->login()){
    		return $this->redirect(["personal-center/task-manage/task/index"]);
    	}

    	return $this->render("login",[
    		"model" => $model,
    	]);
	}

	/**
	 * @Author   张开圆
	 * @DateTime 2017-03-02
	 * @param
	 * @return   [type]
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();
		return $this->redirect(['site/login']);
	}

	public function actionRegister()
    {
        $this->layout = 'register';

        if(!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $request = Yii::$app->request;
        $signup = new SignUpForm;

        if(!$request->isPost) {
            return $this->render('register',[
                "signup" => $signup,
            ]);
        }
        if(!$signup->load($request->post()) || !$signup->validate()){
            return $this->render('register',[
                "signup" => $signup,
            ]);
        }

        $formData = $request->post("SignUpForm");
        $user = new UserAccount;
        if($user->insertRecord($formData)) {
            return $this->redirect(["personal-center/task-manage/task/index"]);
        }
    }

    /**
     * 忘记密码  找回密码第一步
     */
    public function actionFindPsd()
    {
        $model = new FindPsdForm;
        return $this->render("find-psd",[
            "model"=> $model,
        ]);
    }

    /**
     * 发送短信
     */
    public function actionSendMessage()
    {
        $phone = Yii::$app->request->post("phone");
        $verifyCode = Yii::$app->request->post("verify");
        $method = Yii::$app->request->post("method");
        if(!preg_match("/^1[34578]{1}\d{9}$/",$phone)){
            return json_encode(["code"=>1,"message"=>"手机号码不正确"]);
        }
        $user = UserAccount::findOne(["phone" => $phone]);
        if($method == "find_psd") {
            if (!$user) {
                return json_encode(["code" => 1, "message" => "绑定的手机号码不正确"]);
            }
        }else if($method == "register"){
            if ($user) {
                return json_encode(["code" => 1, "message" => "该手机号已注册"]);
            }
        }


        $captcha_validate  = new CaptchaAction('captcha',Yii::$app->controller);
        if($verifyCode != $captcha_validate->getVerifyCode()){
            return json_encode(["code"=>2,"message"=>"图形验证码不正确"]);
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
            return json_encode(["code"=>5,$e->getMessage()]);
        }

        return json_encode(["code"=>0]);
    }

    /**
     * 验证手机号 和 验证码
     */
    public function actionFindPsdOne()
    {
        $model = new FindPsdForm;
        $model ->setScenario("one");
        $request = Yii::$app->request;
        $data = $request->post();
        if($model->load($data) && $model->validate()){
            Yii::$app->session["uuid"] = $model->_account->uuid;
            return json_encode([
                "status"=>0,
                "data"=>CompressHtml::compressHtml($this->renderPartial('find-psd-two'))
            ]);
        }
        $error = $model->getErrorCodeAndMessage($model->getErrors());
        return $error;
    }

    /**
     * 找回密码第二把 重置密码
     */
    public function actionFindPsdTwo()
    {
        if(!Yii::$app->session["uuid"]){
            return $this->redirect(["site/find-psd"]);
        }

        $request = Yii::$app->request;
        $model = new FindPsdForm;
        $model->setScenario('two');
        if(!$model->load($request->post()) || !$model->validate()){
            $error = $model->getErrorCodeAndMessage($model->getErrors());
            return $error;
        }

        $user = new UserAccount;
        $formData = array();
        $formData["password"] = $request->post("FindPsdForm")["password"];
        $formData["uuid"] = Yii::$app->session["uuid"];
        if($user->updateRecord($formData)){
            return json_encode(["status"=>0,"message"=>'']);
        }
    }

    public function beforeAction($action)
    {
        $current_action = $action->id;
        $novalidate_actions = array("find-psd-one","find-psd-two");
        if(in_array($current_action,$novalidate_actions)){
            $action->controller->enableCsrfValidation = false;
        }
        parent::beforeAction($action); // TODO: Change the autogenerated stub
        return true;
    }

}