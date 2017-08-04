<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/24
 * Time: 9:20
 */

namespace src\cores;


class Config
{
    const LANG='lang'; // CN/EN
    const DEBUG='debug';
    const TIMEZONE='timezone';
    const IGNORE_USER_ABORT='ignore_user_abort';
    const LOG_VISITOR_INFO='log_visitor_info';
    const API_MODE='api_mode';
    const API_FORMAT='api_format';
    const ERROR_LOG_LV='error_log_lv';
    const SHOW_DEBUG_BTN='show_debug_btn';
    const TIME_LIMIT='time_limit';
    const MEMORY_LIMIT='memory_limit';
    const MAX_INPUT_TIME='max_input_time';
    const POST_MAX_SIZE='post_max_size';
    const UPLOAD_MAX_FILESIZE='upload_max_filesize';
    const IGNORE_REPEATED_ERRORS='ignore_repeated_errors';
    const IGNORE_REPEATED_SOURCE='ignore_repeated_source';
    const XDEBUG_VAR_DISPLAY_MAX_CHILDREN='xdebug_var_display_max_children';
    const XDEBUG_VAR_DISPLAY_MAX_DATA='xdebug_var_display_max_data';
    const XDEBUG_VAR_DISPLAY_MAX_DEPTH='xdebug_var_display_max_depth';

    public $configs=[];

    public function setConfig($key,$value){
        $this->configs[$key]=$value;
    }

    public function getConfig($key){
        return isset($this->configs[$key])?$this->configs[$key]:null;
    }
}