<?php

return [
    'configs'=>[
        'handler'=>'file',
        'filePreFix'=>PATH_RUNTIME.'/logs/log-'.date('Ymd').'.log',
        'conf'=>[
            'locking'=>1,
            'timeFormat'=>'%H:%M:%S',
        ],
        'level'=>7,
        'ident'=>'['.Yuri2::getIp().']',
        'recoveryProbability'=>0.02,
        'timeout'=>3600*24*30,
    ]
];