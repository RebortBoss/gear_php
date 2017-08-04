<?php

return [
    'configs' => [
        'update_time' => 3600, //超时强制重新编译
        'replace' => [
            '__PUBLIC__' => '<?=URL_PUBLIC?>',
        ]
    ]
];