<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/4/20
 * Time: 14:05
 */
$cache_name='gear_service_running_count';
$count=cache()->get($cache_name);
$count=$count?$count+1:1;
cache()->set($cache_name,$count,300);
maker()->logger()->info("[$count] ".lang('Gear常驻服务运行中...',"Gear service is running..."));