<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\arrayDb;


use src\plugins\locker\Locker;

class ArrayDb
{
    private $file='';
    /** @var Locker $lock */
    private $lock;
    public $data=[]; //数据域

    public function __construct($name)
    {
        $this->file=__DIR__.'/db/'.$name;
        $this->lock=maker()->locker('array_db'.DS.$name)->setTypeEX();
        while(!$this->lock->lock()){
            usleep(10000);
        }
        $this->load();
    }

    /**
     * 读取
     * @return bool
     */
    public function load(){
        $rel= maker()->file()->readFile($this->file);
        if ($rel===false){return false;}
        else{
            $this->data=unserialize($rel);
            return true;
        }
    }

    /**
     * 保存
     * @return bool
     */
    public function save(){
        return maker()->file()->writeFile($this->file,serialize($this->data));
    }

    public function __destruct()
    {
        $this->lock->unlock();
    }

}