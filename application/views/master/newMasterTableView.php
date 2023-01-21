<!-- Content Wrapper. Contains page content -->
<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"><i class="fas fa-store"></i>&nbsp; <?= $title ?></h3>
            </div>
            <div class="d-inline-block float-right">
                <?php if ($title == "Liquor List") { ?>
                    <a href="<?php echo site_url($add_url); ?>" class="btn btn-secondary"><i class="fa fa-plus"></i> <?= $add_title ?></a>
                <?php } ?>
            </div>
        </div>
        <br>
        <div class="card-body">
            <table id="master_table" class="table table-bordered table-hover" style="border-collapse: collapse !important;border-color: #DA0037">
                <thead style='background-color:#dc3545;color:white;border-color: #DA0037;box-shadow: 0px 1px 1px 0px #DA0037;'>
                    <tr>
                        <th style="border-color: #DA0037"><?= trans('id') ?></th>
                        <?php
                        $table_head_array = unserialize($table_head);
                        foreach ($table_head_array as $column_header) {
                            $column_array = explode(":", $column_header);
                            echo "<th style='border-color: #DA0037'>" . trans($column_array[0]) . "</th>";
                        }
                        ?>

                        <th style="border-color: #DA0037">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $table_data_array = json_decode(json_encode($table_data), true);
                    foreach ($table_data_array as $record) {
                        echo "<tr><td style='border-color: #DA0037'>" . $i++ . "</td>";
                        foreach ($table_head_array as $column_header) {
                            $data_key = explode(":", $column_header);
                            if (strpos($data_key[0], '_image') !== false) {
                                echo "<td style='border-color: #DA0037'><img src='" . site_url($record[$data_key[1]]) . "' alt='' width='100' height='100'></td>";
                            } else {
                                echo "<td style='border-color: #DA0037'>" . $record[$data_key[1]] . "</td>";
                            }
                        }
                        $id = $record['id'];
                        $onclickdata = '"' . $table_mode . '",' . $id;
                        echo "<td style='border-color: #DA0037'><button   onclick='fetchDetails($onclickdata);' class='btn btn-primary btn-xs mr5' id='display_details_$id' style='background-color:#035397;'><i class='fa fa-edit'></i></button></td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg" style="max-width: 60%;">
            <div class="modal-content">
                <div class="modal-body p-0" id="modal_data_body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" onclick="editDetails()">Confirm</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


</section>
<!-- /.content -->
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = "<?php echo base_url(); ?>";
</script>

<script>
    $("body").on("change", ".tgl_checkbox", function() {
        $.post('<?= base_url("admin/admin_roles/change_status") ?>', {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                id: $(this).data('id'),
                status: $(this).is(':checked') == true ? 1 : 0
            },
            function(data) {
                $.notify("Status Changed Successfully", "success");
            });
    });
    $(function() {
        $('#master_table').DataTable({});
    });
</script>
<script src="<?= base_url() ?>assets/js/module/common/validation.js"></script>
<script src="<?= base_url() ?>assets/js/module/common/master_table.js"></script>