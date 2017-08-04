[extend tpl/base_bootstrap]
<block_title>
    <?= lang("创建模块","Module Builder") ?>
</block_title>
<block_body>
    <div class="container" style="padding-top: 50px">
        <form action="<?=url(url_info('module',['action'=>'create']))?>" method="get" role="form">
            <legend><?= lang("创建模块","Module Builder") ?></legend>
            <div class="form-group">
                <label for=""></label>
                <input type="text" class="form-control" required name="module_name" placeholder="<?= lang("模块名","Module Name") ?>">
            </div>
            <button type="submit" class="btn btn-primary"><?= lang("提交","Submit") ?></button>
        </form>
    </div>
</block_body>