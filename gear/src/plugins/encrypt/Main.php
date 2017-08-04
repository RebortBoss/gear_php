<?php

namespace src\plugins\encrypt;


use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

/** 加密插件 */
class Main extends Plugin
{

    protected $configs = [];
    protected function getConfigFilePath(){return __DIR__ . '/config.php';}

    public function main()
    {
        //如果没有配置文件，自动生成一个随机的密钥
        if(!is_file($this->getConfigFilePath())){
            $this->configs=[
                'keyAuthCode' => md5(time()+rand(1000,9999)),
                'keyMd5' => md5(time()+rand(1000,9999)),
            ];
            $content=var_export($this->configs,true);
            file_put_contents($this->getConfigFilePath(),"<?php
return [
    'configs' =>$content
];");
        }

        $self=$this;
        Event::addListener(Factory::EVENT_NEED_RECIPE.'encrypt',function () use ($self){
            $obj = new Encrypt();
            $obj->set($self->configs);
            Factory::addRecipe('encrypt', function () use ($obj) {
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