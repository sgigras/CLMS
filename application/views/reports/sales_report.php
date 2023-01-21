<!-- Author:Ujwal Jain
Subject:Sales Report
Date:16-12-21 -->

<?php  $form_data=$this->session->flashdata('form_data');   ?>




<section class="content p-0">
    <div class="card card-default color-palette-bo">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-info-circle"></i>
                    Sales Report</h3>
            </div>

        </div>


        <!-- /.card-header -->
        <!-- form start -->
        <?php $this->load->view('admin/includes/_messages.php') ?>
        <?php echo form_open_multipart(base_url('reports/Sales_Report'), 'class="form-horizontal"');  ?>
        <div class="card-body">
            <div class="row">

                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="text" name="start_date" id="start_date" class="form-control"
                            placeholder="Select  Start Time"
                            value="<?= (isset($form_data['start_date'])? $form_data['start_date']:''); ?>"
                            autocomplete="off">

                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="text" name="end_date" id="end_date" class="form-control"
                            placeholder="Select End  Time"
                            value="<?= (isset($form_data['end_date'])? $form_data['end_date']:'');?>"
                            autocomplete="off">

                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        <div class="card-footer mt-4 card-footer bg-transparent p-0 d-flex justify-content-end">

                            <button class="btn btn-warning mr-2"
                                onclick="window.location=<?= base_url('reports/Sales_Report') ?>"><i
                                    class="fa fa-eraser mr-2"></i>Reset</button>
                            <button type="submit" name="submit" value="submit" id="trip_details"
                                class="btn btn-success"><i class="fa fa-save mr-2"></i>View</button>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div><!-- /.container-fluid -->
        </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?php
    if(isset($details)){
      // print_r($cost_details);
      ?>
<div style="margin-left: 10px; margin-right: 10px; margin-bottom: 20px; " class="card" id="temp_voilation">
    <div class="card-body table-responsive">
        <table id="na_datatable" class="table datatable datatable-bordered table-bordered datatable-striped"
            width="100%">


            <thead class="m0 mb5">
                <th colspan="10">Sales Report From &nbsp;&nbsp;
                    <?=$form_data['start_date']?>&nbsp;&nbsp;To&nbsp;&nbsp;<?= $form_data['end_date']?></th>
                <tr>
                    <th colspan="10">Total Liquor
                        Sold:-&nbsp;&nbsp;<?=$cost_details[0]['total_quantity']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total
                        Sales:- Rs.<?= $cost_details[0]['total_sale']?></th>
                </tr>
                <tr>
                    <th>Sl No.</th>
                    <th>Consumer Name</th>
                    <th>Liquor Details</th>
                    <th>Quantity</th>
                    <th>Order Value</th>
                    <th>Ordered Time</th>
                    <th>Issued By</th>
                    <th>Issued Time</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                <?php foreach($details as $row): ?>
                <tr>
                    <td>
                        <?= ++$i;?>
                    </td>
                    <td>
                        <?=$row['customer_name']?>
                    </td>

                    <td>
                        <?=$row['Liquor_details']?>
                    </td>
                    <td>
                        <?=$row['dispatch_quantity']?>
                    </td>
                    <td>
                        <?=$row['dispatch_total_cost_bottles']?>
                    </td>
                    <td>
                        <?=$row['order_time']?>
                    </td>
                    <td>
                        <?=$row['firstname']?>
                    </td>
                    <td>
                        <?=$row['dispatch_time']?>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>


        </table>
    </div>
</div>
<?php  }  ?>
</div>
</div>
<!-- /.content-wrapper -->
<link href="<?= base_url()?>assets/plugins/datepicker/datepicker3.css" rel="stylesheet">
<script src="<?= base_url()?>assets/plugins/datepicker/bootstrap-datepicker.js" defer></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css">
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>
<script rel="stylesheet" src="<?= base_url()?>assets/plugins/datatablesbtn/jquery-3.5.1.js"></script>
   <script src="<?= base_url()?>assets/plugins/datatablesbtn/jquery.dataTables.min.js" defer></script>
   <script src="<?= base_url()?>assets/plugins/datatablesbtn/dataTables.bootstrap4.min.js" defer></script>
   <script src="<?= base_url()?>assets/plugins/datatablesbtn/dataTables.buttons.min.js" defer></script>
   <script src="<?= base_url()?>assets/plugins/datatablesbtn/jszip.min.js"></script>
   <script src="<?= base_url()?>assets/plugins/datatablesbtn/pdfmake.min.js"></script>
   <script src="<?= base_url()?>assets/plugins/datatablesbtn/vfs_fonts.js"></script>
   <script src="<?= base_url()?>assets/plugins/datatablesbtn/buttons.html5.min.js" defer></script>



<script type="text/javascript">
$(document).ready(function() {

    $('#start_date').datepicker({
        format: 'yyyy-mm-dd',
        endDate: "today",
        orientation: 'top'
    }).on('changeDate', function() {
        // set the "toDate" start to not be later than "fromDate" ends:
        $("#end_date").datepicker('setStartDate', $(this).val());
    });
    $('#end_date').datepicker({
        format: 'yyyy-mm-dd',
        endDate: "today",
        orientation: 'top'
    }).on('changeDate', function() {
        // set the "toDate" start to not be later than "fromDate" ends:
        $("#start_date").datepicker('setEndDate', $(this).val());
    });

    $('#na_datatable').DataTable({
        dom: 'Bfrtip',

        buttons: [{
            extend: 'pdf',
            title: 'Sales Report'
        }]
    });

});
</script>