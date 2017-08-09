<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/20
 * Time: 11:02
 */

namespace src\cores;


class Main
{

    const EVENT_START = 'EVENT_START';
    const EVENT_DESTRUCT = 'EVENT_DESTRUCT';
    const EVENT_ON_SHUTDOWN = 'EVENT_ON_SHUTDOWN';
    const EVENT_ON_ERROR = 'EVENT_ON_ERROR';
    const EVENT_ON_EXCEPTION = 'EVENT_ON_EXCEPTION';

    /** 主流程 */
    public function main()
    {
        $this->init();
        Event::fire(self::EVENT_START);
    }

    /** 初始化 */
    private function init()
    {
        $this->initDefines();
        $this->initConfig();
        $this->initFuncs();
        $this->initSetting();
        $this->initPlugins();
        $this->errorHideAll();
        $this->sysEvent();

    }

    /** 基础设置 */
    private function initSetting()
    {
        $lifetime=600; //10分钟后过期
        session_start();
        setcookie(session_name(),session_id(),time()+$lifetime);

        header('Content-Type: text/html; charset=UTF-8');
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
//        ini_set('short_open_tag', 'On'); //useless?
        clearstatcache();
        if (function_exists('mb_internal_encoding')) {
            mb_internal_encoding("UTF-8");
        }
        if (function_exists('mb_http_output')) {
            mb_http_output('UTF-8');
        }
        date_default_timezone_set(config(Config::TIMEZONE));
        ignore_user_abort(config(Config::IGNORE_USER_ABORT));
        set_time_limit(config(Config::TIME_LIMIT));//设置超时时间
        ini_set('always_populate_raw_post_data', -1);//we don't use $HTTP_RAW_POST_DATA
        ini_set('memory_limit', config(Config::MEMORY_LIMIT) . 'M');//设置运行最大内存
        ini_set('max_input_time', config(Config::MAX_INPUT_TIME));//设置表单提交最大时间
        ini_set('post_max_size', config(Config::POST_MAX_SIZE) . 'M');//设置post最大数据
        ini_set('upload_max_filesize', config(Config::UPLOAD_MAX_FILESIZE) . 'M');//设置文件上传的最大文件上限
        ini_set('ignore_repeated_errors', config(Config::IGNORE_REPEATED_ERRORS));//忽略重复的错误
        ini_set('ignore_repeated_source', config(Config::IGNORE_REPEATED_SOURCE));//忽略重复的错误来源
        ini_set('xdebug.var_display_max_children', config(Config::XDEBUG_VAR_DISPLAY_MAX_CHILDREN)); // 最多孩子节点数
        ini_set('xdebug.var_display_max_data', config(Config::XDEBUG_VAR_DISPLAY_MAX_DATA));// 最大字节数
        ini_set('xdebug.var_display_max_depth', config(Config::XDEBUG_VAR_DISPLAY_MAX_DEPTH));// 最大深度
    }

    /** 读取常量表 */
    private function initDefines()
    {
        require PATH_GEAR . '/configs/defines.php';
    }

    /** 加载基本助手函数 */
    private function initFuncs()
    {
        require PATH_SRC . '/funcs/helperCore.php';
        require PATH_SRC . '/funcs/common.php';
    }

    /** 加载全局配置 */
    private function initConfig()
    {
        $configs = require PATH_CONFIG . '/global.php';
        $obj = new Config();
        $obj->configs = $configs;
        Factory::addRecipe('config', function () use ($obj) {
            return $obj;
        });
    }

    /** 初始化插件表 */
    private function initPlugins()
    {
        PluginManager::initPluginsGlobal();
    }

    /** 注册系统事件 */
    private function sysEvent()
    {
        register_shutdown_function(function () {
            Event::fire(self::EVENT_ON_SHUTDOWN);
            if (function_exists('opcache_reset') and config(Config::DEBUG)) {
                opcache_reset();
            }
        });
        set_error_handler(function () {
            $args=func_get_args();
            Event::fire(self::EVENT_ON_ERROR,new Event($args));
        });
        set_exception_handler(function () {
            $args=func_get_args();
            Event::fire(self::EVENT_ON_EXCEPTION,new Event($args));
        });
    }

    /** 错误报告全开 */
    private function errorHideAll()
    {
        error_reporting(E_ALL);
        ini_set("display_errors", SHOW_ERROR_FORCE?'On':"Off");
    }

    /** 析构 */
    function __destruct()
    {
        Event::fire(self::EVENT_DESTRUCT);
    }
}