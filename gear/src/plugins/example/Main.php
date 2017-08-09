<?php

namespace src\plugins\example;


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
        Event::addListener(Factory::EVENT_NEED_RECIPE.'example',function () use ($self) {
            Factory::addRecipe('example', function () {
                $obj = new Example();
                return $obj;
            });
        });
        return true;
    }
    /** 从路由直接访问的方法 */
    public function direct()
    {
        echo 'This is plugin "example".';
    }
}