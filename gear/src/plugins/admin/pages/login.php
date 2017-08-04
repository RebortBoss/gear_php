<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title>
        <?= lang("登录","login") ?>
    </title>
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
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="<?=URL_PUBLIC?>/common/bootstrap3/js/html5shiv-3.7.2-html5shiv.min.js"></script>
    <script type="text/javascript" src="<?=URL_PUBLIC?>/common/bootstrap3/js/respond.js-1.4.2-respond.min.js"></script>
    <![endif]-->

    <!--bootstrap-datetimepicker-->
    <link href="<?= URL_PUBLIC ?>/common/bootstrap3/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <script type="text/javascript"
            src="<?= URL_PUBLIC ?>/common/bootstrap3/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript"
            src="<?= URL_PUBLIC ?>/common/bootstrap3/js/bootstrap-datetimepicker.zh-CN.js"></script>
    <style>

    </style>
</head>
<body>
<div class="container" style="margin-top: 100px">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">

    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <form class="form-horizontal" role="form" action="<?=url('plugin/admin',['action'=>'check'])?>" target="_self" method="post">
            <!--<div id="legend" class="">-->
            <legend class=""><?= lang("GearPHP管理员登录","GearPHP Administrator Login") ?></legend>
            <!--</div>-->
            <div class="form-group">
                <label for="inputPassword" class="col-sm-2 control-label"><?= lang("密码","Password") ?></label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword" placeholder="<?= lang("密码","Password") ?>" name="psw" value="{{:psw }}">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label"><?= lang("验证码","Captcha") ?></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="<?= lang("验证码","Captcha") ?>" name="cap">
                </div>
                <div class="col-sm-4">
                    <?=V::captcha(150,35)?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input name="rem" type="checkbox" value="yes"> <?= lang("一周内记住我","Remember me in a week.") ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-10 col-sm-2">
                    <button type="submit" class="btn btn-info"><?= lang("登录","Login") ?></button>
                </div>
            </div>
            <input type="hidden" value="<?=request('jump')?>" name="jump">
            <?=V::formToken()?>
        </form>
    </div>
</div>
</body>
</html>