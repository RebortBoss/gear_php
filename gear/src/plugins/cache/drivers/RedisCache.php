<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\cache\drivers;

use src\plugins\cache\ICache;


/** Redis缓存 */
class RedisCache implements ICache
{
    private static $conn;

    protected $options = [
        'expire'=>0,
        'host'=>'',
        'port'=>'',
        'prefix'=>'',
        'data_compress' => false,
    ];

    /**
     * 初始化检查
     * @access private
     * @param $configs array
     */
    public function __construct($configs=[])
    {
        $this->options=array_merge($this->options,$configs);
        if(!is_object(self::$conn)){
            self::$conn=new \Redis();
            self::$conn->connect($this->options['host'],$this->options['port']);
        }
    }

    /**
     * 取得变量的存储名
     * @access protected
     * @param string $name 缓存变量名
     * @return string
     */
    protected function getCacheKey($name)
    {
        return $this->options['prefix'] . $name;
    }

    /**
     * 判断缓存是否存在
     * @access public
     * @param string $name 缓存变量名
     * @return bool
     */
    public function has($name)
    {
        return $this->get($name) ? true : false;
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed  $default 默认值
     * @return mixed
     */
    public function get($name, $default = false)
    {
        $name=$this->getCacheKey($name);
        $content=self::$conn->get($name);
        if ($content===false){
            return $default;
        }
        if ($this->options['data_compress'] && function_exists('gzcompress')) {
            //启用数据压缩
            $content = gzuncompress($content);
        }
        $content = unserialize($content);
        return $content;
    }

    /**
     * 写入缓存
     * @access public
     * @param string    $name 缓存变量名
     * @param mixed     $value  存储数据
     * @param int       $expire  有效时间 0为永久
     * @return boolean
     */
    public function set($name, $value, $expire = null)
    {
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }
        if($expire===0){
            $expire=999999999;
        }
        $name = $this->getCacheKey($name);

        $data = serialize($value);
        if ($this->options['data_compress'] && function_exists('gzcompress')) {
            //数据压缩
            $data = gzcompress($data, 3);
        }
        self::$conn->set($name,$data,$expire);
        return true;
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
        $name=$this->getCacheKey($name);
        if ($this->has($name)) {
            $value = $this->get($name) + $step;
        } else {
            $value = $step;
        }
        return $this->set($name, $value) ? $value : false;
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
        if ($this->has($name)) {
            $value = $this->get($name) - $step;
        } else {
            $value = $step;
        }
        return $this->set($name, $value) ? $value : false;
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function rm($name)
    {
        self::$conn->delete($this->getCacheKey($name));
        return true;
    }


    /** 清理过期缓存 */
    public function cleanOverTime(){

    }

}