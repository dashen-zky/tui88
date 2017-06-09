<?php

namespace frontend\modules\personalCenter\controllers;

use frontend\controllers\BaseAppController;

/**
 * Created by sublime.
 * User: king
 * Date: 17-3-2
 * Time: 上午10:09
 */
class BaseController extends BaseAppController
{
    public $menu = 'task';
    public $layout = '@personal_center/views/layouts/main';

}