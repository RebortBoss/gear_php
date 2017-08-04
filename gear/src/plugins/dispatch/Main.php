<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/21
 * Time: 22:26
 */

namespace src\plugins\dispatch;


use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

class Main extends Plugin
{
    protected function getConfigFilePath()
    {
        return false;
    }

    public function main()
    {

        Event::addListener(\src\cores\Main::EVENT_START, function () {
            if (IS_CLI) {
                echo "

----------------------------------------------------------------------------------------------------------
                                                                                                          
      _______________    ____     ____  __  ______                                                    
     / ____/ ____/   |  / __ \   / __ \/ / / / __ \                                                   
    / / __/ __/ / /| | / /_/ /  / /_/ / /_/ / /_/ /                                                   
   / /_/ / /___/ ___ |/ _, _/  / ____/ __  / ____/                                                    
   \____/_____/_/  |_/_/ |_|  /_/   /_/ /_/_/                                           
                                                                                     
                                                                                                       
   Copyright (c) 2017 Yuri2.All rights reserved.
   
   [ Gear_php v1.0 ]     ".date('Y/m/d H:i:s')."                                                                                                  
                                                                                                      
-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -

";

                $obj = new DispatchCli();
                Factory::addRecipe('dispatchCli', function () use ($obj) {
                    return $obj;
                });
                Factory::getSingle()->dispatchCli()->main();
            } else {
                $request = Factory::getSingle()->request();
                $obj = new Dispatch($request);
                Factory::addRecipe('dispatch', function () use ($obj) {
                    return $obj;
                });
                Factory::getSingle()->dispatch()->main();
            }

        });

        Event::addListener(\src\cores\Main::EVENT_ON_SHUTDOWN, function () {
            if (IS_CLI) {
                //打印结束分割线
                echo "
-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
        
    The script runs out ".date('Y/m/d H:i:s')." .  
    
----------------------------------------------------------------------------------------------------------\n";
            }
        });
        return true;
    }

    /** 从路由直接访问的方法 */
    public function direct()
    {
    }
}