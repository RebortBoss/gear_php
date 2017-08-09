<?php

namespace src\plugins\captcha;


use src\cores\Config;
use src\cores\Event;
use src\cores\Factory;
use src\traits\Ctrl;
use src\traits\Plugin;

/** 验证码 */
class Main extends Plugin
{

    protected function getConfigFilePath(){return __DIR__ . '/config.php';}

    public function main()
    {
        $self = $this;
        Event::addListener(Factory::EVENT_NEED_RECIPE . 'captcha', function () use ($self) {
            $obj = new Captcha();
            $obj->set($self->configs);
            Factory::addRecipe('captcha', function () use ($obj) {
                return $obj;
            });
        });
        Event::addListener(Ctrl::EVENT_ON_CHECK_CAPTCHA, function (Event $event) {
            $s=maker()->session()->setPrefix('plugin.captcha');
            $code_last = $s['code'];
            if (strtolower($code_last) == strtolower($event['code']) and $event['code']!='') {
                $event['is_right']=true;
            }
            unset($s['code']);
            $event->stopSpread();
        });
        return true;
    }

    /** 从路由直接访问的方法 */
    public function direct()
    {
        $cap = maker()->captcha();
        if (request('width')) {
            $cap->set('width', request('width'));
        }
        if (request('height')) {
            $cap->set('height', request('height'));
        }
        $cap->doImg();
        $code = $cap->getCode();
        $s=maker()->session()->setPrefix('plugin.captcha');
        $s['code']=$code;
        Event::freezeAll();
        config(Config::SHOW_DEBUG_BTN,false);
    }
}