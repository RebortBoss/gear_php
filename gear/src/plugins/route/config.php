<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/20
 * Time: 20:16
 */
return [
    'configs'=>[
        'mode'=>2, // 1兼容 2.pathinfo 3.rewrite 4.贪婪
        'pathName'=>'U', //兼容模式下参数名
        'suffix'=>'html', //伪静态模式下伪装后缀名 如html
        'defaultModule'=>'Home', //默认模块名
        'defaultCtrl'=>'Main', //默认控制器名
        'defaultAction'=>'index', //默认方法名
    ]

];