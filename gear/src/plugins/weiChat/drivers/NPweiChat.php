<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2016/12/8
 * Time: 15:24
 */

namespace src\plugins\weiChat\drivers;


class NPweiChat extends WeiChat
{
    /**
     * 设置缓存，按需重载
     * @param string $cachename
     * @param mixed $value
     * @param int $expired
     * @return boolean
     */
    protected function setCache($cachename,$value,$expired){
        cache()->set($cachename,$value,$expired);
        return true;
    }

    /**
     * 获取缓存，按需重载
     * @param string $cachename
     * @return mixed
     */
    protected function getCache($cachename){
        return cache()->has($cachename)?cache()->get($cachename):'';
    }

    /**
     * 清除缓存，按需重载
     * @param string $cachename
     * @return boolean
     */
    protected function removeCache($cachename){
        return cache()->rm($cachename);
    }
}