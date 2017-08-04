<?php

namespace src\plugins\upload;


use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

/** 插件模板 */
class Main extends Plugin
{
    protected $configs = [];
    protected function getConfigFilePath(){return __DIR__ . '/config.php';}

    public function main()
    {
        $self=$this;
        Event::addListener(Factory::EVENT_NEED_RECIPE.'upload',function () use ($self) {
            Factory::addRecipe('upload', function ($path=null, $allowtype=null,$maxsize=null, $israndname=null) use ($self) {
                $obj = new Upload();
                $obj->set('path',is_null($path)?$self->configs['path']:$path);
                $obj->set('allowtype',is_null($allowtype)?$self->configs['allowtype']:$path);
                $obj->set('maxsize',is_null($maxsize)?$self->configs['maxsize']:$path);
                $obj->set('israndname',is_null($israndname)?$self->configs['israndname']:$path);
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