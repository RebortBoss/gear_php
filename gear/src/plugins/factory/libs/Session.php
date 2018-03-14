<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\factory\libs;


class Session implements \ArrayAccess
{
    private $prefix = ''; //前缀
    private $rootName = ''; //根目录名

    public function __construct()
    {
        $path_root_arr = \Yuri2::explodeWithoutNull('/', PATH_ROOT);
        $this->rootName = str_replace('.','_', array_pop($path_root_arr));
    }

    /**
     * 设置前缀
     * @param $prefix string
     * @return Session
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /** session终止 */
    public function abort()
    {
        session_abort();
        return $this;
    }

    /** session清理(不会影响其他子网站) */
    public function clear()
    {
        unset($_SESSION[$this->rootName]);
        return $this;
    }



    /**
     * 获得session id
     * @return string
     */
    public function getSid()
    {
        return session_id();
    }

    /**
     * 改变session id
     * @param $sid string
     */
    public function setSid($sid)
    {
        session_id($sid);
    }

    /**
     * 返回当前前缀的session数组
     * @return array
     */
    public function getAll()
    {
        return $this[''];
    }

    public function offsetSet($offset, $value)
    {
        \Yuri2::arrPublic('session.' . $this->rootName . '.' . $this->prefix . '.' . $offset, $value);
    }

    public function offsetExists($offset)
    {
        return !is_null(\Yuri2::arrPublic('session.' . $this->rootName . '.' . $this->prefix . '.' . $offset));
    }

    public function offsetUnset($offset)
    {
        \Yuri2::arrPublic('session.' . $this->rootName . '.' . $this->prefix . '.' . $offset, null);
    }

    public function offsetGet($offset)
    {
        return \Yuri2::arrPublic('session.' . $this->rootName . '.' . $this->prefix . '.' . $offset);
    }
}