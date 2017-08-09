<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/20
 * Time: 16:22
 * 自定义插件开发可以参考src/plugins/example
 */
return [

    /** --------------- 核心 ------------------- */

    'envCheck',//系统环境检测和指引
    'encrypt',//加密解密相关
    'route',//路由
    'routeSpecial',//特殊路由
    'errorCatch',//错误捕捉
    'debug',//调试
    'dispatch',//调度
    'infoJump',//信息跳转
    'view',//视图
    'logger',//日志
    'formToken',//表单令牌
    'cache',//缓存
    'admin',//gear管理员
    'arrayDb',//数组数据库
    'factory',//工厂函数集合：数组操作语言pinq、cookie、表格辅助excel、文件辅助file、格式转化format、图片操作image、请求的数据request、Int信号量semaphore、发送器sender、session、锁管理器locker


    /** ---------------- 非核心(按需加载) ---------------------- */

//    'captcha',//验证码
//    'email',//邮件
//    'neuralNetwork',//神经网络库
//    'webSocket',//webSocket
//    'dbThink',//tp3数据库操作
//    'db',//Notorm数据库操作
//    'ueditor',//ue富文本
//    'weiChat',//微信公众号、企业号
//    'qrCode',//二维码
//    'upload',//上传
//    'picMagic',//图片处理链接


];