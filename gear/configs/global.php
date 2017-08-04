<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/24
 * Time: 9:18
 */

//全局行为配置
use src\cores\Config;

return [
    Config::LANG=>'AUTO', //language: ZH or EN or AUTO
    Config::DEBUG=>Yuri2::isLocal(),
    Config::TIMEZONE=>'PRC',
    Config::IGNORE_USER_ABORT=>false,
    Config::LOG_VISITOR_INFO=>false,
    Config::API_MODE=>false, //api模式
    Config::API_FORMAT=>'json', //api模式下返回值格式 如ajax返回json
    Config::ERROR_LOG_LV=>10,
    Config::SHOW_DEBUG_BTN=>true,

    Config::TIME_LIMIT=>60,//超时时间  单位s
    Config::MEMORY_LIMIT=>1024,//内存限制 单位M
    Config::MAX_INPUT_TIME=>20,//表单提交最大时间 单位s
    Config::POST_MAX_SIZE=>50,//POST提交数据上限 单位M
    Config::UPLOAD_MAX_FILESIZE=>50,//文件上传的最大文件上限 单位M
    Config::IGNORE_REPEATED_ERRORS=>'on',//忽略重复的错误
    Config::IGNORE_REPEATED_SOURCE=>'on',//忽略重复的错误来源
    Config::XDEBUG_VAR_DISPLAY_MAX_CHILDREN=>256,//xdebug 最多孩子节点数
    Config::XDEBUG_VAR_DISPLAY_MAX_DATA=>128,//xdebug 最大字节数
    Config::XDEBUG_VAR_DISPLAY_MAX_DEPTH=>16,//xdebug 最大深度


];