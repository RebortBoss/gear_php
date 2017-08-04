<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/20
 * Time: 14:37
 */

define('LANG','zh_CN');
define('DS','/');
define('RN',"\r\n");
define('PATH_APPS',PATH_GEAR.'/apps');
define('PATH_CONFIG',PATH_GEAR.'/configs');
define('PATH_PUBLIC',PATH_ROOT.'/public');
define('PATH_RUNTIME',PATH_GEAR.'/runtime');
define('PATH_SRC',PATH_GEAR.'/src');
define('PATH_LANG',PATH_SRC.'/lang');
define('PATH_PLUGINS',PATH_SRC.'/plugins');
define('ID',Yuri2::uniqueID());
define('IS_CGI',(0 === strpos(PHP_SAPI,'cgi') || false !== strpos(PHP_SAPI,'fcgi')) ? true : false );
define('IS_WIN',strstr(PHP_OS, 'WIN') ? true : false );