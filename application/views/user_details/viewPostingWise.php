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
        <br>
        <div class="card-body">
            <div class="row">
                <div class="col-3">

                    <?php $this->load->view('master/select_field', array("field_id" => "posting_unit", "label" => "posting_unit", "place_holder" => "Select an posting_unit", "option_record" => $posting_unit_data, "option_value" => "posting_unit", "option_text" => "posting_unit", "selected_value" => ''));
                    // $this->load->view() 
                    ?>

                </div>
                <div class="col-3">

                    <?php
                    $status_array = array(array("status" => "serving", "option_text" => "Serving"), array("status" => "retired", "option_text" => "Retiree"));
                    $this->load->view('master/select_field', array("field_id" => "personnel_type", "label" => "personnel_type", "place_holder" => "select a personnel type", "option_record" => $status_array, "option_value" => "status", "option_text" => "option_text", "selected_value" => '')); ?>
                    <!-- <select class="form-control" id="personnel_type">
                        <option value="registered">Serving</option>
                        <option value="unregistered">Retiree</option>
                    </select> -->
                </div>
                <div class="col-3">
                    <?php $registration_status_array = array(
                        array("status" => "registered", "option_text" => "Registered"),
                        array("status" => "unregistered", "option_text" => "Not registered"),
                        array("status" => "verification_pending", "option_text" => "Verification Pending"),
                        array("status" => "verification_completed", "option_text" => "Only Verification Completed"),
                    );

                    $this->load->view('master/select_field', array("field_id" => "status_req", "label" => "registration_status", "place_holder" => "select a personnel type", "option_record" => $registration_status_array, "option_value" => "status", "option_text" => "option_text", "selected_value" => ''));
                    ?>

                </div>
                <div class="col-3" style="padding-top: 30px;">
                    <!-- <br><br> -->
                    <!-- <label class="form-control"></label> -->
                    <button class="btn btn-md btn-primary" id="fetchDetails">Fetch Details</button>
                </div>
            </div>
        </div>

</section>
<div style="margin-left: 10px; margin-right: 10px; margin-bottom: 20px; " class="card" id="hold_table">
    <div class="card-body table-responsive">
        <table id="master_table" class="table table-bordered table-hover" style="white-space:nowrap;border-collapse: collapse !important;border-color: #DA0037">
            <thead style='background-color:#dc3545;color:white;border-color: #DA0037;box-shadow: 0px 1px 1px 0px #DA0037;'>
                <tr>
                    <th>Sr No</th>
                    <th>IRLA/Regimental No</th>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Mobile NO</th>
                    <th>Email</th>
                    <th>Posting Unit</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="table_data">
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
</script>
<script src="<?= base_url() ?>assets/js/module/reports/postingWiseUserDataReport.js"></script>