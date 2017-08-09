<?php

namespace src\plugins\neuralNetwork;


use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

/** 神经网络插件 */
class Main extends Plugin
{
    protected $configs=[];
    protected function getConfigFilePath(){return false;}

    public function main()
    {
        Event::addListener(Factory::EVENT_NEED_RECIPE . 'neuralNetwork', function () {
            Factory::addRecipe('neuralNetwork', function ($nodeCount) {
                $obj = new NeuralNetwork($nodeCount);
                return $obj;
            });
        });
        return true;
    }
    /** 从路由直接访问的方法 */
    public function direct()
    {
        echo 'This is plugin "neuralNetwork".';
    }
}