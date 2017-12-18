<gear-extend>tpl/base_bootstrap</gear-extend>
<gear-block-title>
    Gear
</gear-block-title>
<gear-block-body>
    <div class="container" style="padding-top: 50px">
        <form id="frm" action="<?= url(url_info('curd', ['action' => 'create'])) ?>" method="get" role="form">
            <legend>Create CURD</legend>
            <div class="row">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <div class="form-group">
                        <select name="module_name" class="form-control">
                            <?php
                            foreach ($modules as $module) {
                                echo "<option value='$module'>$module</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <div class="form-group">
                        <input type="text" class="form-control" required name="ctrl_name" placeholder="Controller Name">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <div class="form-group">
                        <input type="text" class="form-control" required name="model_alias" placeholder="Model Alias">
                    </div>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <div class="form-group">
                        <input type="text" class="form-control" required name="db_name" placeholder="Database Config Name">
                    </div>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <div class="form-group">
                        <input type="text" class="form-control" required name="tb_name" placeholder="Database Table Name">
                    </div>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <div class="form-group">
                        <input type="text" class="form-control" required name="pk" placeholder="Primary Key">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="button" id="btn_add_field" class="btn btn-info">Add Field</button>

                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <script>
        $(function () {
            var index = 0;
            $("#btn_add_field").click(function () {
                index++;
                $(this).parent().before('<div class="form-group">' +
                    '<div class="row">' +
                    '<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">' +
                    '<input type="text" class="form-control" required name="field_name_' + index + '"' +
                    '   placeholder="Field Name No.' + index + '">' +
                    '</div>' +
                    '<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">' +
                    '<input type="text" class="form-control" name="field_alias_' + index + '"' +
                    '   placeholder="Field Alias No.' + index + '">' +
                    '</div>' +
                    '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">' +
                    '<input type="text" class="form-control" name="field_info_' + index + '"' +
                    '   placeholder="Field Info No.' + index + '">' +
                    '</div>' +
                    '</div>'
                )
            })
        })
    </script>
</gear-block-body>