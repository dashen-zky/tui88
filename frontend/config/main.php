<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'frontend',
    'aliases'=> [
         '@personal_center'=>"@yii/../../../frontend/modules/personalCenter",
         '@task_manage'=>"@yii/../../../frontend/modules/personalCenter/taskManager",
    ],
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'personal-center' => [
            'class' => 'frontend\modules\personalCenter\Module',
            'modules' => [
                'task-manage' => [
                    'class' => 'frontend\modules\personalCenter\taskManager\Module'
                ],
                'setting' => [
                    'class' => 'frontend\modules\personalCenter\setting\Module'
                ],
                'finance-manage' => [
                    'class' => 'frontend\modules\personalCenter\finance_manage\Module'
                ],
            ],
        ],
        'task-hall' => [
            'class' => 'frontend\modules\taskHall\Module',
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'frontend\models\UserAccount',
            'enableAutoLogin' => true,
            'enableSession' => true,
            "loginUrl" => "/index.php?r=site/login",
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'flushInterval' => 1,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['trace'],
                    'logVars' => [],
                    'categories' => ['dev\*'],//表示以 dev 开头的分类
                    'logFile' => '@runtime/logs/dev-trace.log',
                    'exportInterval' => 1,
                    'enabled' => true
                ],//Yii::trace('message', 'dev\#' . __METHOD__);
            ]
        ],
        'errorHandler' => [
            'errorAction' => '/site/error',
        ],
        'urlManager' => [
//            'enablePrettyUrl' => true
        ]
    ],
    'params' => $params,
];
