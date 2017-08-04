<?php
namespace src\plugins\admin;
use src\cores\Event;
use src\plugins\cookie\Cookie;
use src\traits\Ctrl;
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/31
 * Time: 11:20
 */


/** 发送请求检验验证码的事件
 * @param $code string
 * @return bool
 */
function checkCaptcha($code=''){
    $event=new Event(['code'=>$code,'is_right'=>false]);
    Event::fire(Ctrl::EVENT_ON_CHECK_CAPTCHA,$event);
    return $event['is_right'];
}

/** 发送请求表单令牌的事件
 * @return bool
 */
function checkFormToken(){
    $event=new Event(['is_right'=>false]);
    Event::fire(Ctrl::EVENT_ON_CHECK_FORM_TOKEN,$event);
    return $event['is_right'];
}

if(!checkFormToken()){
    maker()->sender()->error(lang('重复提交的表单','Duplicate submission form.'),'back',3);
}
if(!checkCaptcha(request('cap'))){
    maker()->sender()->warning(lang('错误的验证码','Bad authentication code.'),'back',3);
}
$psw=file_get_contents(dirname(__DIR__).'/password.php');
if (md5(request('psw'))===$psw){
    /** @var $cookie Cookie */
    if (request('rem')=='yes'){
        $cookie->setCookie('isAdmin',true);
    }
    /** @var $session \src\plugins\session\Session */
    $session->offsetSet('isAdmin',true);
    maker()->sender()->success(lang('登录成功！','Login successful!'),request('jump'),3);
}else{
    maker()->sender()->warning(lang('密码错误','Incorrect password.'),'back',3);
}