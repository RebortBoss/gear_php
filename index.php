<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/20
 * Time: 0:39
 */

define('PATH_GEAR', __DIR__ . '/gear');
/** 按实际情况修改此处，指向Gear核心目录 */


version_compare("5.4", PHP_VERSION, ">") and die("PHP 5.4 or greater is required !");
define('PATH_ROOT', str_replace('\\', '/', __DIR__));
define('IS_CLI',preg_match("/cli/i", php_sapi_name()));
$class_map = require PATH_GEAR . '/configs/classMap.php';
spl_autoload_register(function ($class) use ($class_map) {
    if (isset($class_map[$class])) {
        require $class_map[$class];
        return true;
    }
}, true, true);
require PATH_GEAR . '/vendor/autoload.php'; //composer的自动加载
require PATH_GEAR . (IS_CLI ? '/cli_entrance.php' : '/web_entrance.php'); //入口（区别是否CLI）