<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\factory\libs;


class Semaphore
{
    private static $instances = []; //存放实例

    private $name = '';
    private $occupied = false;
    private $locker = null;
    private $file = '';
    private $max = 1;

    public function __construct($name, $max = 1)
    {
        $this->name = $name;
        $this->locker = maker()->locker('semaphore/' . $this->name);
        $this->file = PATH_RUNTIME . '/semaphore/' . $this->name . '.sem';
        $this->max = $max;
        self::$instances[] = $this;
    }

    //申请信号量
    public function occupy()
    {
        if ($this->occupied) {
            return true;
        } else {
            $this->locker->lock();
            $this->createIfNotExist();
            $sem = $this->readSem();
            $sem--;
            if ($sem >= 0) {
                $this->writeSem($sem);
                $this->occupied = true;
                $this->locker->unlock();
                return true;
            } else {
                $this->locker->unlock();
                return false;
            }
        }
    }


    //释放信号量
    public function release()
    {
        if ($this->occupied){
            $this->locker->lock();
            $sem = $this->readSem();
            $this->writeSem($sem+1);
            $this->locker->unlock();
        }
        $this->occupied = false;
    }

    private function createIfNotExist()
    {
        if (!is_file($this->file)) {
            maker()->file()->writeFile($this->file, $this->max);
        }
    }

    private function readSem()
    {
        $sem = maker()->file()->readFile($this->file);
        return (int)($sem);
    }

    private function writeSem($sem)
    {
        maker()->file()->writeFile($this->file, $sem);
    }

    public static function releaseAll(){
        foreach (self::$instances as $obj){
            if (is_object($obj)){
                $obj->release();
            }
        }
    }

}