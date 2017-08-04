<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/21
 * Time: 11:27
 */

namespace src\traits;


/** 单例的特性 */
class Single
{
    private static $single=null; //单例存储

    /** 获取单例 */
    public static function getSingle(){
        return self::$single;
    }

    /**
     * 设置一个单例
     * @param $single Single
     */
    public static function setSingle($single)
    {
        self::$single = $single;
    }

    /**
     * 是否有一个单例
     * @return bool
     */
    public static function hasSingle()
    {
        return is_object(self::$single);
    }
}