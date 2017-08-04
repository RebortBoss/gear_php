<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/21
 * Time: 9:33
 */

//定义函数(依赖于插件 helperFunctions)

/**
 * \cores\Factory的辅助函数
 * 配方名，参数1，参数2...
 * @return \src\cores\Factory
 */
function maker(){
    return \src\cores\Factory::getSingle();
}


/**
 * \cores\PluginManager::getPlugin的辅助函数
 * @param $name string
 * @return \src\traits\Plugin
 */
function plugin($name){
    return \src\cores\PluginManager::getPlugin($name);
}


/**
 * 浏览器友好的变量输出
 * @param mixed         $var 变量
 * @param boolean       $echo 是否输出 默认为true 如果为false 则返回输出字符串
 * @param string        $label 标签 默认为空
 * @param integer       $flags htmlspecialchars flags
 * @return string
 * @author tp5
 */
function dump($var, $echo = true, $label = null, $flags = ENT_SUBSTITUTE){
    return Yuri2::dump($var, $echo, $label, $flags);
}

/**
 * 生成基于目前地址的url
 * @param $res_based string res
 * @param $params array get参数
 * @return string
 */
function url_based($res_based='', $params = []){
    return maker()->route()->getUrl(url_info($res_based), $params);
}

/**
 * 生成路由信息
 * @param $res string 路由信息
 * @param $urlParams array 路由参数
 * @param $camelMode bool 是否使用驼峰格式（否则用连字符格式）
 * @return string
 */
function url_info($res='', $urlParams=[],$camelMode=false){
    return maker()->route()->getUrlInfo($res, $urlParams,$camelMode);
}

/**
 * 生成绝对地址的url
 * @param $info string 路由信息(使用 %m,%c,%a 自动替换为 模块、控制器、方法)
 * @param $urlParams array get参数
 * @return string
 */
function url($info='',$urlParams=[]){
    $info=str_replace('%m',maker()->format()->camelToHyphen(maker()->request()->getModuleName()),$info);
    $info=str_replace('%c',maker()->format()->camelToHyphen(maker()->request()->getCtrlName()),$info);
    $info=str_replace('%a',maker()->format()->camelToHyphen(maker()->request()->getActionName()),$info);
    return maker()->route()->getUrl($info, $urlParams);
}

/**
 * 从请求参数中，返回优先级最高的一个
 * @param $key string
 * @param $default string
 * @return string|array
 */
function request($key='',$default=null){
    $request=maker()->request();
    if (!$key){
        return array_merge($request->gets(),$request->posts(),$request->urlParams());
    }else{
        if(isset($request->urlParams()[$key])) return $request->urlParams()[$key];
        if(isset($request->posts()[$key])) return $request->posts()[$key];
        if(isset($request->gets()[$key])) return $request->gets()[$key];
        return $default;
    }
}

/**
 * 锁函数
 * @param string $name 锁名称
 * @param integer $type 锁类型
 * LOCK_SH 共享锁
 * LOCK_EX 独占锁
 * LOCK_NB 非阻塞(Windows上不支持)，用法LOCK_EX | LOCK_NB
 * @return resource|boolean 成功返回锁文件句柄，失败返回false
 * @author carolkey
 */
function lock($name, $type)
{
    if (is_dir(PATH_RUNTIME.'/lock') || mkdir(PATH_RUNTIME . '/lock', 0775, true)) {
        if (false !== $fp = fopen(PATH_RUNTIME . '/lock/' . $name, 'w')) {
            if (flock($fp, $type)) {
                return $fp;
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
 * @param resource $handle 锁句柄
 * @return boolean 成功返回true，失败返回false
 * @author carolkey
 */
function unlock($handle)
{
    return is_resource($handle) ? flock($handle, LOCK_UN) && fclose($handle) : false;
}

/**
 * 获取/设置 配置项
 * @param $key string|array
 * @param $value mixed
 * @return mixed
 */
function config($key='',$value=Yuri2::VAL_NOT_SET){
    if ($key==''){
        return maker()->config()->configs;
    }
    elseif (is_array($key)){
        maker()->config()->configs=array_merge(maker()->config()->configs,$key);
        return maker()->config()->configs;
    }else{
        return Yuri2::arrGetSet(maker()->config()->configs,$key,$value);
    }
}

/**
 * 请求一个站内身份验证码
 * @param $expire int
 * @param $key string
 * @return string
 */
function order_token($expire=30,$key='gear'){
    $time=time()+$expire;
    $ran=substr(ID,0,5);
    $val=maker()->encrypt()->md5WithKey($ran.$time,$key);
    $rel=$ran.'_'.$time.'_'.$val;
    return $rel;
}

/**
 * 检验站内身份验证码
 * @param $token string
 * @param $key string
 * @return bool
 */
function check_token($token,$key='gear'){
    $arr_token=explode('_',$token);
    if (count($arr_token)<3){return false;}
    $old_id=$arr_token[0];
    $old_time=$arr_token[1];
    if ((time()-$old_time)>0){
        return false; //超时
    }
    $old_val=$arr_token[2];
    $val=maker()->encrypt()->md5WithKey($old_id.$old_time,$key);
    return $val==$old_val;
}

/**
 * 返回cache对象
 * @return src\plugins\cache\Cache
 */
function cache(){
    return maker()->cache();
}

/**
 * 区分大小写的文件存在判断
 * @param string $filename 文件地址
 * @return boolean
 */
function file_exists_case($filename) {
    if (is_file($filename)) {
        if (IS_WIN) {
            if (basename(realpath($filename)) != basename($filename))
                return false;
        }
        return true;
    }
    return false;
}

/**
 * 如果变量存在则返回，否则返回默认值
 * @param $var mixed
 * @param $default mixed
 * @return mixed
 */
function issetOrDefault(&$var, $default=null){
    return isset($var)?$var:$default;
}

/**
 * 以系统编码返回
 * @param $content string
 * @return string
 */
function sysEncode($content){
    return maker()->format()->autoSysCoding($content);
}

/**
 * 中英语言支持函数，返回当前语言内容
 * @param $CH string
 * @param $EN string
 * @return string
 */
function lang($CH,$EN){
    switch (config(\src\cores\Config::LANG)){
        case 'ZH':return $CH;break;
        case 'EN':return $EN;break;
        case 'AUTO':
            $language=issetOrDefault($_SERVER['HTTP_ACCEPT_LANGUAGE'],'zh');
            if (preg_match("/zh-c/i", $language) or preg_match("/zh/i", $language)){
                config(\src\cores\Config::LANG,'ZH');
                return $CH;break;
            }else{
                config(\src\cores\Config::LANG,'EN');
                return $EN;break;
            }
            break;
    }
}