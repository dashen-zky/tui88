<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-2-27
 * Time: 上午9:51
 */

namespace frontend\controllers;


use frontend\modules\personalCenter\models\UserInformation;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\web\User;

class BaseAppController extends Controller
{
    const ErrorNumberOne = 1;
    const ErrorNumberTwo = 2;
    const ErrorNumberThree = 3;
    const ErrorNumberFour = 4;
    const ErrorNumberFive = 5;
    const ErrorNumberSix = 6;

    public $layout = '//main';
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
                ]
            ]
        ];
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            Yii::$app->getUser()->on(User::EVENT_AFTER_LOGIN,[
                new UserInformation(),
                'loadInformation'
            ]);
            return true;
        }

        return false;
    }

    public function getParam($index, $defaultValue)
    {
        $value = \Yii::$app->request->get($index);
        return empty($value)?$defaultValue:$value;
    }
}