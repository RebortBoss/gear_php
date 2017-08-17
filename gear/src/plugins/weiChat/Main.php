<?php

namespace src\plugins\weiChat;


use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

/** 插件模板 */
class Main extends Plugin
{
    protected $configs=[];
    protected function getConfigFilePath(){return __DIR__ . '/config.php';}

    public function main()
    {
        $self=$this;
        Event::bindListener(Factory::EVENT_NEED_RECIPE.'weiChat',function () use ($self) {
            $obj = new WeiChat($self->configs);
            Factory::addRecipe('weiChat', function () use ($obj) {
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