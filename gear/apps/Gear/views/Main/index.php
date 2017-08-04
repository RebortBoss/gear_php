<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title>GEAR_PHP</title>
    <link rel='Shortcut Icon' type='image/x-icon' href='__PUBLIC__/common/img/favicon.ico'>
    <script type="text/javascript" src="__PUBLIC__/common/win10-ui/js/jquery-2.2.4.min.js"></script>
    <link href="__PUBLIC__/common/win10-ui/css/animate.css" rel="stylesheet">
    <script type="text/javascript" src="__PUBLIC__/common/win10-ui/component/layer-v3.0.3/layer/layer.js"></script>
    <link rel="stylesheet" href="__PUBLIC__/common/win10-ui/component/font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="__PUBLIC__/common/win10-ui/css/default.css" rel="stylesheet">
    <script type="text/javascript" src="__PUBLIC__/common/win10-ui/js/win10.js"></script>
    <script type="text/javascript" src="__PUBLIC__/common/js/vue.js"></script>
    <style>
        * {
            font-family: "Microsoft YaHei", 微软雅黑, "MicrosoftJhengHei", 华文细黑, STHeiti, MingLiu
        }
    </style>
    <script>
        Win10.onReady(function () {

            //设置壁纸
            Win10.setBgUrl({
                main: "<?= $bing ? $bing['url'] : URL_PUBLIC . '/apps/Gear/img/wallpaper/2.jpg'?> ",
                mobile: '__PUBLIC__/common/win10-ui/img/wallpapers/mobile.jpg',
            });

            Win10.setAnimated([
                'animated flip',
                'animated bounceIn',
            ], 0.01);

            <?php if(\src\plugins\admin\Main::isDefaultPsw()):?>
            setTimeout(function () {
                Win10.newMsg('安全风险', '您使用的是默认的GearPHP管理员密码，请尽快修改为其他密码。');
            }, 1500);
            <?php endif;?>

            <?php if($bing):?>
            setTimeout(function () {
                Win10.newMsg('<?=$bing['title']?>', '<?=$bing['content']?>');
            }, 3000);
            <?php endif;?>

            var block_dir_size =new Vue({
                el:'#block-dir-size',
                data:{
                    size_gear:'统计中',
                    size_public:'统计中',
                    size_runtime:'统计中',
                },
                created:function () {
                    setTimeout(function () {
                        $.post('<?=url_based('getDirSize')?>',{},function (data) {
                            block_dir_size.size_gear=data.size_gear;
                            block_dir_size.size_public=data.size_public;
                            block_dir_size.size_runtime=data.size_runtime;
                        },'json')
                    }, 1000);
                }
            })
        });
    </script>
