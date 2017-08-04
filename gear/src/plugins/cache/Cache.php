<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\cache;


use src\plugins\cache\drivers\FileCache;

class Cache
{
    /** @var  $cacheDriver ICache */
    private $cacheDriver;

    public function __construct($configs)
    {
        $options=$configs[$configs['type']];
        $driver='\src\plugins\cache\drivers\\'.(maker()->format()->hyphenToCamel($configs['type'])).'Cache';
        $this->cacheDriver=new $driver($options);
        if (\Yuri2::randFloat()<$configs['gc_probability']){
            $this->cleanOverTime();
        }
    }

    /**
     * 判断缓存是否存在
     * @access public
     * @param string $name 缓存变量名
     * @return bool
     */
    public function has($name){
        return $this->cacheDriver->has($name);
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed  $default 默认值
     * @return mixed
     */
    public function get($name ,$default = false){
        return $this->cacheDriver->get($name,$default);
    }

    /**
     * 写入缓存
     * @access public
     * @param string    $name 缓存变量名
     * @param mixed     $value  存储数据
     * @param int       $expire  有效时间 0为永久
     * @return boolean
     */
    public function set($name, $value, $expire = null){
           return $this->cacheDriver->set($name, $value, $expire);
    }

    /** 清理过期缓存 */
    public function cleanOverTime(){
        $this->cacheDriver->cleanOverTime();
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function rm($name)
    {
        return $this->cacheDriver->rm($name);
    }

    /**
     * 自增缓存（针对数值缓存）
     * @access public
     * @param string    $name 缓存变量名
     * @param int       $step 步长
     * @return false|int
     */
    public function inc($name, $step = 1)
    {
        return $this->cacheDriver->inc($name,$step);
    }

    /**
     * 自减缓存（针对数值缓存）
     * @access public
     * @param string    $name 缓存变量名
     * @param int       $step 步长
     * @return false|int
     */
    public function dec($name, $step = 1)
    {
        return $this->cacheDriver->dec($name,$step);
    }
}