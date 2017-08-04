<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\errorCatch;


use src\cores\Config;

class ErrorCatch

{

    private static $errors = [];//保存错误信息

    /** 结束处理函数  */
    public static function onShutdown()
    {
        $error = error_get_last();
        if ($error) {
            $msgArr = explode('\n', $error['message']);
            $msg = array_shift($msgArr);
            $errno = $error['type'];
            $type = self::errLevelMap($errno);
            $errstr = $msg;
            $errfile = $error['file'];
            $errline = $error['line'];
            if (self::isNeedErrLog($errno)) {
                self::$errors[] = [
                    'errno' => $errno,
                    'type' => $type,
                    'msg' => $errstr,
                    'file' => $errfile,
                    'line' => $errline,
                    'trace'=>debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS),
                ];
                $log_url=!IS_CLI?"where url is ".url():'';
                maker()->logger()->err(" [".ID."]"." [$errno] $type.$errstr.Error on line $errline in $errfile $log_url");
            }
        }

        if (self::$errors) {
            if (!config(Config::API_MODE) and config(Config::DEBUG) and config(Config::SHOW_DEBUG_BTN)) {
                self::displayError();
            } else {
                $isNeedAlert = false;
                foreach (self::$errors as $error) {
                    $errno = $error['errno'];
                    if (self::isNeedErrLog($errno)) {
                        $isNeedAlert = true;
                    }
                }
                if ($isNeedAlert and !IS_CLI) {
                    if (config(Config::DEBUG)){
                        maker()->sender()->error(['ERROR','[ '.$error['errno'].' ] '.$error['msg']]);
                    }else{
                        maker()->sender()->error(['SORRY','There is an error occurred on this page.']);
                    }
                }
            }
        }
    }

    /**
     * error handle 错误处理
     * @param $errno int 错误级别
     * @param $errstr string 错误信息
     * @param $errfile string 错误文件
     * @param $errline int 错误行号
     */
    public static function  onError($errno, $errstr, $errfile, $errline)
    {
        $type = self::errLevelMap($errno);
        if (self::isNeedErrLog($errno)) {
            self::$errors[] = [
                'errno' => $errno,
                'type' => $type,
                'msg' => $errstr,
                'file' => $errfile,
                'line' => $errline,
                'trace'=>debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS),
            ];
            maker()->logger()->err(" [".ID."]"." [$errno] $type.$errstr.Error on line $errline in $errfile where url is ".url());
        }
    }

    /**
     * 异常处理
     * @param $exc \Exception
     */
    public static function onException($exc)
    {
        $errno = $exc->getCode() ? $exc->getCode() : 1;
        $type = self::errLevelMap($errno);
        $errstr = $exc->getMessage();
        $errfile = $exc->getFile();
        $errline = $exc->getLine();
        $trace=$exc->getTrace();
        foreach ($trace as &$item){
            unset($item['args']);
        }

        if (self::isNeedErrLog($errno)) {
            self::$errors[] = [
                'errno' => $errno,
                'type' => $type,
                'msg' => $errstr,
                'file' => $errfile,
                'line' => $errline,
                'trace'=>$trace,
            ];
            maker()->logger()->err(" [".ID."]"." [$errno] $type.$errstr.Error on line $errline in $errfile where url is ".url());
        }
    }

    /** 根据错误数目来改变按钮样式 */
    public static function displayError()
    {
            $num = count(self::$errors);
            echo "
<script>
    document.getElementById('naples-trace-btn').style.backgroundColor='#c83235';document.getElementById('naples-trace-btn').innerHTML+=' [$num]';
    document.title = '[Err:$num] '+document.title;
</script>";
    }

    /**
     * 错误代码对照表
     * @param $level int|string 级别
     * @return string
     */
    public static function errLevelMap($level)
    {
        /*$map = [
            '1' => '运行时致命的错误', //1
            '2' => '运行时非致命的错误', //2
            '4' => '编译时语法解析错误', //3
            '8' => '运行时通知', //4
            '16' => 'PHP 初始化启动过程中发生的致命错误', //5
            '32' => 'PHP 初始化启动过程中发生的警告 ', //6
            '64' => '致命编译时错误', //7
            '128' => '编译时警告', //8
            '256' => '用户产生的错误信息', //9
            '512' => '用户产生的警告信息', //10
            '1024' => '用户产生的通知信息', //11
            '2048' => 'PHP 对代码的修改建议', //12
            '4096' => '可被捕捉的致命错误', //13
            '8192' => '运行时通知', //14
            '16384' => '用户产生的警告信息', //15
            '32767' => 'E_STRICT 触发的所有错误和警告信息', //16
        ];*/
        $map = [
            '1' => 'Fatal run-time errors',
            '2' => 'Non-fatal run-time errors',
            '4' => 'Compile-time parse errors',
            '8' => 'Run-time notices',
            '16' => 'Fatal errors at PHP startup',
            '32' => 'Non-fatal errors at PHP startup',
            '64' => 'Fatal compile-time errors',
            '128' => 'Non-fatal compile-time errors',
            '256' => 'Fatal user-generated error',
            '512' => 'Non-fatal user-generated warning',
            '1024' => 'User-generated notice',
            '2048' => 'Run-time notices',
            '4096' => 'Catchable fatal error',
            '8192' => 'All errors and warnings, except level E_STRICT',
            '16384' => 'User-generated warning',
            '32767' => 'All errors and warnings trigger by level E_STRICT',
        ];

        return isset($map[$level]) ? $map[$level] : 'Unknown error';

    }

    /**
     * 检查错误是否需要被记录
     * @param $errno int 错误级别
     * @return bool
     */
    private static function isNeedErrLog($errno)
    {
        $error_log_lv = config(Config::ERROR_LOG_LV) ? config(Config::ERROR_LOG_LV) : 0;
        $lv = pow(2, $error_log_lv);
        if ($errno <= $lv) {
            return true;
        } else {
            return false;
        }
    }

    /** 获取所有错误 */
    public static function getErrors()
    {
        return self::$errors;
    }

    /** 显示按钮 */
    public static function displayTrace(){
        $timeSpend=getTimeSpend();
        $reportUrl=url('plugin/debug',['id'=>ID]);
        $div=<<<EOT
<a id="naples-trace-btn" title='打开详细报告' href='$reportUrl' target='_blank' style="
    border-radius: 12px 0 0 0;
    cursor: pointer;
    font-size:13px;
    background-color: #667e69;
    color: aliceblue;
    position: fixed;
    bottom: 0;
    right: 0;
    text-align: center;
    width: auto;
    padding:5px;
    height: auto;
    line-height: 20px;
    margin:0;
    text-decoration: none;
    ">
$timeSpend ms</a>
EOT;
        echo $div;

    }



}