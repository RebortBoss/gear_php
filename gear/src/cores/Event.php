<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/20
 * Time: 13:46
 */

namespace src\cores;

/** 事件管理 */
class Event implements \ArrayAccess
{
    private static $callbacks=[]; //回调方法
    private static $fozenList=[]; //冻结表
    private static $isFreezeAll=false; //冻结所有事件 开关

    /**
     * 添加一个事件监听
     * @param $event_name string 事件名
     * @param $callable callable 回调函数
     * @param $id string
     */
    public static function addListener($event_name, callable $callable,$id=null){
        if ($id){
            self::$callbacks[$event_name][$id]=$callable;
        }else{
            self::$callbacks[$event_name][]=$callable;
        }
    }

    /**
     * 移除一个事件监听
     * @param $event_name string 事件名
     * @param $id string|int
     */
    public static function removeListener($event_name,$id){
        unset(self::$callbacks[$event_name][$id]);
    }

    /**
     * 触发一个事件
     * @param $event_name string 事件名
     * @param $event Event
     */
    public static function fire($event_name,Event $event=null){
        if (!self::isFrozen($event_name) and !self::$isFreezeAll) {
            if (isset(self::$callbacks[$event_name])){
                if (is_null($event)){$event=new Event([]);}
                foreach (self::$callbacks[$event_name] as $id =>$callback){
                    call_user_func_array($callback,[$event]);
                    if (!$event->isAllowedSpread()){
                        break;
                    }
                }
            }
        }
    }

    /**
     * 冻结一个事件。该事件的绑定和触发都将失效。慎用
     * @param $event_name string 事件名
     */
    public static function freeze($event_name){
        if (!self::isFrozen($event_name)){
            self::$fozenList[]=$event_name;
        }
    }

    /**
     * 冻结所有事件。慎用
     */
    public static function freezeAll(){
        self::$isFreezeAll=true;
    }

    /**
     * 停止冻结所有事件。
     */
    public static function unfreezeAll(){
        self::$isFreezeAll=false;
    }

    /**
     * 解冻一个事件。
     * @param $event_name string 事件名
     */
    public static function unfreeze($event_name){
        foreach(self::$fozenList as $k=>$v){
            if($v == $event_name){
                unset(self::$fozenList[$k]);
            }
        }
    }

    /**
     * 检查是否事件被冻结
     * @param $event_name string 事件名
     * @return bool
     */
    public static function isFrozen($event_name){
        return in_array($event_name,self::$fozenList);
    }

    public $data=[]; //公开数据域
    private $spreadAllowed=true; //允许继续传播

    /** 初始化，赋值数据
     * @param $data array
     */
    public function __construct($data=[])
    {
        $this->data=$data;
    }

    /** 停止事件传播 */
    public function stopSpread(){
        $this->spreadAllowed=false;
    }

    /** 是否允许传播
     * @return bool
     */
    public function isAllowedSpread(){
        return $this->spreadAllowed;
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset]=$value;
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }
}