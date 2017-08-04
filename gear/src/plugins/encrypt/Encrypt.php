<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\encrypt;



use src\traits\Base;

/** 加密相关 */
class Encrypt extends Base
{
    protected $keyAuthCode='gear';
    protected $keyMd5='gear';

    /**
     * 加密（依赖authCode）
     * @param $string  string 需要加密的文字
     * @param $key string 密钥
     * @param $expiry int 有效期
     * @return  string 密文
     * @author yuri2
     */
    public function encryptAuthCode($string, $key = '', $expiry = 99999999)
    {
        if ($key==''){$key=$this->keyAuthCode;}
        return \Yuri2::encrypt($string, $key, $expiry);
    }

    /**
     * 解密（依赖authCode）
     * @param $string  string 需要解密的文字
     * @param $key string 密钥
     * @return  string 明文
     * @author yuri2
     */
    public function decryptAuthCode($string,$key=''){
        if ($key==''){$key=$this->keyAuthCode;}
        return \Yuri2::decrypt($string,$key);
    }

    /**
     * md5（加入特征码）
     * @param $str string
     * @param $key string
     * @return string
     */
    public function md5WithKey($str,$key=''){
        if ($key==''){$key=$this->keyMd5;}
        return md5($str.$key);
    }

    /**
     * 密码哈希值
     * @param $psw_raw string
     * @return string
     */
    public function password_hash($psw_raw){
        return password_hash($psw_raw,PASSWORD_DEFAULT);
    }

    /**
     * 验证密码哈希值
     * @param $psw_raw string
     * @param $psw_hashed string
     * @return bool
     */
    public function  password_verify($psw_raw,$psw_hashed){
        return  password_verify($psw_raw,$psw_hashed);
    }

}