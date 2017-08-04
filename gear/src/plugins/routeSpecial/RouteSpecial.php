<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\routeSpecial;


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
        foreach (self::$rules as $preg=>$closure){
            if (preg_match($preg,$this->res,$matches)){
                $rel=call_user_func_array($closure,$matches);
                if (is_string($rel)){
                    return $rel;
                }
            }
        }
        return $this->res;
    }
}