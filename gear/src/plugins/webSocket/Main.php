<?php

namespace src\plugins\webSocket;


use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

/** webSocket工厂 */
class Main extends Plugin
{
    protected $configs = [];

    protected function getConfigFilePath()
    {
        return false;
    }

    public function main()
    {
        Event::bindListener(Factory::EVENT_NEED_RECIPE.'webSocketServer',function () {
            Factory::addRecipe('webSocketServer', function ($host = 'localhost', $port = 8000, $ssl = false) {
                $obj = new Server($host, $port , $ssl);
                return $obj;
            });
        });
        Event::bindListener(Factory::EVENT_NEED_RECIPE.'webSocketClient',function () {
            Factory::addRecipe('webSocketClient', function () {
                $obj = new Client();
                return $obj;
            });
        });
        return true;
    }
    /** 从路由直接访问的方法 */
    public function direct()
    {
    }
}