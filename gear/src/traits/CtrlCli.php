<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/21
 * Time: 14:21
 */

namespace src\traits;

class CtrlCli extends Base
{

    function __construct()
    {
        parent::__construct();
        if (!IS_CLI){
            exit('It can only run in cli mode.');
        }
    }

    /** 忽略客户端的断开 */
    protected function ignoreUserAbort(){
        ignore_user_abort(true);
    }

    public static function input($tip=''){
        if($tip){
            self::output($tip);
        }
        return trim(fgets(STDIN));
    }

    public static  function output($content,$autoReturn=true){
        fwrite(STDOUT, sysEncode($content.($autoReturn?"\n":'')));
    }

}