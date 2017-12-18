<gear-extend>tpl/base_bootstrap</gear-extend>
<gear-block-title><?=V::displayVar($model_alias)?> 列表(lists)</gear-block-title>
<gear-block-head>
<!--    --><?//= V::import('common/js/vue.js') ?>
</gear-block-head>
<gear-block-body>
    <gear-block-header></gear-block-header>
    <div class="container-fluid" id="ctn">
        <div class="panel panel-primary" style="min-width: <?=V::displayVar($min_width,'1080px')?>">
            <div class="panel-heading">
                <h3 class="panel-title"><?=V::displayVar($model_alias)?> 列表(lists)</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-toolbar" role="toolbar" style="margin-bottom: 10px">
                            <div class="btn-group">
                                <a type="button" href="<?=url_based('create')?>" target="<?=V::displayVar($link_target,'_blank')?>" class="btn btn-primary " title="新增(Create)"><span
                                        class="glyphicon glyphicon-plus"></span> </a>
                                <a type="button" class="btn btn-danger btn-del-row-many" title="批量删除(Delete Many)" @click="delete_many"><span
                                        class="glyphicon glyphicon-trash"></span></a>
                                <a class="btn btn-info" data-toggle="modal" href="#modal-import" title="导入(Import)"><span
                                        class="glyphicon glyphicon-floppy-open"></span></a>
                                <a type="button"
                                   @click.stop="export_excel"
                                   class="btn btn-info " title="导出(Export)"><span
                                        class="glyphicon glyphicon-floppy-save"></span></a>
                                <a class="btn btn-warning" data-toggle="modal" href="#modal-tools" title="工具箱(Tools)"><span
                                        class="glyphicon glyphicon-wrench"></span></a>
                            </div>
                            <form action="#" method="get" class="form-inline" style="float: right"
                                  onsubmit="return false" role="form">
                                <select v-model="page_rows" title="每一页的条目数(The number of entries per page)"
                                        class="form-control">
                                    <option value="5">5
                                    </option>
                                    <option value="10">10
                                    </option>
                                    <option value="20">20
                                    </option>
                                    <option value="50">50
                                    </option>
                                    <option value="100">100
                                    </option>
                                    <option value="200">200
                                    </option>
                                    <option value="500">500
                                    </option>
                                </select>
                                <select v-model="con_col" class="form-control" title="字段(field)">
                                    <option v-for="field in fields" :value=field.name> {{field.alias}}</option>
                                </select>
                                <select v-model="con_op" class="form-control">
                                    <option value="null">null</option>
                                    <option value="~=">~=</option>
                                    <option value="=">=</option>
                                    <option value=">">></option>
                                    <option value="<"><</option>
                                    <option value=">=">>=</option>
                                    <option value="<="><=</option>
                                    <option value="<>"><></option>
                                    <option value="~~">[a~~b]</option>
                                </select>
                                <div class="form-group">
                                    <label class="sr-only"></label>
                                    <input type="text" spellcheck="false" class="form-control" name="con_val"
                                           v-model="con_val" placeholder="查询值(Value)">
                                </div>
                                <button type="submit" class="btn btn-info" title="查询(Select)" @click="select"><span
                                        class="glyphicon glyphicon-zoom-in"></span></button>
                            </form>
                        </div>
                    </div>
                </div>
                <table v-if="waiting" class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>
                            {{msg}}
                        </th>
                    </tr>
                    </thead>
                </table>
                <table v-else class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th style="width:20px;"><input type="checkbox" v-model="all_selected" @change="select_all"></th>
                        <th style="width:120px;">操作(Action)</span></th>
                        <gear-block-ths>

                        </gear-block-ths>

                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="row in rows">
                        <td>
                            <input :value=row.id type="checkbox" v-model="checked_rows" class="cbx_selected_row">
                        </td>
                        <td>
                            <div class="btn-toolbar" role="toolbar" >
                                <div class="btn-group">
                                    <a title="详情 (Detail)" type="button" :href=row.url.detail target="<?=V::displayVar($link_target,'_blank')?>"
                                       class="btn btn-success btn-xs"><span
                                            class="glyphicon glyphicon-info-sign"></span></a>
                                    <a title="拷贝 (Copy)" type="button" :href=row.url.copy target="<?=V::displayVar($link_target,'_blank')?>"
                                       class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-file"></span></a>
                                    <a title="修改 (Edit)" type="button" :href=row.url.edit target="<?=V::displayVar($link_target,'_blank')?>"
                                       class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-edit"></span></a>
                                    <a title="删除 (Delete)" type="button" class="btn btn-danger btn-xs" @click.stop="delete_one(row.data.<?=$pk?>)"><span
                                            class="glyphicon glyphicon-trash"></span></a>
                                </div>
                            </div>
                        </td>
                        <gear-block-tds></gear-block-tds>
                    </tr>
                    </tbody>
                </table>
                <ul class="pagination pagination-small" style="margin: 0">
                    <li v-for="(page,index) in pagination" :class="{ active: page.isActive }"><a href='#'
                                                                                                 @click.stop="page_index=index">{{
                            index }}</a></li>
                </ul>
            </div>
            <div class="panel-footer text-right">
                总计(count):{{count}}
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-import">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">从excel文件导入数据(Import data from excel)</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <form id="frm_import" action="<?=url_based('import')?>" method="post" class="form-horizontal frm-up" enctype="multipart/form-data" role="form">
                            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-10">
                                    <input type="file" required class="form-control ipt-up" name="excel" placeholder="上传(Upload)">
                                </div>
                            </div>
                            <?=V::formToken()?>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭(Close)</button>
                    <button type="button" onclick="$('#frm_import').submit()" class="btn btn-primary btn-up-frm">确定(Confirm)</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" id="modal-tools">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">辅助工具(Tools)</h4>
                </div>
                <div class="modal-body">
                    <form action="#" onsubmit="return false;" class="form-horizontal"  role="form">
                        <div class="form-group">
                            <label for="dtp-rel" class="col-sm-2 control-label">时间日期</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="dtp-rel" onchange="$('#dtp-timestamp').val(Date.parse(new Date($(this).val()))/1000);" placeholder="datetime">
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">时间戳</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="dtp-timestamp" onkeyup="var newDate = new Date(); newDate.setTime($('#dtp-timestamp').val() * 1000);$('#dtp-rel').val(newDate.format('yyyy-MM-dd hh:mm:ss')); " placeholder="timestamp">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭(Close)</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <gear-block-footer></gear-block-footer>
    <script>
        var Model = {};
        $(function () {
            Model.config =<?=json_encode(request(), JSON_FORCE_OBJECT)?>;
            Model.data = {
                msg: 'Loading...',
                waiting: true,
                fields: {},
                rows: [],
                pagination: {},
                count: 0,
                page_index: 1,
                page_rows: 10,
                con_col: '',
                con_op: '',
                con_val: '',
                order: '',
                checked_rows: [],
                all_selected:false,
            };
            Model.vue = new Vue({
                el: '#ctn',
                data: Model.data,
                watch: {
                    page_index: function (val) {
                        Model.config.page_index = val;
                        Model.vue.flash();
                    },
                    page_rows: function (val) {
                        Model.config.page_rows = val;
                        Model.vue.flash();
                    },
                    order:function (val) {
                        Model.config.order=val;
                        Model.vue.flash();
                    }
                },
                methods:{
                    flash: function () {
                        Model.vue.waiting = true;
                        $.ajax({
                            url: '<?=url_based('getLists')?>',
                            type: 'post',
                            async: true,
                            data: Model.config,
                            dataType: 'json',
                            success: function (data) {
                                Model.vue.waiting = false;
                                Model.vue.msg = 'Loading...';
                                Model.vue.fields = data.fields;
                                Model.vue.rows = data.rows;
                                Model.vue.pagination = data.pagination;
                                Model.vue.count = data.count;
                                Model.vue.page_index = data.page_index;
                                Model.vue.con_col = data.con_col;
                                Model.vue.con_op = data.con_op;
                                Model.vue.con_val = data.con_val;
                                Model.vue.order = data.order;
                            },
                            error: function () {
                                Yuri2.log('Gear:Get lists error.');
                                Model.vue.msg = 'Error.';
                            }
                        });
                        Yuri2.log('Data updated.');
                    },
                    select: function () {
                        Model.config.con_col = Model.vue.con_col;
                        Model.config.con_op = Model.vue.con_op;
                        Model.config.con_val = Model.vue.con_val;
                        Model.vue.flash();
                    },
                    delete_one: function (id) {
                        if (confirm('确定删除此项吗？\r\nAre you sure you want to delete?\r\nid='+id)){
                            $.ajax({
                                url: '<?=url_based('delete')?>',
                                type: 'post',
                                async: true,
                                data: {'id':id},
                                dataType: 'json',
                                success: function () {
                                    Yuri2.log(id+' has been deleted.');
                                    Model.vue.flash();
                                },
                                error: function () {
                                    Yuri2.log(id+' was not deleted.');
                                }
                            });
                        }
                    },
                    delete_many:function () {
                        if(this.checked_rows.length>0 && confirm('确定要删除'+this.checked_rows.length+'项吗？\r\nAre you sure want to delete '+this.checked_rows.length+' rows?')){
                            $.ajax({
                                url: '<?=url_based('delete')?>',
                                type: 'post',
                                async: true,
                                data: {'ids':this.checked_rows},
                                dataType: 'json',
                                success: function () {
                                    Yuri2.log('Delete many completed.');
                                    Model.vue.flash();
                                },
                                error: function () {
                                    Yuri2.log('Delete many failed.');
                                }
                            });
                        }
                    },
                    select_all:function () {
                        function getRowsIDs(myhash){
                            var keys=[];
                            for (key in myhash) {
                                keys.push(myhash[key].id);
                            }
                            return keys;
                        }
                        Yuri2.log(this.all_selected);
                        this.checked_rows=this.all_selected?getRowsIDs(this.rows):[];
                    },
                    export_excel:function () {
                        if (confirm('确认导出当前页数据?\r\nAre you sure want  to export the data from this page?')){
                            Yuri2.submitForm('<?=url_based('export')?>',Model.config);
                        }
                    },
                    timestampToDate:function (timestamp) {
                        return Yuri2.timestampToDate(timestamp)
                    }
                }

            });
            Model.vue.flash();
            //小工具
            $('#dtp-rel').datetimepicker({
                language:  'zh-CN',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0,
                showMeridian: 1,
                format: 'yyyy-mm-dd hh:ii:ss',
            });
        });
    </script>
</gear-block-body>