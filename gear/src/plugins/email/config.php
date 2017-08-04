<?php

return [
    'enable_error_attention'=>false,//是否开启错误邮件提醒服务
    'error_attention'=>[
        "host" => '',//smtp服务器
        "port" => '',//端口
        "ssl" => false,//是否ssl
        "username" => '',//用户名
        "password" => '',//密码
        "nickname" => '',//昵称
        "receiver"=>[''],//接收者
    ],
    'configs'=>[
        'default'=>[
            "host"=>'',//smtp服务器
            "port"=>'',//端口
            "ssl"=>false,//是否ssl
            "username"=>'',//用户名
            "password"=>'',//密码
            "nickname"=>'',//昵称
        ],
    ],
];