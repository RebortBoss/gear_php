[extend tpl/base_bootstrap]
<block_title><?= lang("网站地图","WebsiteMap") ?></block_title>
<block_head> </block_head>
<block_body>
    [include tpl/inc_nav_bar]
    <div class="container-fluid" id="box-map" style="padding-top: 40px">
        <?php foreach ($modules as $module){?>
        <div class="panel panel-primary maps">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="btn btn-xs btn-info btn-hidePanel hidden" style="width:30px;">-</span>
                    <span class="btn btn-xs btn-info btn-showPanel " style="width:30px;">+</span>
                    <?= V::displayVar($module['moduleName'])?>
                </h3>
            </div>
            <div class="panel-body">
                <!-- TAB NAVIGATION -->
                <ul class="nav nav-tabs" role="tablist">
                    <?php foreach ($module['ctrls'] as $ctrl){?>
                        <li class=""><a href="#tab_<?= V::displayVar($module['moduleName'])?>_<?= V::displayVar($ctrl['ctrlName'])?>" role="tab" data-toggle="tab"> <?= V::displayVar($ctrl['ctrlName'])?></a></li>
                    <?php } ?>
                </ul>
                <!-- TAB CONTENT -->
                <div class="tab-content">
                    <?php foreach ($module['ctrls'] as $ctrl){?>
                    <div class="tab-pane fade in" id="tab_<?= V::displayVar($module['moduleName'])?>_<?= V::displayVar($ctrl['ctrlName'])?>">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th><?= lang("行为","Action") ?></th>
                                <th><?= lang("参数","Params") ?></th>
                                <th><?= lang("注释","Document") ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($ctrl['infos'] as $info){?>
                            <tr>
                                <td><a href="<?= V::displayVar($info['url'])?>" target="_blank"><?= V::displayVar($info['name'])?></a></td>
                                <td><?= V::displayVar($info['params'])?></td>
                                <td><?= V::displayVar($info['doc'])?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                </div>

            </div>
        </div>
        <?php } ?>
    </div>

    <script>
        //脚本
        $(function () {
            $('#box-map').find('.nav').each(function () {
                $(this).find('li:first').addClass('active')
            });
            $('#box-map').find('.tab-content').each(function () {
                $(this).find('.tab-pane:first').addClass('active')
            });
            $('.maps .panel-body').toggle();
            $('.btn-showPanel').on('click',function () {
                $(this).parent().parent().parent().find('.panel-body').slideDown();
                $(this).addClass('hidden');
                $(this).siblings('.btn-hidePanel').removeClass('hidden');
            });
            $('.btn-hidePanel').on('click',function () {
                $(this).parent().parent().parent().find('.panel-body').slideUp();
                $(this).addClass('hidden');
                $(this).siblings('.btn-showPanel').removeClass('hidden');
            });
        })
    </script>
</block_body>