<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-2-27
 * Time: 上午9:51
 */

namespace backend\controllers;


use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;

class BaseAppController extends Controller
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
                ]
            ]
        ];
    }

    public function getParams($index, $default = null) {
        return empty(Yii::$app->getRequest()->get($index))?$default:Yii::$app->getRequest()->get($index);
    }
}