</head>
<body>
<div id="win10">
    <div class="desktop">
        <div id="win10-shortcuts">
            <div class="shortcut"
                 onclick="Win10.openUrl('<?= url_based('favorites/index') ?>','<img class=\'icon\' src=\'__PUBLIC__/apps/Gear/img/icon/favorite.png\' /><?= lang("收藏夹", "favorites") ?>')">
                <img class="icon" src="__PUBLIC__/apps/Gear/img/icon/favorite.png"/>
                <div class="title"><?= lang("收藏夹","Favorites") ?></div>
            </div>
            <div class="shortcut"
                 onclick="Win10.openUrl('<?= url_based('tools/appMap') ?>','<img class=\'icon\' src=\'__PUBLIC__/apps/Gear/img/icon/appMap.png\' /><?= lang("网站地图", "Website Map") ?>')">
                <img class="icon" src="__PUBLIC__/apps/Gear/img/icon/appMap.png"/>
                <div class="title"><?= lang("网站地图","Website Map") ?></div>
            </div>
            <div class="shortcut"
                 onclick="Win10.openUrl('<?= url_based('tools/codeTest') ?>','<img class=\'icon\' src=\'__PUBLIC__/apps/Gear/img/icon/codeTest.png\' /><?= lang("代码测试", "Code Test") ?>')">
                <img class="icon" src="__PUBLIC__/apps/Gear/img/icon/codeTest.png"/>
                <div class="title"><?= lang("代码测试","Code Test") ?></div>
            </div>
            <div class="shortcut"
                 onclick="Win10.openUrl('<?= url_based('tools/webShell') ?>','<img class=\'icon\' src=\'__PUBLIC__/apps/Gear/img/icon/webshell.png\' />WebShell')">
                <img class="icon" src="__PUBLIC__/apps/Gear/img/icon/webshell.png"/>
                <div class="title">WebShell</div>
            </div>
            <div class="shortcut"
                 onclick="Win10.openUrl('<?= url('plugin/logger', ['log' => 'today']) ?>','<img class=\'icon\' src=\'__PUBLIC__/apps/Gear/img/icon/logger.png\' /><?= lang("今日日志", "Logs Today") ?>')">
                <img class="icon" src="__PUBLIC__/apps/Gear/img/icon/logger.png"/>
                <div class="title"><?= lang("今日日志","Logs Today") ?></div>
            </div>
            <div class="shortcut"
                 onclick="Win10.openUrl('<?= url('plugin/debug') ?>','<img class=\'icon\' src=\'__PUBLIC__/apps/Gear/img/icon/reports.png\' /><?= lang("DEBUG报告", "Debug Reports") ?>')">
                <img class="icon" src="__PUBLIC__/apps/Gear/img/icon/reports.png"/>
                <div class="title"><?= lang("DEBUG报告","Debug Reports") ?></div>
            </div>
            <div class="shortcut"
                 onclick="Win10.openUrl('<?= url_based('Service/index') ?>','<img class=\'icon\' src=\'__PUBLIC__/apps/Gear/img/icon/task.png\' /><?= lang("常驻服务", "Gear Service") ?>')">
                <img class="icon" src="__PUBLIC__/apps/Gear/img/icon/task.png"/>
                <div class="title"><?= lang("常驻服务","Gear Service") ?></div>
            </div>
        </div>
    </div>
    <div id="win10-menu" class="hidden">
        <div class="list win10-menu-hidden animated animated-slideOutLeft">
            <div class="item"><span class=" icon fa fa-gavel fa-fw"></span><?= lang("脚手架","Scaffold") ?></div>
            <div class="sub-item" onclick="Win10.openUrl('<?=url_based('scaffold/module')?>','<?= lang("生成模块","Module Builder") ?>')"><?= lang("生成模块","Module Builder") ?></div>
            <div class="sub-item" onclick="Win10.openUrl('<?=url_based('scaffold/ctrl')?>','<?= lang("生成控制器","Controller Builder") ?>')"><?= lang("生成控制器","Controller Builder") ?></div>
            <div class="item"><span class=" icon fa fa-wrench fa-fw"></span><?= lang("系统工具","System Tools") ?></div>
            <div class="sub-item" onclick="Win10.openUrl('<?=url('plugin/logger')?>','<?= lang("查看日志","Read Logs") ?>')"><?= lang("查看日志","Read Logs") ?></div>
            <div class="sub-item" onclick="Win10.openUrl('<?=url('plugin/envCheck')?>','<?= lang("环境检测","Env CHeck") ?>')"><?= lang("环境检测","Env CHeck") ?></div>
            <div class="sub-item" onclick="Win10.openUrl('<?=url('plugin/admin',['action'=>'psw'])?>','<?= lang("修改密码","Change Password") ?>')"><?= lang("修改密码","Change Password") ?></div>
            <div class="item" onclick="layer.confirm('<?= lang("确认要注销吗?您将不再是Gear PHP的管理员","Are you sure you want to logout? You will no longer be an administrator for Gear PHP.") ?>', {icon: 3, title:'<?= lang("提示","Attention") ?>'}, function(index){window.location.href='<?=url('plugin/admin',['action'=>'logout'])?>';layer.close(index);});"><span class=" icon fa fa-user-o fa-fw"></span><?= lang("注销","Logout") ?></div>
            <div class="item" onclick="layer.confirm('<?= lang("确认要关闭本页吗?","Are you sure you want to close this page?") ?>', {icon: 3, title:'<?= lang("提示","Attention") ?>'}, function(index){Win10.exit();layer.close(index);});"><span class=" icon fa fa-window-close fa-fw"></span><?= lang("退出","Exit") ?></div>
        </div>
        <div class="blocks">
            <div class="menu_group">
                <div class="title">信息中心</div>
                <div class="block" loc="1,1" size="6,3">
                    <div class="content">
                        <img style="width: 30%;margin:10%;float: left" src="__PUBLIC__/apps/Gear/img/icon/floppy_disk.png" />
                        <div id="block-dir-size" style="font-size: 12px;line-height: 44px;float: left">
                            <div>核心目录:<span>{{size_gear}}</span></div>
                            <div>资源目录:<span>{{size_public}}</span></div>
                            <div>运行时目录:<span>{{size_runtime}}</span></div>
                        </div>
                    </div>
                </div>
                <div class="block" loc="1,4" size="3,2" onclick="Win10.openUrl('<?=url_based('readme')?>','README')">
                    <div class="content">
                        <div style="line-height: 88px;font-size: 16px;text-align: center"><i class="fa fa-file-text"></i> README</div>
                    </div>
                </div>
                </div>
        </div>
        <div id="win10-menu-switcher"></div>
    </div>
    <div id="win10_command_center" class="hidden_right">
        <div class="title">
            <h4 style="float: left"><?= lang("消息中心","Message Center") ?></h4>
            <span id="win10_btn_command_center_clean_all"><?= lang("全部清除","Clear All") ?></span>
        </div>
        <div class="msgs"></div>
    </div>
    <div id="win10_task_bar">
        <div id="win10_btn_group_left" class="btn_group">
            <div id="win10_btn_win" class="btn"><span class="fa fa-windows"></span></div>
            <div class="btn" id="win10-btn-browser"><span class="fa fa-internet-explorer"></span></div>
        </div>
        <div id="win10_btn_group_middle" class="btn_group"></div>
        <div id="win10_btn_group_right" class="btn_group">
            <div class="btn" id="win10_btn_time">
<!--                0:00<br/>-->
<!--                1993/8/13-->
            </div>
            <div class="btn" id="win10_btn_command"><span id="win10-msg-nof" class="fa fa-comment-o"></span></div>
            <div class="btn" id="win10_btn_show_desktop"></div>
        </div>
    </div>

</div>
</body>
</html>