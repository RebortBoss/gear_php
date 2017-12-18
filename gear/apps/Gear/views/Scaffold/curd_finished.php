<gear-extend>tpl/base_bootstrap</gear-extend>
<gear-block-title>
    Gear
</gear-block-title>
<gear-block-body>
    <div class="container">
        <div class="jumbotron" style="background: white">
            <div class="container">
                <h1>Job done!</h1>
                <p>You can now use the relevant pages directly.</p>
                <p class="text-right">
                    <a class="btn btn-primary btn-lg" target="_blank"
                       href="<?= url(request('module_name') . DS . request('ctrl_name')) ?>">GO</a>
                </p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>File</th>
                    <th>State</th>
                    <th>Info</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($file_state as  $file=>$rel){
                        $state=$rel?'Success':'Failed';
                        $info=$rel?'File write successful':'File already exist.';
                        $class=$rel?'text-success':'text-warning';
                        echo "<tr class='$class'>";
                        echo "<td>$file</td>";
                        echo "<td>$state</td>";
                        echo "<td>$info</td>";
                        echo "</tr>";
                    }
                ?>
                <tr>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script>
    </script>
</gear-block-body>