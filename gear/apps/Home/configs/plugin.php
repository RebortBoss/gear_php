<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/29
 * Time: 14:28
 */
return [
    'db' => [
        'local' => [
            'dsn' => 'mysql:host=localhost;dbname=test',
            'usn' => 'root',
            'psw' => 'root',
            'convention' => [
                'primary' => "id",// %s = table
                'foreign' => "id_%s",
                'table' => "%s",
                'prefix' => ""
            ]
        ],
    ],
];