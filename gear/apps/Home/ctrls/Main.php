<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/21
 * Time: 16:47
 */

namespace apps\Home\ctrls;


use src\cores\Config;
use src\traits\Ctrl;

class Main extends Ctrl
{
    public function index()
    {
        maker()->sender()->success([lang('跳转中','Jumping'),lang('请稍侯...','Please wait...')],url('Gear/Main/index'),1);
    }

    public function hello()
    {
        echo "<h1>Hello , GearPHP!</h1>";
    }

}