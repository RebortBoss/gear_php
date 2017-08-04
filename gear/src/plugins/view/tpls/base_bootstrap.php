<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title>
        <block_title>模板页</block_title>
    </title>
    <block_ico>
        <?=V::import('common/img/gear.ico')?>
    </block_ico>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <?php if (Yuri2::isOldIE()) { ?>
        <script type="text/javascript" src="__PUBLIC__/common/js/jquery-1.11.3.min.js"></script>
    <?php }else{ ?>
        <script type="text/javascript" src="__PUBLIC__/common/js/jquery-2.2.4.min.js"></script>
    <?php } ?>
    <script type="text/javascript" src="__PUBLIC__/common/js/jquery.jsonp.js"></script>
    <script type="text/javascript" src="__PUBLIC__/common/js/jqueryMousewheel.js"></script>
    <script type="text/javascript" src="__PUBLIC__/common/js/Yuri2.js"></script>
    <!-- Bootstrap -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="__PUBLIC__/common/bootstrap3/js/bootstrap.min.js" ></script>
    <link href="__PUBLIC__/common/bootstrap3/css/bootstrap.min.css" rel="stylesheet">
    <link href="__PUBLIC__/common/css/animate.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="__PUBLIC__/common/bootstrap3/js/html5shiv-3.7.2-html5shiv.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/common/bootstrap3/js/respond.js-1.4.2-respond.min.js"></script>
    <![endif]-->
    <!-- Vue-->
    <script type="text/javascript" src="__PUBLIC__/common/js/vue.min.js"></script>
    <!--bootstrap-datetimepicker-->
    <link href="__PUBLIC__/common/bootstrap3/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <script type="text/javascript" src="__PUBLIC__/common/bootstrap3/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/common/bootstrap3/js/bootstrap-datetimepicker.zh-CN.js"></script>


    <block_head></block_head>
</head>
<body>
<block_body>
    <div class="container">
        <div class="row">
            <div class="jumbotron" style="background-color: white">
                <h1>Bootstap3模板页</h1>
                <p>本页已包含一些前端组件</p>
                <ul class="list-group">
                    <li class="list-group-item">bootstrap3</li>
                    <li class="list-group-item">jquery</li>
                    <li class="list-group-item">naplesHelper</li>
                </ul>
            </div>
        </div>
    </div>
</block_body>
</body>
</html>