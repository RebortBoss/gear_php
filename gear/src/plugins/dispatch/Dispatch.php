<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/21
 * Time: 14:24
 */

namespace src\plugins\dispatch;

use src\cores\Config;
use src\cores\Event;
use src\cores\PluginManager;
use src\plugins\request\Request;
use src\traits\Base;
use src\traits\Ctrl;

class Dispatch extends Base
{
    const EVENT_BEFORE_CTRL_RUN = 'EVENT_BEFORE_CTRL_RUN';
    const EVENT_AFTER_CTRL_RUN = 'EVENT_AFTER_CTRL_RUN';
    const LANG_ERROR_DISPATCH_TRY_ACCESS_PROTECTED_ACTION= 'Trying to access protected areas.';
    const LANG_ERROR_DISPATCH_TRY_ACCESS_STATIC_ACTION= 'Trying to access static areas.';
    const LANG_ERROR_DISPATCH_FILE_NOT_FOUND= 'Can not find controller file';
    const LANG_ERROR_DISPATCH_ACTION_NOT_FOUND= 'Can not find controller action';

    /** @var  $request Request */
    private $request; //请求对象
    private $res = ''; //res
    private $moduleName = ''; //模块名
    private $ctrlName = ''; //控制器名
    private $ctrlClass = ''; //控制器类名
    private $methodRef; //控制器方法的反射
    private $ctrlFile = ''; //控制器文件路径
    /** @var Ctrl $ctrl */
    private $ctrl = null; //控制器对象
    private $actionName = ''; //方法名

    function __construct($request)
    {
        parent::__construct();
        $this->request = $request;
    }

    /** 开始调度 */
    public function main()
    {
        $this->prepare();
        $this->loadConfig();
        $this->createCtrl();
        $this->runCtrl();
    }

    /** 准备工作 搜集数据 */
    private function prepare()
    {
        $res = $this->request->getRes();
        $this->res = $res;
        $arr_res = \Yuri2::explodeWithoutNull('/', $res);
        $this->moduleName = $arr_res[0];
        $this->ctrlName = $arr_res[1];
        $this->ctrlFile = PATH_APPS . DS . $this->moduleName . DS . 'ctrls' . DS . $this->ctrlName . '.php';
        if (!is_file($this->ctrlFile)) {
            if (config(Config::DEBUG))
                maker()->sender()->error([self::LANG_ERROR_DISPATCH_FILE_NOT_FOUND, $this->ctrlFile], 'back');
            else
                maker()->sender()->notFound($this->res, 'back', 3);
        }
        $this->actionName = $arr_res[2];
        $this->ctrlClass = "\\apps\\{$this->moduleName}\\ctrls\\{$this->ctrlName}";
    }

    /** 读取模块配置 */
    private function loadConfig()
    {
        $config_dir = PATH_APPS . DS . $this->moduleName . "/configs";
        $file_config = $config_dir . '/global.php';
        $file_plugin = $config_dir . '/plugin.php';
        $file_autoload = $config_dir . '/autorun.php';
        if (is_file($file_config)) {
            $config_arr = require $file_config;
            config($config_arr);
        }
        if (is_file($file_plugin)) {
            $plugin_arr=require  $file_plugin;
            foreach ($plugin_arr as $plugin_name=>$configs){
                if ($plugin=PluginManager::getPlugin($plugin_name)){
                    $old_plugin_configs=$plugin->get('configs');
                    if (is_array($old_plugin_configs)){
                        $configs=array_merge($old_plugin_configs,$configs);
                    }
                    $plugin->set('configs',$configs);
                }
            }
        }
        if (is_file($file_autoload)){
            require $file_autoload;
        }
    }

    /** 创建控制器 */
    private function createCtrl()
    {
        $ctrl = new $this->ctrlClass();
        $classRef = new \ReflectionClass($this->ctrlClass);
        try {
            $methodRef = $classRef->getMethod($this->actionName);
        } catch (\Exception $e) {
            if (config(Config::DEBUG))
                maker()->sender()->error([self::LANG_ERROR_DISPATCH_ACTION_NOT_FOUND, $this->ctrlClass . '->' . $this->actionName . '()'], 'back');
            else
                maker()->sender()->notFound($this->res, 'back', 3);
        }
        $this->ctrl = $ctrl;
        $this->methodRef = $methodRef;
    }

    /** 运行控制器 */
    private function runCtrl()
    {
        if ($this->methodRef->isStatic()){
            if (config(Config::DEBUG))
                maker()->sender()->error([self::LANG_ERROR_DISPATCH_TRY_ACCESS_STATIC_ACTION, $this->ctrlClass . '->' . $this->actionName . '()'], 'back');
            else
                maker()->sender()->notFound($this->res, 'back', 3);
        }
        $route_param = $this->request->urlParams();
        $protected_actions=['init','get','set'];
        if ($this->actionName{0} == '_' or in_array($this->actionName,$protected_actions)) {
            if (config(Config::DEBUG))
                maker()->sender()->error([self::LANG_ERROR_DISPATCH_TRY_ACCESS_PROTECTED_ACTION, $this->ctrlClass . '->' . $this->actionName . '()'], 'back');
            else
                maker()->sender()->notFound($this->res, 'back', 3);
        } else {
            $event = new Event(['ctrl' => $this->ctrl]);
            Event::fire(self::EVENT_BEFORE_CTRL_RUN, $event);
            $rel = \Yuri2::invokeMethod($this->methodRef, $route_param, $this->ctrl);
            if (config(Config::API_MODE)) {
                switch (config(Config::API_FORMAT)) {
                    case 'json':
                        $rel = maker()->format()->arrayToJson($rel);
                        break;
                    case 'xml':
                        $rel = maker()->format()->arrayToXml($rel);
                        break;
                }
                echo $rel;
            } else {
                \Yuri2::smarterEcho($rel);
            }
            Event::fire(self::EVENT_AFTER_CTRL_RUN, $event);
        }
    }
}