<?php

namespace src\plugins\picMagic;


use src\cores\Config;
use src\cores\Event;
use src\cores\Factory;
use src\plugins\picMagic\PicMagic;
use src\traits\Plugin;

/** 图片魔术url */
class Main extends Plugin
{
    protected function getConfigFilePath()
    {
        return false;
    }

    public function main()
    {
        PicMagic::setConfigs(['cacheDir' => PATH_RUNTIME . '/picMagic']);
        Event::bindListener(Factory::EVENT_NEED_RECIPE . 'picMagic', function () {
            Factory::addRecipe('picMagic', function ($pic,$params) {
                $obj = new PicMagic($pic,$params);
                return $obj;
            });
        });
        return true;
    }
    /** 从路由直接访问的方法 */
    public function direct()
    {
        config(Config::DEBUG,false);
        if (check_token(request('token'))){
            $pic=maker()->picMagic(request('pic'),request());
            $pic->display();
        }else{
            exit( 'wrong token');
        }
    }
}