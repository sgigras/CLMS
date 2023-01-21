<!-- Content Wrapper. Contains page content -->
<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"><i class="fas fa-store"></i>&nbsp; <?= $title ?></h3>
            </div>
            <div class="d-inline-block float-right">
                <a href="#" onclick="window.history.go(-1); return false;" class="btn  btn-info pull-right" style="border-radius:7px"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
        </div>
</section>
<div style="margin-left: 10px; margin-right: 10px; margin-bottom: 20px; " class="card" id="hold_table">
    <div class="card-body table-responsive">
        <table id="master_table" class="table table-bordered table-hover" style="white-space:nowrap;border-collapse: collapse !important;border-color: #DA0037">
            <thead style='background-color:#dc3545;color:white;border-color: #DA0037;box-shadow: 0px 1px 1px 0px #DA0037;'>
                <tr>
                    <th>Sr No</th>
                    <th>Canteen</th>
                    <!-- <th>Type</th> -->
                    <th>Total Serving</th>
                    <th>Serving Registered</th>
                    <th>Serving Unregistered</th>
                    <th>Total Retiree</th>
                    <th>Retiree Registered(Using CLMS)</th>
                    <th>Retiree Unregistered</th>
                    <th>Retiree Data Added</th>
                    <th>Retiree Verification Completed</th>
                    <th>Retiree Verification Pending</th>
                    <th>Started Sale</th>
                    <th>Stocks Added</th>

                </tr>
            </thead>
            <tbody id="table_data">

                <!-- <pre> -->
                <?php
                // echo '<pre>';
                // // print_r($canteen_posting_data[0]);
                // echo '<pre>';
                $count = 1;
                foreach ($canteen_posting_data as $row) {
                    echo '<tr>';
                    echo '<td>' . $count . '</td>';
                    echo '<td>' . $row[0]['posting_unit'] . '</td>';
                    // echo '<td>' . $canteen_data['type'] . '</td>';
                    echo '<td>' . $row[0]['TOTAL_SERVING'] . '</td>';
                    echo '<td>' . $row[0]['REGISTERED_SERVING'] . '</td>';
                    echo '<td>' . ($row[0]['TOTAL_SERVING'] - $row[0]['REGISTERED_SERVING']) . '</td>';
                    echo '<td>' . $row[0]['TOTAL_RETIREE'] . '</td>';
                    echo '<td>' . $row[0]['REGISTERED_RETIREE'] . '</td>';
                    echo '<td>' . ($row[0]['TOTAL_RETIREE'] - $row[0]['REGISTERED_RETIREE']) . '</td>';
                    echo '<td>' . $row[0]['retiree_applied'] . '</td>';
                    echo '<td>' . $row[0]['verification_completed'] . '</td>';
                    echo '<td>' . $row[0]['verification_pending'] . '</td>';
                    echo '<td>' . $row[0]['starte_sale'] . '</td>';
                    echo '<td>' . $row[0]['stocks_added'] . '</td>';
                    echo '</tr>';
                    $count++;
                }
                // print_r($canteen_posting_data);

                ?>
                <!-- </pre> -->
            </tbody>
        </table>
    </div>
</div>
<!-- /.content -->

<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css">
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/jquery.dataTables.min.js" defer></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/dataTables.bootstrap4.min.js" defer></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/dataTables.buttons.min.js" defer></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/jszip.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/pdfmake.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/vfs_fonts.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/buttons.html5.min.js" defer></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/buttons.html5.min.js" defer></script>

<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    //     width: "100%",
    //     placeholder: "Select a posting unit"
    // })
    $('#master_table').DataTable({
        destroy: true,
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excel',
            className: 'btn btn-info',
            title: 'canteen details',
        }]
    });
</script>