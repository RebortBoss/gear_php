<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/23
 * Time: 15:14
 */

namespace src\plugins\envCheck;


use src\cores\Config;
use src\cores\Event;
use src\traits\Plugin;

class Main extends Plugin
{
    public  function main()
    {
        if (!is_file(__DIR__.'/lock') and !IS_CLI){
            include __DIR__ . '/probe.php';
            exit();
        }
        return true;
    }
    /** 从路由直接访问的方法 */
    public function direct()
    {
        Event::fire(\src\plugins\admin\Main::EVENT_PLUGIN_ADMIN_ON_CHECK_ADMIN);
        config(Config::SHOW_DEBUG_BTN,false);
        include __DIR__ . '/probe.php';
        exit();
    }
}