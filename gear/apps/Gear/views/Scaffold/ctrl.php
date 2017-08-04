[extend tpl/base_bootstrap]
<block_title>
    <?= lang("创建控制器","Controller Builder") ?>
</block_title>
<block_body>
    <div class="container" style="padding-top: 50px">
        <form action="<?=url(url_info('ctrl',['action'=>'create']))?>" method="get" role="form">
            <legend><?= lang("创建控制器","Controller Builder") ?></legend>
            <div class="form-group">
                <select name="module_name" class="form-control">
                    <?php
                        foreach ($modules as $module){
                            echo "<option value='$module'>$module</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" required name="ctrl_name" placeholder="<?= lang("控制器名","Ctrl Name") ?>">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" required name="action_names" placeholder="<?= lang("方法名（以逗号分割）","Action Names(comma partition)") ?>">
            </div>
            <button type="submit" class="btn btn-primary"><?= lang("提交","Submit") ?></button>
        </form>
    </div>
</block_body>