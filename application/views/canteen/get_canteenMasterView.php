<!--<div class="content-wrapper" style="margin-top: 55px;min-height: 580.08px !important;">-->
<!-- Main content -->
<section class="content">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-list"></i>
                    Canteen Details Report</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control form-select2" name="canteen_report" id="canteen_report">
                        <option selected disabled>select menu</option>
                        <option value="1">All Canteen</option>
                        <option value="2">Canteen Started Sale</option>
                        <option value="3">Rights Given</option>
                        <option value="4">Rights Not Given</option>
                        <option value="5">Canteen Liquor's Added</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="submit" value="submit" name="submit" class="btn btn-info" onclick="canteen()">
                </div>
            </div>
            <br>
            <div id="tableView">
            </div>
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
        </div>
        <!-- /.box-body -->
    </div>
</section>
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = "<?php echo base_url(); ?>";
</script>
<script>
    $('.form-select2').select2()
    function canteen() {
        var report_type = document.getElementById("canteen_report").value;
        $.ajax({
            url: DOMAIN + "/canteen_report/Canteen_report_master/getCanteenData",
            dataType: 'html',
            data: {
                report_type: report_type,
                csrf_test_name: csrfHash,
                csrfName: csrfName
            },
            method: 'POST',
            success: function(response) {
                $('#tableView').html(response);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
</script>