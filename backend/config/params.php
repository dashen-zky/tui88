<?php
return [
    // 监控任务相关选项
    'monitorTask' => [
        'createOptions' => [
            // 监控频率
            'frequencies' => [300, 600, 1800, 3600],
            // 监控周期
            'cycles' => [86400, 172800],
        ],
        'taskList' => [
            //  每页显示的数目
            'pageLimit' => 11,
            // 链接无效提示文字
            'invalidUrlTips' => '链接无效',
            'urlWaitDetectTips' => '努力监测中，请耐心等待~',
        ]
    ],
    'static_version'=>'1.1',
];
