<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 17:19
 */
define('SHOW_ERROR_FORCE', false);//强制显示所有错误
define('STIME',$stime=microtime(true));
ini_set("display_errors", "Off");

/** 获取框架运行耗时 */
function getTimeSpend(){

    return defined("STIME")? round((microtime(true)-STIME)*1000,2):0;
}

/**
 * 打印耗时
 * @param $tag string
 */
function dumpTimeSpend($tag='undefined'){
    static $rem=[];
    $time=getTimeSpend();
    if(isset($rem[$tag])){
        $spend=$time-$rem[$tag];
        echo "<p>[$tag] $spend ms</p>";
        unset($rem[$tag]);
    }else{
        $rem[$tag]=$time;
    }
}
$main=new \src\cores\Main();
$main->main();
exit();