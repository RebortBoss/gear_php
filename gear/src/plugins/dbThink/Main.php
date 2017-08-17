<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/5/5
 * Time: 15:44
 */

namespace src\plugins\dbThink;

use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;
use Think\Db\Adapter;

class Main extends Plugin
{
    protected $configs = [];
    protected function getConfigFilePath(){return __DIR__ . '/config.php';}

    public function main()
    {
        require __DIR__.'/autoloader.php';

        Event::bindListener(Factory::EVENT_NEED_RECIPE.'model_M',function (){
            Factory::addRecipe('model_M', function ($name='', $tablePrefix='',$connection='') {
                return Adapter::M($name, $tablePrefix,$connection);
            });
        });

        Event::bindListener(Factory::EVENT_NEED_RECIPE.'model_D',function (){
            Factory::addRecipe('model_D', function ($name='') {
                return Adapter::D($name);
            });
        });
        return true;
    }
    /** 从路由直接访问的方法 */
    public function direct()
    {
    }
}