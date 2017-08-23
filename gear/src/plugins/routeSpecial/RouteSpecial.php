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
    private static $binds=[];

    public static function setRules($rules){
        self::$rules=$rules;
    }
    public static function setBinds($binds){
        self::$binds=$binds;
    }

    public function setRes($res){
        $this->res=$res;
    }

    /**
     * @return string
     */
    public function getRes(){
        return $this->res;
    }

    //获取路由别名（正解和反解）
    public function checkAlias(){
        $this->forbidFaviconRequest($this->res);
        foreach (self::$rules as $preg=>$closure){
            if (preg_match($preg,$this->res,$matches)){
                $rel=call_user_func_array($closure,$matches);
                if (is_string($rel)){
                    $this->res= $rel;
                }
            }
        }
    }

    //路由处理 域名->模块 (添加对应的模块名,Gear除外)
    public function checkDomainToModule(){
        foreach (self::$binds as $domain=>$module){
            if($domain===maker()->request()->getHost()){
                $is_match=preg_match('/^\/([^\/]+)/',$this->res,$matches);
                if($is_match){
                    $res_module=$matches[1];
                    if(maker()->format()->camelToHyphen($res_module)==='gear'){
                        break;
                    }
                }
                $this->res=DS.maker()->format()->camelToHyphen($module).$this->res;
                break;
            }
        }
    }

    //路由处理 模块->域名 (去掉多余的模块名)
    public function checkModuleToDomain(){
        foreach (self::$binds as $domain=>$module){
            if($domain===maker()->request()->getHost()){
                $is_match=preg_match('/^\/([^\/]+)/',$this->res,$matches);
                if($is_match){
                    $res_module=$matches[1];
                    if(maker()->format()->camelToHyphen($res_module)===maker()->format()->camelToHyphen($module)){
                        $this->res=\Yuri2::strReplaceOnce('/'.$matches[1],'',$this->res);
                        if(!$this->res){$this->res='/';}
                        break;
                    }
                }
            }
        }
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