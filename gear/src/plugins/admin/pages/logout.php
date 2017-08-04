<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/31
 * Time: 14:49
 */
/** @var $cookie \src\plugins\cookie\Cookie */
/** @var $session \src\plugins\session\Session */
$cookie->unsetCookie('isAdmin');
$session->offsetUnset('isAdmin');
maker()->sender()->success(lang('您不再是GearPHP的管理员','You are no longer the admin of Gear.'),'back',3);