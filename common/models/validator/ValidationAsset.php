<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\models\validator;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the javascript files for client validation.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ValidationAsset extends AssetBundle
{
    public $sourcePath = '@common\models\validator\assets';

    public $js = [
        'yii.customerValidation.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
