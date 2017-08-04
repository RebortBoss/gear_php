[extend tpl/base_bootstrap]
<block_title><?=lang('收藏夹','Favorites')?></block_title>

<block_head>
    <!--head-->
    <style>
        body {
            background-color: white;
            font-family: "Microsoft YaHei", 微软雅黑, "MicrosoftJhengHei", 华文细黑, STHeiti, MingLiu
        }

        .item {
            margin: 5px;
        }

        .btn-del {
            display: none
        }

        .grp-visit {
            width: 100%
        }

        .grp-visit:hover .btn-del {
            display: inline-block
        }
    </style>
</block_head>

<block_body>
    <div class="container">
        <div class="row">
            <div class="jumbotron" style="background-color:transparent">
                <h2 style="color: #0B5979"><?=lang('收藏夹','Favorites')?></h2>

                <div class="row">
                    <form action="<?= url_based('addFav') ?>" style="margin:10px 5px;margin-bottom: 60px" method="post"
                          class="form-inline col-md-12" role="form">
                        <div class="form-group">
                            <div class="col-md-4">
                                <input type="url" class="form-control" name="href" id="ipt_url"
                                       placeholder="URL">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" id="ipt_title" placeholder="<?= lang("标题","Title") ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4 text-right ">
                                <button type="submit" class="btn btn-primary"><?= lang("添加","Add") ?></button>
                            </div>
                        </div>
                    </form>

                    <?php foreach ($favs as $k => $fav) { ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="item">
                                <div class="btn-toolbar" role="toolbar">
                                    <div class="btn-group grp-visit">
                                        <a type="button" title="<?= $fav['name'] ?>" href="<?= $fav['href'] ?>"
                                           style="width:70%;overflow: hidden" target="_blank" class="btn btn-default">
                                            <?= $fav['name'] ?>
                                        </a>
                                        <a href="<?= url_based('delFav', ['id' => $k]) ?>" title="Delete" type="button"
                                           class="btn btn-default btn-del"><span
                                                    class="glyphicon glyphicon-trash"></span></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#ipt_url').keyup(function () {
            var val = $(this).val();
            $.ajax({
                type: "post",
                url: "<?=url_based('getTitle')?>",
                data: {
                    url: val
                },
                dataType: "json",
                async: true,

                success: function (data) {
                    if (data.errno != 0) {
                        alert('<?=lang('很抱歉，操作失败。错误码：','Sorry, operation failed. Error code:')?>' + data.errno + '<?=lang('错误信息：','Error msg:')?>' + data.msg)
                    }
                    $('#ipt_title').val(data.data);
                },
                error: function (a) {
                    Gear.log('Sorry,ajax request failed.');
                    Yuri2.log(a.responseText);
                }
            });
        });
    </script>
</block_body>