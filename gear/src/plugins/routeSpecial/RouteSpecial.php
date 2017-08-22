<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\routeSpecial;


use src\cores\Event;

class RouteSpecial
{
    private $res='';
    private static $rules=[];

    public static function setRules($rules){
        self::$rules=$rules;
    }

    public function setRes($res){
        $this->res=$res;
    }

    public function getAlias(){
        $this->forbidFaviconRequest($this->res);
        foreach (self::$rules as $preg=>$closure){
            if (preg_match($preg,$this->res,$matches)){
                $rel=call_user_func_array($closure,$matches);
                if (is_string($rel)){
                    $this->res= $rel;
                }
            }
        }
        return $this->res;
    }

    //屏蔽favicon的请求
    private function forbidFaviconRequest($res){
        $preg='/^\/?favicon.ico$/i';
        if(preg_match($preg,$res)){
            maker()->sender()->httpStateCode(404);
            Event::freezeAll();
            exit('Can not find favicon.ico on this server.');
        }
    }

}