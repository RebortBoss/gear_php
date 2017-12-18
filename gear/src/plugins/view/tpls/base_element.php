<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title>
        <gear-block-title>模板页</gear-block-title>
    </title>
    <gear-block-ico>
        <?= V::import('common/img/gear.ico') ?>
    </gear-block-ico>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <?php if (Yuri2::isOldIE()) { ?>
        <script type="text/javascript" src="__PUBLIC__/common/js/jquery-1.11.3.min.js"></script>
    <?php }else{ ?>
        <script type="text/javascript" src="__PUBLIC__/common/js/jquery-2.2.4.min.js"></script>
    <?php } ?>

    <!-- Jquery-->
    <script type="text/javascript" src="__PUBLIC__/common/js/jquery.jsonp.js"></script>
    <script type="text/javascript" src="__PUBLIC__/common/js/jqueryMousewheel.js"></script>
    <script type="text/javascript" src="__PUBLIC__/common/js/Yuri2.js"></script>
    <!-- Vue-->
    <script type="text/javascript" src="__PUBLIC__/common/js/vue.min.js"></script>
    <!-- Element -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <?= V::import_rs_element_ui() ?>

    <!-- css-->
    <link href="__PUBLIC__/common/css/animate.css" rel="stylesheet">
    <link href="__PUBLIC__/common/css/grid.css" rel="stylesheet">

    <!-- layer -->
    <?= V::import_rs_layer() ?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="__PUBLIC__/common/js/html5shiv-3.7.2-html5shiv.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/common/js/respond.js-1.4.2-respond.min.js"></script>
    <![endif]-->

    <style>
        *{
            font-family:"Microsoft YaHei",微软雅黑,"MicrosoftJhengHei",华文细黑,STHeiti,MingLiu
        }
    </style>
    <gear-block-head></gear-block-head>
</head>
<body>
<gear-block-body>
    <gear-include>tpl/inc_particle</gear-include>
    <div id="app">
        <el-row type="flex" justify="center">
            <el-col :span="18" style="margin-top: 200px">
                <el-steps :space="100" direction="vertical" :active="active">
                    <el-step title="继承：<gear-extend>tpl/base_element</gear-extend>"></el-step>
                    <el-step title="替换：title,ico,head,body"></el-step>
                    <el-step title="自由使用vue,jquery,element,Yuri2.js"></el-step>
                </el-steps>
            </el-col>
        </el-row>
    </div>

    <style>

    </style>
    <script>
        var app=new Vue({
            el: '#app',
            data:{
                active:1,
            },
            created: function () {
                this.$notify({
                    title: '提示',
                    message: '成功引用相关组件！',
                    type: 'success',
                    duration:0,
                    offset: 100,
                });
                //步骤循环
                var itv=setInterval(function () {
                    app.active+=1;
                    if (app.active>3){
                        clearInterval(itv);
                    }
                },1500)
            }
        });
    </script>
</gear-block-body>
</body>
</html>