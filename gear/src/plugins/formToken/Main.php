<?php

namespace src\plugins\formToken;


use src\cores\Event;
use src\traits\Ctrl;
use src\traits\Plugin;

/** 表单令牌 */
class Main extends Plugin
{
    protected function getConfigFilePath()
    {
        return false;
    }

    public function main()
    {
        Event::addListener(Ctrl::EVENT_ON_GET_FORM_TOKEN,function (Event $event){
            $token_key=\Yuri2::uniqueID();
            $token_value=\Yuri2::uniqueID();
            $s=maker()->session()->setPrefix('plugin.formToken');
            $s[$token_key]=$token_value;
            $event['rel']="<input type='hidden' name='form_token_$token_key' value='$token_value'>";
            $event['token_key']=$token_key;
            $event['token_value']=$token_value;
        });

        Event::addListener(Ctrl::EVENT_ON_CHECK_FORM_TOKEN,function (Event $event){
            $request=request();
            $is_right=false;
            foreach ($request as $k=>$v){
                if (!is_string($v)){continue;}
                if (preg_match('/^form_token_(\w+)$/',$k,$matches)){
                    $token_key=$matches[1];
                    $token_value=$v;
                    $s=maker()->session()->setPrefix('plugin.formToken');
                    $tokens=$s[''];
                    foreach ($tokens as $kk=>$vv){
                        if ($kk==$token_key and $vv==$token_value){
                            //bingo
                            $is_right=true;
                            unset($s[$token_key]);
                        }
                    }
                }
            }
            $event['is_right']=$is_right;
        });
        return true;
    }
    /** 从路由直接访问的方法 */
    public function direct()
    {
    }
}