<?php
namespace src\cores;
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/6/7
 * Time: 13:31
 */

clearstatcache();
if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding("UTF-8");
}

require PATH_GEAR . '/configs/defines.php'; // 读取常量表

//加载配置表
$configs = require PATH_CONFIG . '/global.php';
$obj = new Config();
$obj->configs = $configs;
Factory::addRecipe('config', function () use ($obj) {
    return $obj;
});


require PATH_SRC . '/funcs/helperCore.php'; //助手函数
date_default_timezone_set(config(Config::TIMEZONE));//设置时区
PluginManager::initPluginsGlobal();    /** 初始化插件表 */

const CIL_EVENT_START = 'EVENT_START';
const CIL_EVENT_ON_SHUTDOWN = 'EVENT_ON_SHUTDOWN';
const CIL_EVENT_ON_ERROR = 'EVENT_ON_ERROR';
const CIL_EVENT_ON_EXCEPTION = 'EVENT_ON_EXCEPTION';

register_shutdown_function(function () {
    Event::fire(CIL_EVENT_ON_SHUTDOWN);
    //debug模式下 禁用opcache缓存
    if (function_exists('opcache_reset') and config(Config::DEBUG)) {
        opcache_reset();
    }
    set_error_handler(function () {
        $args=func_get_args();
        Event::fire(CIL_EVENT_ON_ERROR,new Event($args));
    });
    set_exception_handler(function () {
        $args=func_get_args();
        Event::fire(CIL_EVENT_ON_EXCEPTION,new Event($args));
    });


});
//启动
Event::fire(CIL_EVENT_START);
