<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/21
 * Time: 10:44
 */

namespace src\plugins\factory\libs;

use src\cores\Event;

/** 发送各种输出 */
class Sender
{
    const EVENT_INFO_SUCCESS = 'EVENT_INFO_SUCCESS';
    const EVENT_INFO_WARNING = 'EVENT_INFO_WARNING';
    const EVENT_INFO_ATTENTION = 'EVENT_INFO_ATTENTION';
    const EVENT_INFO_ERROR = 'EVENT_INFO_ERROR';
    const EVENT_INFO_NOT_FOUND = 'EVENT_INFO_NOT_FOUND';

    public function echoTag($content, $tag = 'p')
    {
        echo "<$tag>$content</$tag>";
    }

    /**
     * echo 一个js alert()
     * @param $msg string 警告内容
     * @author yuri2
     */
    public function jsAlert($msg = '')
    {
        \Yuri2::jsAlert($msg);
    }

    /**
     * 发送子框架刷新父窗口的js代码
     */
    public function refreshFather()
    {
        \Yuri2::refreshFather();
    }

    /**
     * 更聪明的echo 将输出最适合的格式
     * @param $var mixed
     * @param $filters string
     * @return void
     */
    public function smarterEcho($var, $filters = '|')
    {
        \Yuri2::smarterEcho($var, $filters);
    }

    /**
     * 立即重定向，还可以在后面添加需要get出去的数组，将自动按照url格式生成get
     * @param $url string
     * @param $params array 需要发送get的关联数组
     * @param $code int 需要发送的状态码
     * @author yuri2
     */
    public function redirect($url, $params = [], $code = 302)
    {
        \Yuri2::redirect($url, $params, $code);
    }

    /**
     * GET curl请求
     * @param string $url
     * @param $timeout int
     * @return string content
     */
    public function curlGet($url,$timeout=9)
    {
        return \Yuri2::http_get($url,$timeout);
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    public function curlPost($url, $param = [], $post_file = false)
    {
        return \Yuri2::http_post($url, $param, $post_file);
    }

    /**
     * https 发起post多发请求
     * 2016/5/25, by CleverCode, Create
     * @param array $nodes url和参数信息。$nodes = array
     *                                              (
     *                                                 [0] = > array
     *                                                   (
     *                                                       'url' => 'http://www.baidu.com',
     *                                                       'data' => '{"a":1,"b":2}'
     *                                                   ),
     *                                                 [1] = > array
     *                                                   (
     *                                                       'url' => 'http://www.baidu.com',
     *                                                   )
     *                                                 ....
     *                                              )
     * @param int $timeOut 超时设置
     * @return array
     */
    public function curlMulti($nodes, $timeOut = 5)
    {
        return \Yuri2::httpMulti($nodes, $timeOut);
    }

    /**
     * 发送状态码
     * HTTP Protocol defined status codes
     * HTTP协议状态码,调用函数时候只需要将$num赋予一个下表中的已知值就直接会返回状态了。
     * @param int $num
     */
    function httpStateCode($num)
    {
        \Yuri2::httpStateCode($num);
    }

    /**
     * 发送成功提示的事件
     * @param $msg string|array
     * @param $url_jump string
     * @param $count_down int
     */
    function success($msg = '', $url_jump = '', $count_down = 0)
    {
        $url_self=url();
        Event::trigger(self::EVENT_INFO_SUCCESS, new Event(compact('msg','url_self', 'url_jump', 'count_down')));
    }

    /**
     * 发送警告提示的事件
     * @param $msg string|array
     * @param $url_jump string
     * @param $count_down int
     */
    function warning($msg = '', $url_jump = '', $count_down = 0)
    {
        $url_self=url();
        Event::trigger(self::EVENT_INFO_WARNING,new Event(compact('msg','url_self', 'url_jump', 'count_down')));
    }

    /**
     * 发送错误提示的事件
     * @param $msg string|array
     * @param $url_jump string
     * @param $count_down int
     */
    function error($msg = '', $url_jump = '', $count_down = 0)
    {
        $url_self=url();
        Event::trigger(self::EVENT_INFO_ERROR,new Event(compact('msg','url_self', 'url_jump', 'count_down')));
    }

    /**
     * 发送消息提示的事件
     * @param $msg string|array
     * @param $url_jump string
     * @param $count_down int
     */
    function attention($msg = '', $url_jump = '', $count_down = 0)
    {
        $url_self=url();
        Event::trigger(self::EVENT_INFO_ATTENTION,new Event(compact('msg','url_self', 'url_jump', 'count_down')));
    }

    /**
     * 发送消息提示的事件
     * @param $msg string
     * @param $url_jump string
     * @param $count_down int
     */
    function notFound($msg = '', $url_jump = '', $count_down = 0)
    {
        $url_self=url();
        Event::trigger(self::EVENT_INFO_NOT_FOUND, new Event(compact('msg','url_self', 'url_jump', 'count_down')));
    }

    /**
     * 从webService获取数据
     * @param $url string
     * @param $method string
     * @param $params array [a,b,c...]
     * @return string
     */
    function webServiceGet($url,$method,$params){
        if (!class_exists('\SoapClient')){
            \Yuri2::throwException('WebService requires curl and soap modules.');
            return '';
        }else{
            $client=new \SoapClient( $url,[ 'trace' => true, 'exceptions' => true ] );
            return call_user_func_array([$client,$method],$params);
        }
    }

    /** 立即输出到浏览器 */
    function flush(){
        \Yuri2::flush();
    }

    /**
     * 发送可以缓存的header
     * @param $expire int
     * */
    function headerSendCachePage($expire=300){
        \Yuri2::headerSendCachePage($expire);
    }
}