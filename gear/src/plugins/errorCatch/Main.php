<?php

namespace src\plugins\errorCatch;


use src\cores\Config;
use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

/** 插件模板 */
class Main extends Plugin
{

    protected function getConfigFilePath()
    {
        return false;
    }

    public function main()
    {

        Event::bindListener(Factory::EVENT_NEED_RECIPE.'errorCatch',function () {
            $obj = new ErrorCatch();
            Factory::addRecipe('errorCatch', function () use ($obj) {
                return $obj;
            });
        });
        Event::bindListener(\src\cores\Main::EVENT_ON_ERROR,function (Event $event){
            ErrorCatch::onError($event[0],$event[1],$event[2],$event[3]);
        });
        Event::bindListener(\src\cores\Main::EVENT_ON_EXCEPTION,function (Event $event){
            ErrorCatch::onException($event[0]);
        });
        Event::bindListener(\src\cores\Main::EVENT_ON_SHUTDOWN,function (){
            if (!config(Config::API_MODE) and config(Config::DEBUG) and config(Config::SHOW_DEBUG_BTN)){
                ErrorCatch::displayTrace();
            }
            ErrorCatch::onShutdown();
        });
        Factory::addRecipe('getErrors',function (){
           return ErrorCatch::getErrors();
        });
        return true;
    }
    /** 从路由直接访问的方法 */
    public function direct()
    {
    }
}