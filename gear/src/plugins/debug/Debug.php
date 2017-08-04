<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\debug;

use src\cores\PluginManager;
use src\traits\Base;

class Debug extends Base
{
    protected $recoveryProbability=0.05; //回收概率
    protected $timeout=600; //回收超时线

    /**
     * 存储这次访问的详细信息
     */
    public function saveReport(){
        $errors=maker()->getErrors();
        $basic=[
            'ID'=>ID,
            'IP'=>maker()->request()->getIp(),
            'timestamp'=>time(),
            'url'=>url(),
            'gets'=>maker()->request()->gets(),
            'posts'=>maker()->request()->posts(),
            'sessions'=>maker()->request()->sessions(),
            'cookies'=>maker()->cookie()->getCookie(),
        ];
        $plugins=PluginManager::getPlugin();
        $report=compact('basic','plugins','errors');
        $report_content=serialize($report);
        maker()->file()->writeFile(PATH_RUNTIME.'/reports/'.ID.'.php',$report_content);
        if (\Yuri2::randFloat()<=$this->recoveryProbability){
            $this->cleanOlds();
        }
    }

    private function cleanOlds(){
        maker()->file()->ergodicDir(PATH_RUNTIME.'/reports',function ($file){
            $file=PATH_RUNTIME.'/reports/'.$file;
            $file=maker()->format()->autoSysCoding($file);
            $mtime=filemtime($file);
            if ((time()-$mtime)>$this->timeout){
                unlink($file);
            }
        });
    }

    /**
     * 记录和统计时间（微秒）和内存使用情况
     * 使用方法:
     * <code>
     * G('begin'); // 记录开始标记位
     * // ... 区间运行代码
     * G('end'); // 记录结束标签位
     * echo G('begin','end',6); // 统计区间运行时间 精确到小数后6位
     * echo G('begin','end','m'); // 统计区间内存使用情况
     * 如果end标记位没有定义，则会自动以当前作为标记位
     * 其中统计内存使用需要 MEMORY_LIMIT_ON 常量为true才有效
     * </code>
     * @param string $start 开始标签
     * @param string $end 结束标签
     * @param integer|string $dec 小数位或者m
     * @return mixed
     */
    public function watcher($start,$end='',$dec=4) {
        static $_info       =   array();
        static $_mem        =   array();
        if(is_float($end)) { // 记录时间
            $_info[$start]  =   $end;
        }elseif(!empty($end)){ // 统计时间和内存使用
            if(!isset($_info[$end])) $_info[$end]       =  microtime(TRUE);
            if(function_exists('memory_get_usage') && $dec=='m'){
                if(!isset($_mem[$end])) $_mem[$end]     =  memory_get_usage();
                return number_format(($_mem[$end]-$_mem[$start])/1024);
            }else{
                return number_format(($_info[$end]-$_info[$start]),$dec);
            }

        }else{ // 记录时间和内存使用
            $_info[$start]  =  microtime(TRUE);
            if(function_exists('memory_get_usage')) $_mem[$start]           =  memory_get_usage();
        }
        return null;
    }
}