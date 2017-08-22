<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title>
        <?= lang("报告详情","Report Details") ?>
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
        tr, td {
            word-break: break-all;
        }
        .dump_box{position: relative;}
        .dump_title{cursor: pointer}
        .dump_content{position: absolute;left: 40px}
    </style>
    <script>
        $(function () {
            $('.dump_title').click(function () {
                $(this).siblings('.dump_content').toggleClass('hidden');
            })
        });
    </script>
</head>
<body>
<block_body>
    <div class="container">
        <div class="panel panel-primary" style="margin-top: 20px">
            <!-- Default panel contents -->
            <div class="panel-heading"><?= lang("基本信息","Basic Information") ?></div>
            <!-- Table -->
            <table class="table table-bordered table-responsive table-hover">
                <?php
                $num_basic = count($report['basic']);
                if ($num_basic % 2 != 0) {
                    $report['basic'][''] = '';
                }
                $i = 0;
                foreach ($report['basic'] as $key => $value) {
                    $i++;
                    if ($i % 2 != 0)
                        echo "<tr>";
                    echo "<th style='width: 15%'>$key</th>";
                    echo "<td style='width:35%;' class='dump_box'>";
                    if (Yuri2::isEchoAble($value)){
                        if ($key=='timestamp'){
                            echo htmlspecialchars(date('Y-m-d H:i:s',$value));
                        }else{
                            echo htmlspecialchars($value);
                        }
                    }else{
                        echo '<div >';
                        echo '<span class="dump_title ">...</span>';
                        echo '    <div class="dump_content hidden">';
                        dump($value);
                        echo '    </div>';
                        echo '</div>';
                    }
                    echo "</td>";
                    if ($i % 2 == 0)
                        echo "</tr>";
                }
                ?>
            </table>
        </div>
        <div class="panel panel-info">
            <!-- Default panel contents -->
            <div class="panel-heading"><?= lang("已加载组件","Plugins Loaded") ?></div>
            <!-- Table -->
            <table class="table table-bordered table-responsive table-hover">
                <?php
                $i = 0;
                foreach ($report['plugins'] as $key => $value) {
                    if ($i % 4 == 0)
                        echo "<tr>";
                    $i++;
                    echo "<td style='width: 25%'>";
                    Yuri2::smarterEcho($value);
                    echo "</td>";
                    if ($i % 4 == 0)
                        echo "</tr>";
                }
                ?>
            </table>
        </div>
        <?php if (!empty($report['errors'])) { ?>

            <?php foreach ($report['errors'] as $no => $error) { ?>
                <div class="panel panel-danger">
                    <!-- Default panel contents -->
                    <div class="panel-heading"><?= lang("错误","Error") ?> NO.<?=$no+1 ?></div>
                    <!-- Table -->
                    <table class="table table-bordered table-responsive table-hover">
                        <tr>
                            <th><?= lang("类型","type") ?></th>
                            <td><?= "[{$error['errno']}] " . $error['type'] ?></td>
                        </tr>
                        <tr>
                            <th><?= lang("信息","msg") ?></th>
                            <td><?= $error['msg'] ?></td>
                        </tr>
                        <tr>
                            <th><?= lang("文件","file") ?></th>
                            <td><?= "[{$error['line']}] " . $error['file'] ?></td>
                        </tr>
                        <?php if (is_file($error['file'])) {
                            echo '<tr><td colspan="2">&nbsp;</td></tr>';
                            $file = file_get_contents($error['file']);
                            $lines = explode("\n", $file);
                            $num = count($lines);
                            $min = $error['line'] - 10 < 1 ? 1 : $error['line'] - 10;
                            $max = $error['line'] + 10 > $num ? $num : $error['line'] + 10;
                            for ($i = $min; $i <= $max; $i++) {
                                $style = $i == $error['line'] ? 'background-color:rgba(169, 68, 66, 0.72); color: white;' : ($i%2==0?'background-color:rgba(255,243,243, 0.72)':'');
                                echo "<tr style='$style'><th style='padding: 2px 5px;color: darkgray;text-align:center'>$i</th>";
                                echo "<td style='padding: 2px 5px;'>" . str_replace(' ', '&nbsp;&nbsp;', htmlspecialchars($lines[$i - 1])) . "</td></tr>";
                            }
                            $flag_found=false;//已经发现目标文件的标志（之前的错误处理trace不该被纪录）
                            $trace_index=0;
                            echo "<tr><td colspan='2'>&nbsp;</td></tr>";
                            foreach ($error['trace'] as $trace){
                                if ($flag_found or (isset($trace['file']) and $trace['file']==$error['file'] and $trace['line']=$error['line'])){
                                    $flag_found=true;
                                    $trace_index++;
                                    $td='';
                                    $td.=isset($trace['class'])?"<b style='color: saddlebrown'>{$trace['class']}</b>":'';
                                    $td.=isset($trace['type'])?"<b style='color: saddlebrown'>{$trace['type']}</b>":'';
                                    $td.=isset($trace['function'])?"<b style='color: saddlebrown'>{$trace['function']} ()</b>":'';
                                    $td.=isset($trace['line'])? " on line <b style='color: #313980'>{$trace['line']}</b> " :'';
                                    $td.=isset($trace['file'])? " in <span style='color: #888a8e'>{$trace['file']}</span>" :'';
                                    echo "<tr><th style='text-align: center'>$trace_index</th><td>$td</td></tr>";
                                }
                            }
                        } ?>
                    </table>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</block_body>
</body>
</html>