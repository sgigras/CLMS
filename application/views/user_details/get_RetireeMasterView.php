<!--<div class="content-wrapper" style="margin-top: 55px;min-height: 580.08px !important;">-->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= $pending; ?></h3>
                    <p style="color:white">Verification  Pending</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>
                <!-- <a href="#" class="small-box-footer"><?= trans('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $approved; ?></h3>
                    <p style="color:white">Verification Approved</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <!-- <a href="#" class="small-box-footer"><?= trans('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?= $denied; ?></h3>
                    <p style="color:white">Verifcation Denied</p>
                </div>
                <div class="icon">
                    <i class="ion ion-close"></i>
                </div>
                <!-- <a href="#" class="small-box-footer"><?= trans('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $registered; ?></h3>
                    <p style="color:white">Retiree Registered</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bookmark"></i>
                </div>
                <!-- <a href="#" class="small-box-footer"><?= trans('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
    </div>
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-list"></i>
                    Retiree Verification Status Report</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-select form-control" aria-label="Default select example" name="retiree_report" id="retiree_report">
                        <option value="0" selected disabled>Select Status Type</option>
                        <option value="1">Verification Pending</option>
                        <option value="2">Verification Approved</option>
                        <option value="3">Verification Denied</option>
                        <!-- <option value="4">Rights Not Given</option>
                        <option value="5">Canteen Liquor's Added</option> -->
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="submit" value="submit" name="submit" class="btn btn-info" onclick="retiree()">
                </div>
            </div>
            <br>
            <div id="tableView">
            </div>
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
            <!-- <script>
                $('#master_table').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'excel',
                        className:'btn btn-info',
                        title: 'User Details',
                    }]
                });
            </script> -->
        </div>
        <!-- /.box-body -->
    </div>
</section>
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
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = "<?php echo base_url(); ?>";
</script>
<!--</div>-->
<script>
    $("#retiree_report").select2({
        width: '100%',
        placeholder: 'Select a Status Type'
    });
    function retiree() {
        var report_type = document.getElementById("retiree_report").value;
        // var strUser = report_type.value;
        if (report_type == 0) {
            swal("Please Select Status Type");
            return false;
        }
        // console.log(report_type);
        $.ajax({
            url: DOMAIN + "user_details/User_details/getRetireeData",
            dataType: 'html',
            data: {
                report_type: report_type,
                csrf_test_name: csrfHash,
                // csrfName: csrfName
            },
            method: 'POST',
            success: function(response) {
                // console.log(response);
                $('#tableView').html(response);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
</script>