<?php

namespace src\plugins\arrayDb;


use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

/** 数组数据库 */
class Main extends Plugin
{
    protected $configs=[];

    protected function getConfigFilePath()
    {
        return false;
    }

    public function main()
    {
        $self=$this;
        Event::bindListener(Factory::EVENT_NEED_RECIPE.'arrayDb',function () use ($self) {
            Factory::addRecipe('arrayDb', function ($name) {
                $obj = new ArrayDb($name);
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