<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/20
 * Time: 20:08
 */

namespace src\plugins\route;


use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

/** 路由 */
class Main extends Plugin
{

    protected function getConfigFilePath(){return __DIR__ . '/config.php';}

    public function main()
    {
        if(IS_CLI){return true;} //如果是CLI模式，不进行任何操作

        Event::addListener(\src\cores\Main::EVENT_START, function () {
            $route=new Route();
            $route->set($this->configs);
            Factory::addRecipe('route', function () use ($route) {
                return $route;
            });
            $route->main();
        });

        return true;
    }
    /** 从路由直接访问的方法 */
    public function direct()
    {
    }
}