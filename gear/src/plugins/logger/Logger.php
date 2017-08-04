<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\logger;


use src\traits\Base;

class Logger extends Base
{
    /** @var  \Log */
    private $pearLog;
    protected $handler;
    protected $filePreFix;
    protected $ident;
    protected $conf;
    protected $level;
    protected $recoveryProbability=0.05; //回收概率
    protected $timeout=600; //回收超时线

    public function initLogger(){
        $this->pearLog=\Log::factory($this->handler,$this->filePreFix,$this->ident,$this->conf,$this->level);
        if (\Yuri2::randFloat()<=$this->recoveryProbability){
            $this->cleanOlds();
        }
    }

    private function cleanOlds(){
        switch ($this->handler){
            case 'file':
                maker()->file()->ergodicDir(PATH_RUNTIME.'/logs',function ($file){
                    $file=PATH_RUNTIME.'/logs/'.$file;
                    $file=maker()->format()->autoSysCoding($file);
                    $mtime=filemtime($file);
                    if ((time()-$mtime)>$this->timeout){
                        unlink($file);
                    }
                });
                break;
            default:
                break;
        }
    }

    function emerg($message)
    {
        return $this->pearLog->emerg($message);
    }

    function alert($message)
    {
        return $this->pearLog->alert($message);
    }

    function crit($message)
    {
        return $this->pearLog->crit($message);
    }

    function err($message)
    {
        return $this->pearLog->err($message);
    }

    function warning($message)
    {
        return $this->pearLog->warning($message);
    }

    function notice($message)
    {
        return $this->pearLog->notice($message);
    }

    function info($message)
    {
        return $this->pearLog->info($message);
    }

    function debug($message)
    {
        return $this->pearLog->debug($message);
    }
}