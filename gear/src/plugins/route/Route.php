<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 16:05
 */

namespace src\plugins\route;


use src\cores\Event;
use src\traits\Base;

class Route extends Base
{

    const EVENT_BEFORE_FORMAT_RES = 'EVENT_BEFORE_FORMAT_RES';
    const EVENT_BEFORE_FORMAT_URL = 'EVENT_BEFORE_FORMAT_URL';
    const LANG_ERROR_ROUTE_UNDEFINED_MODE='Try using an undefined routing mode.';

    protected $mode = 4; //路由模式
    protected $pathName = 'U'; //兼容模式下参数名
    protected $suffix = '.html'; //伪静态模式下伪装后缀名 如.html
    protected $defaultModule = 'Home'; //默认模块名
    protected $defaultCtrl = 'Main'; //默认控制器名
    protected $defaultAction = 'index'; //默认方法名
    private $params = []; //路由参数数组
    private $pathFix = ''; //从url获取此次访问的子目录
    private $res = ''; //res a/b/c

    public function main()
    {
        $this->initPathFix();
        $this->defineConst();
        $this->initRes();
    }

    /**
     * 生成从url获取此次访问的子目录
     */
    private function initPathFix()
    {
        $naples_path_fix = $_SERVER['SCRIPT_FILENAME'];
        $naples_path_fix = preg_replace('/\/index.php$/i', '', $naples_path_fix);
        $naples_path_fix = \Yuri2::strReplaceOnce($_SERVER['DOCUMENT_ROOT'], '', $naples_path_fix);
        $this->pathFix = $naples_path_fix;
    }

    /** 定义相关常量 */
    private function defineConst()
    {
        define('URL_ROOT', \Yuri2::getHttpType() . '://' . $this->getHost() . $this->pathFix);
        define('URL_PUBLIC', URL_ROOT . '/public');
    }

    /**
     * 获取主机名
     * @return string
     */
    public function getHost()
    {
        return \Yuri2::getHost();
    }

    /**
     * 生成从url获取此次访问的res
     */
    private function initRes()
    {
        switch ($this->mode) {
            case '1':
                //兼容模式
                $info=isset($_GET[$this->pathName])?isset($_GET[$this->pathName]):'';
                break;
            case '2':
                //pathinfo模式
                $path_info = empty($_SERVER['PATH_INFO']) ? '' : $_SERVER['PATH_INFO'];
                $orig_path_info = empty($_SERVER['ORIG_PATH_INFO']) ? '' : $_SERVER['ORIG_PATH_INFO'];
                $pathinfo = $path_info ? $path_info : $orig_path_info; //获取index.php/action/login 这样的参数
                $info = $pathinfo;
                break;
            case '3':
                //rewrite模式
                if (isset($_SERVER['REDIRECT_URL']) or isset($_SERVER['REQUEST_URI'])) {
                    $info = isset($_SERVER['REDIRECT_URL'])?$_SERVER['REDIRECT_URL']:$_SERVER['REQUEST_URI'];
                } else {
                    $info = '';
                }
                break;
            case '4':
                //贪婪模式
                if (!isset($_GET[$this->pathName]) and (isset($_SERVER['REDIRECT_URL']) or isset($_SERVER['REQUEST_URI']) and !preg_match('/index\.php(\??[\s\S]*)?$/',$_SERVER['REQUEST_URI']))) {
                    $info = isset($_SERVER['REDIRECT_URL'])?$_SERVER['REDIRECT_URL']:$_SERVER['REQUEST_URI'];
                    $this->mode = 3;
                } elseif (isset($_SERVER['PATH_INFO']) or isset($_SERVER['ORIG_PATH_INFO'])) {
                    //pathinfo模式
                    $path_info = empty($_SERVER['PATH_INFO']) ? '' : $_SERVER['PATH_INFO'];
                    $orig_path_info = empty($_SERVER['ORIG_PATH_INFO']) ? '' : $_SERVER['ORIG_PATH_INFO'];
                    $pathinfo = $path_info ? $path_info : $orig_path_info; //获取index.php/action/login 这样的参数
                    $info = $pathinfo;
                    $this->mode = 2;
                } elseif (isset($_GET[$this->pathName])) {
                    $info = $_GET[$this->pathName];
                    $this->mode = 1;
                } else {
                    $info = '';
                    $this->mode = 1;
                }
                break;
            default:
                $info = '';
                trigger_error(self::LANG_ERROR_ROUTE_UNDEFINED_MODE, E_USER_ERROR);
                break;
        }

        //子目录兼容处理
        $info = \Yuri2::strReplaceOnce($this->getPathFix(), '', $info);

        //修正GET数组在nginx服务器下的表现
        $url_info=(parse_url($info));
        $info=$url_info['path'];
        if (isset($url_info['query'])){
            parse_str($url_info['query'],$gets);
            $_GET=$gets;
            foreach ($gets as $k=>$v){
                if (!isset($_REQUEST[$k])){
                    $_REQUEST[$k]=$v;
                }
            }
        }

        //去掉兼容模式下无用的request
        if ($this->mode==1){
            unset($_GET[$this->pathName]);
            unset($_REQUEST[$this->pathName]);
        }

        //去除伪静态后缀名
        $info = preg_replace('/\.' . $this->suffix . "$/i", '', $info);

        //预留特殊路由识别的接口
        $event = new Event(['info'=>$info]);
        Event::fire(self::EVENT_BEFORE_FORMAT_RES, $event);
        $info = $event['info'];


        $infoArr = \Yuri2::explodeWithoutNull('/',$info);
        switch (count($infoArr)) {
            case 0:
                $info = DS . $this->defaultModule . DS . $this->defaultCtrl . DS . $this->defaultAction;
                break;
            case 1:
                $info = DS . $infoArr[0] . DS . $this->defaultCtrl . DS . $this->defaultAction;
                break;
            case 2:
                $info = DS . implode('/', $infoArr) . DS . $this->defaultAction;
                break;
            case 3:
                $info = DS . implode('/', $infoArr);
                break;
            default:
                $info = DS . array_shift($infoArr) . DS . array_shift($infoArr) . DS . array_shift($infoArr);
                $this->params = $infoArr;
                break;
        }
        $this->res = $info;

        //此处，对连字符写法进行兼容
        $resArr= \Yuri2::explodeWithoutNull('/',$this->res);
        $resArr[0]=maker()->format()->hyphenToCamel($resArr[0]);
        $resArr[1]=maker()->format()->hyphenToCamel($resArr[1]);
        $resArr[2]=maker()->format()->hyphenToCamel($resArr[2],false);
        $this->res='/'.implode('/',$resArr);
    }

