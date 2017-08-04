<?php

namespace src\plugins\db;


use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

/** notorm */
class Main extends Plugin
{

    protected function getConfigFilePath(){return __DIR__ . '/config.php';}

    public function main()
    {
        $self=$this;
        Event::addListener(Factory::EVENT_NEED_RECIPE.'db',function () use ($self) {
            Factory::addRecipe('db', function ($config_name='local',$convention=[]) {
                $obj = new Db($config_name,$convention);
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