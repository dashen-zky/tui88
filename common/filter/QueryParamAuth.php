<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-5-26
 * Time: 下午4:11
 */

namespace common\filter;

use Yii;


class QueryParamAuth extends \yii\filters\auth\QueryParamAuth
{
    public function authenticate($user, $request, $response) {
        if(!Yii::$app->getUser()->isGuest) {
            return Yii::$app->getUser()->getIdentity();
        }

        $user = parent::authenticate($user, $request, $response);
        if(empty($user)) {
            return Yii::$app->getUser()->loginRequired();
        }

        return $user;
    }
}