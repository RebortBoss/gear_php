<?php

namespace src\plugins\cache;


use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

/** cache */
class Main extends Plugin
{

    protected $configs=[];
    protected function getConfigFilePath(){return __DIR__ . '/config.php';}

    public function main()
    {
        $self=$this;
        Event::bindListener(Factory::EVENT_NEED_RECIPE.'cache',function () use ($self) {
            $obj = new Cache($self->configs);
            Factory::addRecipe('cache', function () use ($obj) {
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