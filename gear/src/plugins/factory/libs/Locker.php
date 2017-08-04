<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\factory\libs;


class Locker
{
    private $id='';//标识符
    private $filePath='';//文件锁位置
    private $fileDir='';//文件夹位置
    private $type=LOCK_EX;
    private $fp=null;

    /**
     * @param string $name 锁名称
     */
    public function __construct($name)
    {
        $this->id=$name;
        $this->fileDir=PATH_RUNTIME.'/lock';
        $this->filePath=$this->fileDir.DS.$this->id;
        \Yuri2::createDir(dirname($this->filePath));
    }

    public function setTypeEX(){
        $this->type=LOCK_EX;
        return $this;
    }

    public function setTypeSH(){
        $this->type=LOCK_SH;
        return $this;
    }

    public function setTypeNB(){
        $this->type=LOCK_EX | LOCK_NB;
        return $this;
    }

    /**
     * 锁函数
     * LOCK_SH 共享锁
     * LOCK_EX 独占锁
     * LOCK_NB 非阻塞(Windows上不支持)，用法LOCK_EX | LOCK_NB
     * @return boolean 成功返回锁文件句柄，失败返回false
     * @author carolkey
     */
    public function lock(){
        if ($this->fileDir || mkdir($this->fileDir, 0775, true)) {
            if (false !== $fp = fopen($this->filePath, 'w')) {
                if (flock($fp, $this->type)) {
                    $this->fp=$fp;
                    return true;
                } else {
                    fclose($fp);
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * 解锁
     * @return boolean 成功返回true，失败返回false
     * @author carolkey
     */
    function unlock()
    {
        return is_resource($this->fp) ? flock($this->fp, LOCK_UN) && fclose($this->fp) : false;
    }

    /**
     * 排他运行的代码(超时功能只在非windows环境下有效?)
     * @param $call callable
     * @param $time_limit int (0表示无限等待)
     * @return bool
     */
    function exclusive($call,$time_limit=0){
        $time_begin=time();
        $type_pre=$this->type;
        $this->setTypeNB();
        while (!($this->fp or $this->lock())){
            usleep(rand(1, 10000));
            if ($time_limit!=0 and  time()>$time_begin+$time_limit){
                return false;
            }
        }
        $call();
        $this->unlock();
        $this->type=$type_pre;
        return true;
    }

}