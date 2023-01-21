<?php $form_data = $this->session->flashdata('form_data'); ?>
<!-- Content Wrapper. Contains page content -->


<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"><i class="fas fa-store"></i>&nbsp; <?= $title ?></h3>
            </div>
            <div class="d-inline-block float-right">
                <a href="<?php echo site_url($add_url); ?>" class="btn btn-secondary"><i class="fa fa-plus"></i> <?= $add_title ?></a>
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn  btn-info pull-right" style="border-radius:7px"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
        </div>
        <br>
        <div class="card-body">
            <?php $this->load->view('admin/includes/_messages.php') ?>
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
                            echo "<td style='border-color: #DA0037'>" . $record[$data_key[1]] . "</td>";
                        }
                        $id = $record['id'];
                        echo "<td style='border-color: #DA0037'><a href='" . site_url($edit_url . "/" . $id) . "' class='btn btn-primary btn-xs mr5' style='background-color:#035397;'><i class='fa fa-edit'></i></a></td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<!-- /.content -->


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