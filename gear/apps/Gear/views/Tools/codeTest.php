[extend tpl/base_bootstrap]
<block_title><?= lang("代码测试","Code Test") ?></block_title>
<block_head>
    <?= V::import('common/codemirror-5.2/lib/codemirror.css') ?>
    <?= V::import('common/codemirror-5.2/lib/codemirror.js') ?>
    <?= V::import('common/codemirror-5.2/addon/edit/matchbrackets.js') ?>
    <?= V::import('common/codemirror-5.2/mode/htmlmixed/htmlmixed.js') ?>
    <?= V::import('common/codemirror-5.2/mode/xml/xml.js') ?>
    <?= V::import('common/codemirror-5.2/mode/javascript/javascript.js') ?>
    <?= V::import('common/codemirror-5.2/mode/css/css.js') ?>
    <?= V::import('common/codemirror-5.2/mode/clike/clike.js') ?>
    <?= V::import('common/codemirror-5.2/mode/php/php.js') ?>
</block_head>
<block_body>
    <style>
        .CodeMirror{min-height: 500px;border: 1px solid gray;}
        #ifrm{min-height: 500px;border: 1px solid gray;width: 100%}
    </style>

    <div class="container" style="padding-top: 30px">
        <div class="row" style="padding-bottom: 10px">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <button type="button" id="btn_save" class="btn btn-primary"><?= lang("保存","Save") ?></button>
                <button type="button" id="btn_refresh" class="btn btn-primary"><?= lang("刷新","Refresh") ?></button>
                <button type="button" id="btn_save_refresh" class="btn btn-primary"><?= lang("保存并刷新","Save & Refresh") ?></button>
                <a class="btn btn-primary" data-toggle="modal" href="#modal-id"><?= lang("草稿纸","Scratch Paper") ?></a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <textarea id="code" name="code"><?=V::displayVar($code,'','no')?></textarea>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <iframe id="ifrm" src="<?=url(url_info('',['action'=>'preview']))?>"></iframe>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <textarea id="scratch" name="scratch" style="min-height: 500px;resize: none;width: 100%"><?=V::displayVar($scratch,'','no')?></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang("关闭","Close") ?></button>
                    <button type="button" id="btn_save_scratch" class="btn btn-primary" data-dismiss="modal"><?= lang("保存修改","Save Changes") ?></button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <script>
        $(function () {
            var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
                lineNumbers: true,
                matchBrackets: true,
                mode: "application/x-httpd-php",
                indentUnit: 4,
                indentWithTabs: true
            });

            $('#btn_save_scratch').click(function () {
                var scratch=$("#scratch").val();
                $.post('<?=url(url_info('',['action'=>'scratch']))?>',{scratch:scratch},function (data) {
                    if (data.state==='success'){
                        Yuri2.log('Save completed.')
                    }else{
                        alert(data.msg);
                    }
                },'json')
            });
            $('#btn_save').click(function () {
                var code=editor.getValue();
                $.post('<?=url(url_info('',['action'=>'save']))?>',{code:code},function (data) {
                    if (data.state==='success'){
                        Yuri2.log('Save completed.')
                    }else{
                        alert(data.msg);
                    }
                },'json')
            });
            $('#btn_refresh').click(function () {
                var ifrm = $('#ifrm');
                ifrm.attr('src', ifrm.attr('src'))
            });
            $('#btn_save_refresh').click(function () {
                $('#btn_save').click();
                $('#btn_refresh').click();
            });
        });

    </script>
</block_body>