    /**
     * 从url获取此次访问的子目录
     * @return string
     */
    public function getPathFix()
    {
        return $this->pathFix;
    }

    /**
     * 从url获取此次访问的res
     */
    public function getRes()
    {
        return $this->res;
    }

    /**
     * 获取路由参数
     * @return array
     */
    public function getParam()
    {
        return $this->params;
    }

    /**
     * 生成一个 gear Url
     * @param $url string RES
     * @param $params array getArr
     * @return string
     */
    public function getUrl($url = '', $params = [])
    {
        //如果留空，返回当前访问地址
        if ($url == '') {
            $url = $this->getHttpType() . '://' . $this->getHost() . $_SERVER["REQUEST_URI"];
            return $url;
        }

        //如果是http开头  将params看作get参数
        if (preg_match('/^https?:/', $url)) {
            if (count($params) > 0) {
                $query = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
                $url .= (strpos($url, '?') ? '&' : '?') . $query;
            }
            return $url;
        }

        //处理 url
        if ($url{0} != '/') {
            $url = '/' . $url;
        }

        //此处，进行路由生成反解
        $event = new Event(['url'=>$url]);
        Event::fire(self::EVENT_BEFORE_FORMAT_URL, $event);
        $url = $event['url'];

        //定义处理函数
        $funcs = [
            function ($url, $params) {
                //兼容模式
                $url = URL_ROOT . '/?' . $this->pathName . '=' . $url;
                $query = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
                if ($query){
                    $url .= (strpos($url, '?') ? '&' : '?') . $query;
                }
                return $url;
            },
            function ($url, $params) {
                //pathinfo模式
                $url = URL_ROOT . '/index.php' . $url;
                $query = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
                if ($query){
                    $url .= (strpos($url, '?') ? '&' : '?') . $query;
                }
                return $url;
            },
            function ($url, $params) {
                //rewrite模式
                $url .= '.' . $this->suffix;
                $url = URL_ROOT . $url;
                $query = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
                if ($query){
                    $url .= (strpos($url, '?') ? '&' : '?') . $query;
                }
                return $url;
            }
        ];

        switch ($this->mode) {
            case '1':
                //兼容模式
                $url = $funcs[0]($url, $params);
                return $url;
            case '2':
                //pathinfo模式
                $url = $funcs[1]($url, $params);
                return $url;
            case '3':
                //rewrite模式
                $url = $funcs[2]($url, $params);
                return $url;
            default :
                return false;
        }
    }

    /**
     * 生成url信息
     * @param $res string
     * @param $params array
     * @return string 形如 a/b/c/d/e....
     */
    public function getUrlInfo($res = '', $params = [],$camelMode=false)
    {
        $resArr = \Yuri2::explodeWithoutNull('/', $res);
        $request=maker()->request();
        switch (count($resArr)) {
            case 0:
                $rel = DS . ($request->getModuleName()?$request->getModuleName():$this->defaultModule) . DS . ($request->getCtrlName()?$request->getCtrlName():$this->defaultCtrl) . DS . ($request->getActionName()?$request->getActionName():$this->defaultAction);
                break;
            case 1:
                $rel = DS . ($request->getModuleName()?$request->getModuleName():$this->defaultModule) . DS . ($request->getCtrlName()?$request->getCtrlName():$this->defaultCtrl) . DS . $resArr[0];
                break;
            case 2:
                $rel = DS . ($request->getModuleName()?$request->getModuleName():$this->defaultModule) . DS . $resArr[0] . DS . $resArr[1];
                break;
            default:
                $rel = DS . $resArr[0] . DS . $resArr[1] . DS . $resArr[2];
                break;
        }
        $rel=$camelMode?$rel: maker()->format()->camelToHyphen($rel);
        foreach ($params as $k => $v) {
            if (!preg_match('/^[\w\-]+$/', $k)) {
                continue;
            }
            if ($v == '' or !\Yuri2::isEchoAble($v)) {
                $rel .= DS . '^' . $k;
            } else {
                $v = urlencode($v);
                $rel .= DS . $k . DS . $v;
            }
        }
        return $rel;
    }

    /**
     * http or https
     * @return string
     */
    public function getHttpType()
    {
        return \Yuri2::getHttpType();
    }


}