<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\factory\libs;


use src\traits\Base;

class Cookie extends Base
{
    protected $expire = 2592000; //默认过期时间
    private $prefix = ''; //前缀
    private $path='';
    private $domain='';

    protected function init()
    {
        $PathFix=maker()->route()->getPathFix();
        if ($PathFix==''){$PathFix='/';}
        $this->path=$PathFix;
        $this->domain=maker()->route()->getHost();
    }

    /**
     * 设置前缀
     * @param $prefix string
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * 设置cookie 自动加密
     * @param $name string
     * @param $value mixed
     * @param $expire int|null
     */
    public function setCookie($name, $value, $expire = null)
    {
        if (is_null($expire)) {
            $expire = $this->expire;
        }
        $value = serialize($value);
        $value = maker()->encrypt()->encryptAuthCode($value, '', $expire);
        setcookie($this->getKeyName($name), $value, time() + $expire,$this->path,$this->domain);
    }

    /**
     * 获取加密的cookie
     * @param $name string 空字符串代表返回所有解密结果
     * @param $autoPrefixed bool 自动加前缀？
     * @return mixed
     */
    public function getCookie($name = '',$autoPrefixed=true)
    {
        if ($name == '') {
            $rel = [];
            foreach ($_COOKIE as $name => $value) {
                $cookie = $this->getCookie($name,false);
                $rel[$name] = $cookie;
            }
            return $rel;
        } else {
            if (isset($_COOKIE[$this->getKeyName($name,$autoPrefixed)])) {
                $rel = $_COOKIE[$this->getKeyName($name,$autoPrefixed)];
                $rel = maker()->encrypt()->decryptAuthCode($rel, '');
                $rel = unserialize($rel);
            }else{
                $rel = null;
            }
            return $rel;
        }
    }

    /**
     * 是否有某个cookie
     * @param $name string
     * @return bool
     */
    public function hasCookie($name)
    {
        return isset($_COOKIE[$this->getKeyName($name)]);
    }/** @noinspection PhpInconsistentReturnPointsInspection */

    /**
     * 删除某个cookie
     * @param $name string
     */
    public function unsetCookie($name)
    {
        unset($_COOKIE[$this->getKeyName($name)]);
        setcookie($this->getKeyName($name), '', time() - 1,$this->path,$this->domain);
    }

    private function getKeyName($name,$autoPrefixed=true)
    {
        $rel = $autoPrefixed?$this->prefix . '_' . $name:$name;
        $rel = str_replace('.', '_', $rel);
        return $rel;
    }

}