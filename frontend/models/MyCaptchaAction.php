<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-5-5
 * Time: 上午10:44
 */

namespace frontend\models;

use Yii;
use yii\captcha\CaptchaAction;
use yii\web\Response;

class MyCaptchaAction extends CaptchaAction
{

    public $autoRegenerate = true;

    public function run()
    {
        if ($this->autoRegenerate && Yii::$app->request->getQueryParam(self::REFRESH_GET_VAR) === null) {
            $this->setHttpHeaders();
            Yii::$app->response->format = Response::FORMAT_RAW;
            return $this->renderImage($this->getVerifyCode(true));
        }
        return parent::run();
    }
}