<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/20
 * Time: 22:25
 */

namespace src\plugins\routeSpecial;


use src\cores\Event;
use src\cores\PluginManager;
use src\plugins\route\Route;
use src\traits\Plugin;

class Main extends Plugin
{
    const LANG_ERROR_ROUTE_SPECIAL_DIRECT_NOT_CALLABLE='Can not access direct method of the plugin';
    protected function getConfigFilePath(){return __DIR__ . '/config.php';}

    protected $rules=[];
    protected $binds=[];

    public function main()
    {
        //加载路由解析特殊规则
        RouteSpecial::setBinds($this->binds);
        RouteSpecial::setRules($this->rules);

        Event::bindListener(Route::EVENT_BEFORE_FORMAT_RES,function (Event $event){
            //路由正解前
            $info=$event['info'];

            //尝试识别路由为直接访问插件的direct方法
            if (preg_match('/^\/?plugin\/(\w+)$/',$info,$matches)){
                PluginManager::initPlugin($matches[1]);
                plugin($matches[1])->direct();
                exit();
            }else{
                $rs=new RouteSpecial();
                $rs->setRes($info);
                $rs->checkDomainToModule();
                $rs->checkAlias();
                $event['info']=$rs->getRes();
            }
        });

        Event::bindListener(Route::EVENT_BEFORE_FORMAT_URL,function (Event $event){
            //url反解
            $url=$event['url'];
            $rs=new RouteSpecial();
            $rs->setRes($url);
            $rs->checkModuleToDomain();
            $rs->checkAlias();
            $event['url']=$rs->getRes();
        });

        return true;
    }
    /** 从路由直接访问的方法 */
    public function direct(){

    }
}