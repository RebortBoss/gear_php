<?php
switch (request('type')) {
    case 'success':
        $color = '#86c68d';
        $class = 'success';
        $head_title=lang('成功','Success');
        break;
    case 'warning':
        $color = 'rgba(255, 183, 0, 0.61)';
        $class = 'warning';
        $head_title=lang('警告','Warning');
        break;
    case 'error':
        $color = 'rgba(255, 89, 0, 0.86)';
        $class = 'danger';
        $head_title=lang('错误','Error');
        break;
    case 'notFound':
        $color = 'gray';
        $class = 'default';
        $head_title=lang('找不到页面','404');
        break;
    default:
        $color = 'rgba(0, 178, 255, 0.73)';
        $class = 'info';
        $head_title=lang('提示','Information');
        break;
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title><?= $head_title ?></title>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <?php if (Yuri2::isOldIE()) { ?>
        <script type="text/javascript" src="<?= URL_PUBLIC ?>/common/js/jquery-1.11.3.min.js"></script>
    <?php }else{ ?>
        <script type="text/javascript" src="<?= URL_PUBLIC ?>/common/js/jquery-2.2.4.min.js"></script>
    <?php } ?>
    <script type="text/javascript" src="<?= URL_PUBLIC ?>/common/js/jqueryMousewheel.js"></script>
    <script type="text/javascript" src="<?= URL_PUBLIC ?>/common/js/Yuri2.js"></script>
    <!-- Bootstrap -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="<?= URL_PUBLIC ?>/common/bootstrap3/js/bootstrap.min.js"></script>
    <link href="<?= URL_PUBLIC ?>/common/bootstrap3/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= URL_PUBLIC ?>/common/css/animate.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="<?=URL_PUBLIC?>/common/bootstrap3/js/html5shiv-3.7.2-html5shiv.min.js"></script>
    <script type="text/javascript" src="<?=URL_PUBLIC?>/common/bootstrap3/js/respond.js-1.4.2-respond.min.js"></script>
    <![endif]-->
    <style>
        body {
            background-size: 50px 50px;
            background-color: <?=$color?>;
            background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, .2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .2) 50%, rgba(255, 255, 255, .2) 75%, transparent 75%, transparent);
            background-image: -moz-linear-gradient(45deg, rgba(255, 255, 255, .2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .2) 50%, rgba(255, 255, 255, .2) 75%, transparent 75%, transparent);
            background-image: -ms-linear-gradient(45deg, rgba(255, 255, 255, .2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .2) 50%, rgba(255, 255, 255, .2) 75%, transparent 75%, transparent);
            background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, .2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .2) 50%, rgba(255, 255, 255, .2) 75%, transparent 75%, transparent);
            background-image: linear-gradient(45deg, rgba(255, 255, 255, .2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .2) 50%, rgba(255, 255, 255, .2) 75%, transparent 75%, transparent);
            overflow-y: hidden;
        }
        @-webkit-keyframes 'drop_down' {
            0% {
                bottom: 200px;
            }
            70% {
                bottom: -20px;
            }
            100% {
                bottom: 0;
            }
        }
        .panel {
            background: rgba(233, 233, 233, 0.7);
            box-shadow: rgba(72, 73, 72, 0.56) 5px 5px 4px;
        }

        .panel .info {
            word-break: break-all
        }


    </style>
</head>
<body>
<div class="container">
    <div class="row" style="padding-top: 30px">
        <div class="col-md-5 hidden-xs  hidden-sm " style="position: relative;left: -150px">
            <img id="pic" style="width:100%;" class="animated hidden" src="<?= URL_PUBLIC ?>/common/img/gear_bulb.png">
        </div>
        <div class="col-md-7 col-sm-7" style="position: absolute;top: 30%;right: 10%;max-width: 700px">
            <div class="animated zoomInDown panel panel-<?= $class ?> ">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= request('title') ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div style="min-height: 80px;max-height: 450px" class="info"><?= request('info') ?></div>
                            <div style="float: right">
                                <?php if (request('url_jump') == 'back') { ?>
                                    <a class="btn btn-info" id="back" href="#"
                                       onclick="history.go(-1);return false;" title="上一页"><?= lang("返回","Previous") ?><span
                                                id="wait"><?= request('count_down') ? request('count_down') : '' ?></span></a>
                                <?php } elseif (request('url_jump')) { ?>
                                    <a id="jump" href="<?= request('url_jump') ?>"
                                       class="btn btn-success"><?= lang("跳转","Jump") ?>
                                        <span id="wait"><?= request('count_down') ? request('count_down') : '' ?></span></a>
                                <?php } ?>
                                <a href="<?= request('url_self') ?>" type="button" class="btn btn-warning"><?= lang("重试","Retry") ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="hidden-xs navbar navbar-default navbar-fixed-bottom" role="navigation" style="background-color: transparent;border: 0">
        <ul class="nav navbar-nav navbar-right">
            <li >
                <a style="color: white" href="<?=url('Home')?>"><?= lang("主页","Home") ?></a>
            </li>
            <li >
                <a style="color: white" href="https://git.coding.net/yuri2/gear_php.git" target="_blank"><?= lang("访问GearPHP","Visit GearPHP") ?>&nbsp;&nbsp;&nbsp;&nbsp;</a>
            </li>
        </ul>
    </nav>
</div>
<script>
    $(function () {
        setTimeout(function () {
            var e=$("#pic");
            e.removeClass('hidden');
            e.addClass('flipInY');
        },500);
        <?php if (request('url_jump') and request('count_down')){?>
        var wait = document.getElementById('wait'),
            href = document.getElementById('jump') ? document.getElementById('jump').href : '';
        var interval = setInterval(function () {
            var time = --wait.innerHTML;
            if (time <= 0) {
                <?php if(request('url_jump') == 'back'){ ?>
                location.href = 'javascript:history.go(-1);';
                <?php }else{?>
                location.href = href;
                <?php }?>
                clearInterval(interval);
            }
        }, 1000);
        <?php }?>
    })
</script>
</body>
</html>