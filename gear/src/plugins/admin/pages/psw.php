<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/4/17
 * Time: 11:21
 */
\src\cores\Event::fire(\src\plugins\admin\Main::EVENT_PLUGIN_ADMIN_ON_CHECK_ADMIN);
if (maker()->request()->isPost()){
    $psw=request('psw');
    maker()->file()->writeFile(dirname(__DIR__).'/password.php',md5($psw));
    maker()->sender()->jsAlert('Password has been changed.');
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title><?= lang("修改管理员密码","Change Admin Password") ?></title>
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
    <div class="container">
        <div class="panel panel-primary" style="width:50%;margin: 100px auto">
            <!-- Default panel contents -->
            <div class="panel-heading"><?= lang("修改管理员密码","Change Admin Password") ?></div>
            <!-- Table -->
            <div class="panel-body">
                <form action="" method="post" class="form-horizontal" role="form" id="frm">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="password" required class="form-control" id="psw1" name="psw" placeholder="<?= lang("新密码","New Password") ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="password" required class="form-control" id="psw2" placeholder="<?= lang("确认密码","Confirm Password") ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3" style="float: right">
                            <button type="submit" class="btn btn-primary"><?= lang("提交","Submit") ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $("#frm").submit(function () {
            var psw1=$("#psw1").val();
            var psw2=$("#psw2").val();
            if (psw1!==psw2){
                alert('<?=lang('两次密码输入不一致','Your confirmed password and new password do not match.')?>');
                return false;
            }
        })
    </script>
</body>
</html>