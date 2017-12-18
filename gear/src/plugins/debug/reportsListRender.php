<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title>
        <?= lang("详细报告","reports") ?>
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
        td{word-break: break-all;vertical-align: middle}
    </style>
</head>
<body>
<gear-block-body>
    <div class="container">
        <div class="panel panel-primary" style="margin-top: 20px">
            <!-- Default panel contents -->
            <div class="panel-heading"><?= lang("近期报告","Recent Reports") ?></div>
            <!-- Table -->
            <table class="table table-bordered table-responsive table-hover">
                <tr>
                    <th>URL</th>
                    <th><?= lang("错误数目","Error Count") ?></th>
                    <th><?= lang("时间","Time") ?></th>
                </tr>
                <?php foreach ($rows as $row) {?>
                    <tr <?php  if ($row['errors']>0){echo 'style="background-color:#fcf8e3"';}?>>
                        <td style="max-width: 300px"><a href="<?= $row['link']?>" target="_blank"><?= $row['url']?></a></td>
                        <td><?= $row['errors']?></td>
                        <td style="color: gray"><?= $row['date']?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</gear-block-body>
</body>
</html>