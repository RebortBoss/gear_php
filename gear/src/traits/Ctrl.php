<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/21
 * Time: 14:21
 */

namespace src\traits;


use src\cores\Config;
use src\cores\Event;
use src\plugins\cookie\Cookie;
use src\plugins\sender\Sender;
use src\plugins\session\Session;

class Ctrl extends Base
{
    const EVENT_ON_RENDER='EVENT_ON_RENDER';
    const EVENT_ON_CHECK_CAPTCHA='EVENT_ON_CHECK_CAPTCHA';
    const EVENT_ON_GET_FORM_TOKEN='EVENT_ON_GET_FORM_TOKEN';
    const EVENT_ON_CHECK_FORM_TOKEN='EVENT_ON_CHECK_FORM_TOKEN';

    private $assigns = [];

    /**
     * 为视图分配变量
     * @param $name string|array
     * @param $value mixed
     */
    public function assign($name, $value=null)
    {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->assigns[$k] = $v;
            }
        } else {
            $this->assigns[$name] = $value;
        }
    }

    /**
     * 发送请求渲染事件
     * @param $res string
     */
    public function render($res=''){
        $this->assigns['self'] = isset($this->assigns['self'])?$this->assigns['self']:$this;
        $res=url_info($res,[],true);
        Event::fire(self::EVENT_ON_RENDER,new Event(['res'=>$res,'assigns'=>$this->getAssigns()]));
    }

    /**
     * 获得已分配的变量数组
     * @return array
     */
    public function getAssigns()
    {
        return $this->assigns;
    }

    /** 忽略客户端的断开 */
    protected function ignoreUserAbort(){
        ignore_user_abort(true);
    }

    /** 发送请求检验验证码的事件
     * @param $code string
     * @return bool
     */
    protected function checkCaptcha($code=''){
        $event=new Event(['code'=>$code,'is_right'=>false]);
        Event::fire(self::EVENT_ON_CHECK_CAPTCHA,$event);
        return $event['is_right'];
    }

    /** 发送请求表单令牌的事件
     * @return bool
     */
    protected function checkFormToken(){
        $event=new Event(['is_right'=>false]);
        Event::fire(self::EVENT_ON_CHECK_FORM_TOKEN,$event);
        return $event['is_right'];
    }

    /**
     * 开关api模式。
     * api模式下，数组输出将转换为json或xml
     * 关闭其他额外的输出
     * @param $switch bool
     * @param $format string
     */
    protected function apiMode($switch=true,$format='json'){
        config(Config::API_MODE,$switch);
        config(Config::API_FORMAT,$format);
        ignore_user_abort($switch);
    }

    /** @return Cookie */
    protected function cookie(){
        return maker()->cookie()->setPrefix(maker()->request()->getModuleName());
    }

    /** @return Session */
    protected function session(){
        return maker()->session()->setPrefix(maker()->request()->getModuleName());
    }

}