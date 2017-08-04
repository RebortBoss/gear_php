<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/21
 * Time: 10:44
 */

namespace src\plugins\factory\libs;


class Request
{
    private $moduleName = '';
    private $ctrlName = '';
    private $actionName = '';

    /**
     * @return string
     */
    public function getModuleName()
    {
        if (!$this->moduleName) {
            $this->resToNames();
        }
        return $this->moduleName;
    }

    private function resToNames()
    {
        $resArr = \Yuri2::explodeWithoutNull('/', $this->getRes());
        $this->moduleName = isset($resArr[0])?$resArr[0]:'';
        $this->ctrlName = isset($resArr[1])?$resArr[1]:'';
        $this->actionName = isset($resArr[2])?$resArr[2]:'';
    }

    /** 获取res */
    public function getRes()
    {
        return IS_CLI?maker()->dispatchCli()->getRes(): maker()->route()->getRes();
    }

    /**
     * @return string
     */
    public function getCtrlName()
    {
        if (!$this->moduleName) {
            $this->resToNames();
        }
        return $this->ctrlName;
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        if (!$this->moduleName) {
            $this->resToNames();
        }
        return $this->actionName;
    }

    /**
     * 对get数组的操作
     * @param $target string
     * @param $value string
     * @return string|array
     */
    public function gets($target = '', $value = \Yuri2::VAL_NOT_SET)
    {
        return \Yuri2::arrPublic('get.' . $target, $value);
    }

    /**
     * 对post数组的操作
     * @param $target string
     * @param $value string
     * @return string|array
     */
    public function posts($target = '', $value = \Yuri2::VAL_NOT_SET)
    {
        return \Yuri2::arrPublic('post.' . $target, $value);
    }

    /**
     * 对require数组的操作
     * @param $target string
     * @param $value string
     * @return string|array
     */
    public function requires($target = '', $value = \Yuri2::VAL_NOT_SET)
    {
        return \Yuri2::arrPublic('request.' . $target, $value);
    }

    /**
     * 对server数组的操作
     * @param $target string
     * @param $value string
     * @return string|array
     */
    public function servers($target = '', $value = \Yuri2::VAL_NOT_SET)
    {
        return \Yuri2::arrPublic('server.' . $target, $value);
    }

    /**
     * 对session数组的操作
     * @param $target string
     * @param $value string
     * @return string|array
     */
    public function sessions($target = '', $value = \Yuri2::VAL_NOT_SET)
    {
        return \Yuri2::arrPublic('session.' . $target, $value);
    }

    /**
     * 对cookie数组的操作
     * @param $target string
     * @param $value string
     * @return string|array
     */
    public function cookies($target = '', $value = \Yuri2::VAL_NOT_SET)
    {
        return \Yuri2::arrPublic('cookie.' . $target, $value);
    }

    /** http or https */
    public function getHttpType()
    {
        return maker()->route()->getHttpType();
    }

    /**
     * 获得路由参数
     * @return array
     */
    public function urlParams()
    {
        $param = maker()->route()->getParam();
        $pairs = [];
        while (!empty($param)) {
            $e1 = array_shift($param);
            if ($e1{0} == '^') {
                $pairs[\Yuri2::strReplaceOnce('^', '', $e1)] = '';
            } else {
                $pairs[$e1] = urldecode(array_shift($param));
            }
        }
        return $pairs;
    }

    /**
     * 模拟添加数据到Request数组
     * @param $key string 键名
     * @param $value mixed 键值
     * @param $mode string get/post/both 自动有优先级处理
     * @author yuri2
     */
    public function addRequest($key,$value,$mode='get'){
        \Yuri2::addRequest($key,$value,$mode);
    }

    /**
     * 模拟删除request数组中的某键值
     * @param $key string 键名
     * @author yuri2
     */
    public function rmRequest($key){
        unset($_REQUEST[$key]);
        unset($_GET[$key]);
        unset($_POST[$key]);
    }

    /** 是否是老IE浏览器 */
    public function isOldIE()
    {
        return \Yuri2::isOldIE();
    }

    /** 是否是get访问 */
    public function isGet()
    {
        return \Yuri2::isGet();
    }

    /** 是否是post访问 */
    public function isPost()
    {
        return \Yuri2::isPost();
    }

    /** 是否是ajax访问 */
    public function isAjax()
    {
        return \Yuri2::isAjax();
    }

    /** 是否是pjax访问 */
    public function isPjax()
    {
        return \Yuri2::isPjax();
    }

    /** 是否是本地访问 */
    public function isLocal()
    {
        return \Yuri2::isLocal();
    }

    /** 获得ip */
    public function getIp()
    {
        return \Yuri2::getIp();
    }

    public function getHost(){
        return \Yuri2::getHost();
    }

}