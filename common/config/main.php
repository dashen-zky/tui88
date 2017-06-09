<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Asia/Shanghai',
    'language' => 'zh-CN',
    'aliases'=> [
        '@common\models\validator\assets' => "@common/models/validator/assets",
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => 'redis'
        ],
        'session' => [
            'class' => 'yii\web\CacheSession',
            'cache' => 'cache',
            'timeout' => 24*3600,
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=rdsvwq3t80d6dyy39hk3o.mysql.rds.aliyuncs.com;dbname=tui88_dev',// DB开发环境
            'username' => 'qmg_tui88',
            'password' => '51hdQm2018abwem82',
            'charset' => 'utf8'
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0
        ],
    ]
];
