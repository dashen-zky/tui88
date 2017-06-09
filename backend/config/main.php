<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'task-manage' => [
            'class' => 'backend\modules\task_manage\Module',
        ],
        'order-manage' => [
            'class' => 'backend\modules\order_manage\Module',
        ],
        'user-manage' => [
            'class' => 'backend\modules\user_manage\Module',
        ],
        'settle-manage' => [
            'class' => 'backend\modules\settle_manage\Module',
        ],
        'fin-manage' => [
            'class' => 'backend\modules\fin_manage\Module',
        ],

    ],
    'components' => [
        'user' => [
            'identityClass' => 'backend\models\UserAccount',
            'enableAutoLogin' => true,
            'enableSession' => true,
            // 'loginUrl' => null,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => '/site/error',
        ],
        'urlManager' => [
//            'enablePrettyUrl' => true,
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ]
    ],
    'params' => $params,
];
