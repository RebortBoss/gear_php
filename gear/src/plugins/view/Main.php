<?php

namespace src\plugins\view;


use src\cores\Event;
use src\cores\Factory;
use src\plugins\dispatch\Dispatch;
use src\plugins\route\Route;
use src\plugins\session\Session;
use src\traits\Ctrl;
use src\traits\Plugin;

/** 视图 */
class Main extends Plugin
{
    protected function getConfigFilePath(){return __DIR__ . '/config.php';}

    public function main()
    {
        $self=$this;
        Event::addListener(Ctrl::EVENT_ON_RENDER, function (Event $event) use ($self){
            $obj = new View();
            $obj->set('assigns',$event['assigns']);
            $obj->set('res',$event['res']);
            $obj->set('configs',$self->configs);
            $obj->main();
        });
        require __DIR__.'/V.php';
        return true;
    }
    /** 从路由直接访问的方法 */
    public function direct()
    {
    }
}