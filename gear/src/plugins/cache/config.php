<?php

return [
    'version' => 1,
    'requires'=>[

    ],
    'configs'=>[
        'type'=>'file',
        'gc_probability'=>0.02,
        'file'=>[
            'expire'        => 0,
            'cache_subdir'  => false,
            'prefix'        => 'cache_',
            'path'          => PATH_RUNTIME.'/fileCaches',
            'data_compress' => false,
        ],
        'redis'=>[
            'expire'=>0,
            'host'=>'127.0.0.1',
            'port'=>'6379',
            'prefix'=>'cache_',
            'data_compress' => false,
        ]
    ],
];