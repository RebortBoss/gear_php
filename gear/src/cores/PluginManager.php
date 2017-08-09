<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/20
 * Time: 16:15
 */

namespace src\cores;

use src\traits\Plugin;

class PluginManager
{
    const EVENT_BEFORE_PLUGIN_INIT='EVENT_BEFORE_PLUGIN_INIT_';
    const EVENT_AFTER_PLUGIN_INIT='EVENT_AFTER_PLUGIN_INIT_';
    const LANG_ERROR_PLUGIN_INIT_MISSING_MAIN_CLASS='Main file not found in plugin when initialization.';
    const LANG_ERROR_PLUGIN_INIT_FAILED='Plugin initialization failed.';

    private static $plugin_configs_file='';
    private static $plugin_configs=[];
    private static $objs=[];



    /** 加载全局插件 */
    public static function initPluginsGlobal(){
        self::$plugin_configs_file=PATH_CONFIG.'/plugins.php';
        $configs=require self::$plugin_configs_file;
        self::$plugin_configs=$configs;
        foreach (self::$plugin_configs as $index =>$plugin_name){
            self::initPlugin($plugin_name);
        }
    }

    /** 加载模块插件 */
    public static function initPluginsFromArray($plugin_arr){
        foreach ($plugin_arr as $k=>$v){
            if(is_numeric($k)&&is_string($v)&&!PluginManager::getPlugin($v)){
                PluginManager::initPlugin($v);
            }
            if (is_array($v) ){
                $plugin=PluginManager::getPlugin($k);
                if(!$plugin){
                    PluginManager::initPlugin($k);
                    $plugin=PluginManager::getPlugin($k);
                }
                $configs=$v;
                $old_plugin_configs=$plugin->get('configs');
                if (is_array($old_plugin_configs)){
                    $configs=array_merge($old_plugin_configs,$v);
                }
                $plugin->set('configs',$configs);
            }
        }
    }

    public static function initPlugin($plugin_name){
        Event::fire(self::EVENT_BEFORE_PLUGIN_INIT,new Event(['plugin_name'=>$plugin_name]));
        $class_name="\\src\\plugins\\$plugin_name\\Main";
        if (class_exists($class_name)){
            /** @var  $plugin Plugin*/
            $plugin=new $class_name();
            $ret=$plugin->main();
            if ($ret===true){
                //初始化成功
                self::$objs[$plugin_name]=$plugin; //存储插件对象
                Event::fire(self::EVENT_AFTER_PLUGIN_INIT.$plugin_name,new Event(['plugin_name'=>$plugin_name]));
            }else{
                //初始化失败
                trigger_error($plugin_name.self::LANG_ERROR_PLUGIN_INIT_FAILED,E_USER_ERROR);
            }
            Event::fire(self::EVENT_AFTER_PLUGIN_INIT,new Event(['plugin_name'=>$plugin_name]));
        }else{
            trigger_error($plugin_name.self::LANG_ERROR_PLUGIN_INIT_MISSING_MAIN_CLASS.':'.$class_name,E_USER_ERROR);
        }
    }

    /**
     * 获得插件的主对象
     * @param $name
     * @return null|Plugin
     */
    public static function getPlugin($name=null){
        if (is_null($name)){
            return array_keys(self::$objs);
        }
        return isset(self::$objs[$name])?self::$objs[$name]:null;
    }

}