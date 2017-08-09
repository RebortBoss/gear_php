<?php

namespace src\plugins\ueditor;


use src\cores\Config;
use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

/** ueditor的支持 */
class Main extends Plugin
{
    protected $configs=[];

    protected function getConfigFilePath()
    {
        return __DIR__.'/config.php';
    }

    public function main()
    {
        Event::addListener(Factory::EVENT_NEED_RECIPE . 'ueditor', function () {
            $obj = new Ueditor();
            Factory::addRecipe('ueditor', function () use ($obj) {
                return $obj;
            });
        });
        return true;
    }
    /** 从路由直接访问的方法 */
    public function direct()
    {
        config('debug',false);
        config(Config::ERROR_LOG_LV,1);
        check_token(request('token'),'ue') or die('Wrong access token!');
        require __DIR__.'/control/controller.php';
    }
}