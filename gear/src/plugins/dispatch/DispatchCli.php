<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/6/7
 * Time: 15:24
 */

namespace src\plugins\dispatch;


use src\cores\Config;
use src\cores\PluginManager;

class DispatchCli
{
    const LANG_ERROR_DISPATCH_FILE_NOT_FOUND= 'Can not find controller file';
    const LANG_ERROR_DISPATCH_ACTION_NOT_FOUND= 'Can not find controller action';
    const LANG_ERROR_DISPATCH_TRY_ACCESS_STATIC_ACTION= 'Trying to access static areas.';
    const LANG_ERROR_DISPATCH_TRY_ACCESS_PROTECTED_ACTION= 'Trying to access protected areas.';



    private $argv=[];
    private $pathinfo=''; //  a/b/c
    private $module='Home';
    private $controller='Main';
    private $action='index';
    private $className='';
    private $ctrlFile='';
    private $objCtrl;
    private $methodRef;
    private $bindByOrder=true; //参数绑定格式 true按顺序，false按名称
    private $params=[];//传递的参数
    private $res='';

    function __construct()
    {
        global $argv;
        $this->argv=$argv;
        $this->pathinfo=issetOrDefault($argv[1],'');
    }

    private function route(){
        $info=\Yuri2::explodeWithoutNull('/',$this->pathinfo);
        switch (count($info)){
            case 0:break;
            case 1:
                $this->module=$info[0];
                break;
            case 2:
                $this->module=$info[0];
                $this->controller=$info[1];
                break;
            default :
                $this->module=$info[0];
                $this->controller=$info[1];
                $this->action=$info[2];
                break;
        }

        $this->res=$this->module.DS.$this->controller.DS.$this->action;

        //连字符转驼峰
        $this->module=maker()->format()->hyphenToCamel($this->module,true);
        $this->controller=maker()->format()->hyphenToCamel($this->controller,true);
        $this->action=maker()->format()->hyphenToCamel($this->action,false);

        $this->argv[1]= DS.$this->module.DS. $this->controller.DS.$this->action;

        $this->ctrlClass = "\\apps\\{$this->module}\\ctrls\\{$this->controller}";

    }

    //解析数组
    public function parseArgs(){
        $argv=$this->argv;
        array_shift($argv);
        array_shift($argv);

        if($argv && preg_match('/^-/',$argv[0])){
            //第一个参数以 -开头 表示 按名称绑定
            $this->bindByOrder=false;
            for ($i=0;$i<count($argv);$i+=2){
                preg_match('/^--?([^-]+)$/',$argv[$i],$matches);
                if(!isset($matches[1]) or !isset($argv[$i+1])){$this->error('invalid param format');}
                $this->params[$matches[1]]=$argv[$i+1];
            }
        }else{
            //顺序绑定
            $this->params=$argv;
        }

    }

    /**
     * @return string
     */
    public function getRes()
    {
        return $this->res;
    }

    //错误并退出
    private function error($msg){
        exit("\n    [gear_php] Error: ".sysEncode($msg));
    }

    /** 读取模块配置 */
    private function loadConfig()
    {
        $config_dir = PATH_APPS . DS . $this->module . "/configs";
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

        \Yuri2::setErrorReportLevel(Config::ERROR_LOG_LV);

        if (is_file($file_autoload)){
            require $file_autoload;
        }
    }

    /** 创建控制器 */
    private function createCtrl()
    {
        $this->ctrlFile = PATH_APPS . DS . $this->module . DS . 'ctrls' . DS . $this->controller . '.php';
        if (!is_file($this->ctrlFile)) {
            $this->error(self::LANG_ERROR_DISPATCH_FILE_NOT_FOUND.' -- '. $this->ctrlFile);
        }
        $this->className = "\\apps\\{$this->module}\\ctrls\\{$this->controller}";
        $ctrl = new $this->className();
        $classRef = new \ReflectionClass($this->className);
        try {
            $methodRef = $classRef->getMethod($this->action);
        } catch (\Exception $e) {
            $this->error(self::LANG_ERROR_DISPATCH_ACTION_NOT_FOUND.' -- '. $this->controller . '->' . $this->action );
        }
        $this->objCtrl = $ctrl;
        $this->methodRef = $methodRef;
    }
    /** 运行控制器 */
    private function runCtrl(){
        if ($this->methodRef->isStatic()){
            $this->error(self::LANG_ERROR_DISPATCH_TRY_ACCESS_STATIC_ACTION.' -- '.$this->className . '->' . $this->action);
        }
        $protected_actions=['init','get','set'];
        if ($this->action{0} == '_' or in_array($this->action,$protected_actions)) {
            $this->error(self::LANG_ERROR_DISPATCH_TRY_ACCESS_PROTECTED_ACTION.' -- '.$this->className . '->' . $this->action);
        }
        $rel = \Yuri2::invokeMethod($this->methodRef, $this->params, $this->objCtrl);
        echo sysEncode($rel);
    }

    //主流程
    public function main(){
        $this->route();
        $this->loadConfig();
        $this->parseArgs();
        $this->createCtrl();
        $this->runCtrl();
    }

}