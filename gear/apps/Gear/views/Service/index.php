[extend tpl/base_bootstrap]
<block_title>index</block_title>
<block_head></block_head>
<block_body>
    <div class="container" style="padding-top: 50px">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?= lang('服务','Service') ?></h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-hover ">
                    <thead>
                    <tr>
                        <th><?= lang('状态','State') ?></th>
                        <td><?= $data['enable']?lang('正在运行','Running'):lang('已停止','Stopped') ?></td>
                        <td>
                            <?= !$data['enable'] ? "<button id='btn_start_stop' type='button' class='btn btn-success btn-xs'>".lang('启动','Start')."</button>" : "<button id='btn_start_stop' type='button' class='btn btn-danger btn-xs'>".lang('终止','Stop')."</button>" ?>
                        </td>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($data['list'] as $name => $item) {
                        $html_btn = $item['enable'] ?
                            "<button type='button' class='btn-stop btn btn-warning btn-xs'>".lang('终止','Stop')."</button>"
                            : "<button type='button' class='btn-start btn btn-success btn-xs'>".lang('启动','Start')."</button>";
                        echo "
                                  <tr>
                                    <th>$name</th>
                                    <td>{$item['target']}</td>
                                    <td data='$name'>
                                        $html_btn
                                        <button type='button' class='btn-del btn btn-danger btn-xs'>".lang('删除','Delete')."</button>
                                    </td>
                                  </tr>";
                    } ?>

                    <tr>
                        <td colspan="3">
                            <form id="frm_add_script" action="" method="post" class="form-inline" role="form" style="float: right;padding-top: 20px">
                                <div class="form-group">
                                    <input type="text" required class="form-control" id="ipt_name" name="name"  placeholder="<?= lang('别名','Name') ?> ...">
                                </div>
                                <div class="form-group">
                                    <input type="text" required class="form-control" id="ipt_target" name="target"  placeholder="<?= lang('脚本位置或URL','Script path or URL') ?> ...">
                                </div>
                                <button type="submit" class="btn btn-primary"><?= lang('添加任务','Add Task') ?></button>
                            </form>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            var str_confirm='<?=lang('您确定要这么做吗?','Are you sure you want to do this?')?>';
            $("#btn_start_stop").click(function () {
                if (!confirm(str_confirm)){
                    return false;
                }
                $.post('<?=url_based('startStop')?>',{},function () {
                    location.reload(true);
                },'json');
                setTimeout(function () {
                    location.reload(true);
                },1500)
            });

            $(".btn-start").click(function () {
                if (!confirm(str_confirm)){
                    return false;
                }
                var name=$(this).parent().attr('data');
                $.post('<?=url_based('startScript')?>',{name:name},function () {
                    location.reload(true);
                },'json');
            });

            $(".btn-stop").click(function () {
                if (!confirm(str_confirm)){
                    return false;
                }
                var name=$(this).parent().attr('data');
                $.post('<?=url_based('stopScript')?>',{name:name},function () {
                    location.reload(true);
                },'json');
            });

            $(".btn-del").click(function () {
                if (!confirm(str_confirm)){
                    return false;
                }
                var name=$(this).parent().attr('data');
                $.post('<?=url_based('delScript')?>',{name:name},function () {
                    location.reload(true);
                },'json');
            });

            $("#frm_add_script").submit(function () {
                var name=$("#ipt_name").val();
                var target=$("#ipt_target").val();
                $.post("<?=url_based('addScript')?>",{name:name,target:target},function (data) {
                    if (data.state==='success'){
                        location.reload(true);
                    }else{
                        alert(data.msg);
                    }
                },'json');
                return false;
            })
        })
    </script>
</block_body>