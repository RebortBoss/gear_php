<?php

namespace src\plugins\factory;


use Pinq\Traversable;
use src\cores\Event;
use src\cores\Factory;
use src\plugins\factory\libs\Cookie;
use src\plugins\factory\libs\Excel;
use src\plugins\factory\libs\File;
use src\plugins\factory\libs\Format;
use src\plugins\factory\libs\Image;
use src\plugins\factory\libs\Locker;
use src\plugins\factory\libs\NeuralNetwork;
use src\plugins\factory\libs\Request;
use src\plugins\factory\libs\Semaphore;
use src\plugins\factory\libs\Sender;
use src\plugins\factory\libs\Session;
use src\traits\Plugin;

/** 快捷工厂 */
class Main extends Plugin
{
    protected function getConfigFilePath()
    {
        return false;
    }

    public function main()
    {
        Event::bindListener(Factory::EVENT_NEED_RECIPE.'pinq',function () {
            Factory::addRecipe('pinq', function ($from_array) {
                return Traversable::from($from_array);
            });
        });
        Event::bindListener(Factory::EVENT_NEED_RECIPE . 'cookie', function () {
            Factory::addRecipe('cookie', function () {
                $obj = new Cookie();
                return $obj;
            });
        });
        Event::bindListener(Factory::EVENT_NEED_RECIPE . 'excel', function () {
            Factory::addRecipe('excel', function () {
                $obj = new Excel();
                return $obj;
            });
        });
        Event::bindListener(Factory::EVENT_NEED_RECIPE . 'file', function () {
            $obj = new File();
            Factory::addRecipe('file', function () use ($obj) {
                return $obj;
            });
        });
        Event::bindListener(Factory::EVENT_NEED_RECIPE . 'format', function () {
            $obj = new Format();
            Factory::addRecipe('format', function () use ($obj) {
                return $obj;
            });
        });
        Event::bindListener(Factory::EVENT_NEED_RECIPE . 'image', function () {
            Factory::addRecipe('image', function ($img_file, $status = 0) {
                $obj = new Image($img_file, $status);
                return $obj;
            });
        });
        Event::bindListener(Factory::EVENT_NEED_RECIPE . 'request', function () {
            $req = new Request();
            Factory::addRecipe('request', function () use ($req) {
                return $req;
            });
        });
        Event::bindListener(Factory::EVENT_NEED_RECIPE . 'semaphore', function () {
            Factory::addRecipe('semaphore', function ($name) {
                $obj = new Semaphore($name);
                return $obj;
            });
        });
        Event::bindListener(Factory::EVENT_NEED_RECIPE . 'sender', function () {
            $obj = new Sender();
            Factory::addRecipe('sender', function () use ($obj) {
                return $obj;
            });
        });
        Event::bindListener(Factory::EVENT_NEED_RECIPE . 'session', function () {
            Factory::addRecipe('session', function () {
                $obj = new Session();
                return $obj;
            });
        });
        Event::bindListener(Factory::EVENT_NEED_RECIPE . 'locker', function () {
            Factory::addRecipe('locker', function ($id) {
                $obj = new Locker($id);
                return $obj;
            });
            register_shutdown_function(function () {
                Semaphore::releaseAll();
            });
        });
        return true;
    }
    /** 从路由直接访问的方法 */
    public function direct()
    {
    }
}