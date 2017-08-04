<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/20
 * Time: 15:41
 */

namespace src\traits;


abstract class Plugin extends Base
{
    protected $configFile = '';
    protected $configs = [];

    /**
     * 主流程（未返回true表示初始化失败）
     * @return boolean
     */
    public abstract function main();

    /**
     * 直接访问（特殊路由）
     */
    public abstract function direct();

}