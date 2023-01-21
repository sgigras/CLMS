
<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->
<!-- Content Header (Page header) -->

<!-- /.content-header -->

<!-- Main content -->
<!-- <section class="content"> -->
<!-- <div class="container-fluid"> -->

<!-- Info boxes -->
<!-- <div class="row"> -->
<!-- put the content -->
<!-- <div class="col-12 text-center "> -->

<!-- <div id="printableArea"> -->
<!-- <table id="tblPrint" align="center" style="min-width: 400px;"> -->
<div class="table-responsive">
    <!-- <table id="example2" style="white-space:nowrap;" class="table  table-bordered table-hover "> -->
    <table id="master_table" class="table table-bordered table-hover" style="white-space:nowrap;border-collapse: collapse !important;border-color: #DA0037">
        <thead style='background-color:#dc3545;color:white;border-color: #DA0037;box-shadow: 0px 1px 1px 0px #DA0037;'>
            <tr>
                <!-- <th style="border-color: #DA0037"><?= trans('id') ?></th> -->
                <?php
                $table_head_array = unserialize(ALL_CANTEEN);
                // print_r($table_head_array);
                // die();
                foreach ($table_head_array as $column_header) {
                    $column_array = explode(":", $column_header);
                    echo "<th style='border-color: #DA0037'>" . trans($column_array[0]) . "</th>";
                }
                ?>

                <!-- <th style="border-color: #DA0037">Action</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($table_data as $record) {
                echo "<tr>";
                foreach ($table_head_array as $column_header) {
                    $data_key = explode(":", $column_header);
                    echo "<td>" . $record[$data_key[1]] . "</td>";
                    // echo "<td>" .$record[$data_key[0]] = isset($data_key[1]) ? $data_key[1] : null . "</td>";
                }
                echo "</tr>";
            }
            ?>

        </tbody>
    </table>
    <?php // $this->load->view('master/table_tr_td', array("table_header" => USER_DETAILS_TABLE, "table_data_array" => $user_details)) 
    ?>
    <!-- </table> -->
</div>
<!-- </div> -->
<!-- </table> -->
<!-- </div> -->
<!-- <a id="print_receipt" target="_blank" type="button" onclick="printDiv('printableArea')" value="print a div!"></a> -->
<!-- </div> -->
<!-- </div> -->
<!-- </div> -->
<!-- /.row -->
<!-- </div> -->
<!--/. container-fluid -->
<!-- </section> -->
<!-- /.content -->
<!-- /.content-wrapper -->


<!-- PAGE PLUGINS -->
<!-- SparkLine -->
<!-- <script src="<?= base_url() ?>assets/plugins/sparkline/jquery.sparkline.min.js"></script> -->
<!-- jVectorMap -->
<!-- <script src="<?= base_url() ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script> -->
<!-- <script src="<?= base_url() ?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script> -->
<!-- SlimScroll 1.3.0 -->
<!-- <script src="<?= base_url() ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script> -->
<!-- ChartJS 1.0.2 -->
<!-- <script src="<?= base_url() ?>assets/plugins/chartjs-old/Chart.min.js"></script> -->

<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css">
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>
<script rel="stylesheet" src="<?= base_url() ?>assets/plugins/datatablesbtn/jquery-3.5.1.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/jquery.dataTables.min.js" defer></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/dataTables.bootstrap4.min.js" defer></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/dataTables.buttons.min.js" defer></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/jszip.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/pdfmake.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/vfs_fonts.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/buttons.html5.min.js" defer></script>

<script>
    $('#master_table').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excel',
            className:'btn btn-info',
            title: 'User Details',
        }]
    });
</script>