<!-- Content Wrapper. Contains page content -->
<?php
// $resultArray = (isset($canteen_club_data)) ? $canteen_club_data[0] : new stdClass;
// $city_select_array = (isset($city_list)) ? $city_list : array();
// $distributor_name_select_array = (isset($distributor_name_list)) ? $distributor_name_list : array();
?>
<!--<div class="content-wrapper" style="margin-top: 55px;min-height: 580.08px !important;">-->
<!-- Main content -->
<style>
    .card{
        margin-top:30px;
        
        width:600px;
        border: 1px solid #34343434;
        border-radius:10px;
       
       
    }
   .head{
       color: #0b095a;
       font-weight:bold;
   }

   .total{
       font-size:20px;
       color:#dc3545;
   }
   .thankyou
   {
    color:#fbb710;  
   }
   
</style>
<section class="content">
    <center>
    <div class="card">
        <div class="card-header">
            <div class="d-inline-block">
               
            <center> <h3>Order Summary</h3> </center>
            </div>
            
        </div>
        <div class="card-body">

            <!-- For Messages -->
            <?php // $this->load->view('admin/includes/_messages.php')   ?>

            <?php
            // $redirect_url = ($mode == 'A') ? 'master/CanteenMaster/addCanteenClub' : 'master/CanteenMaster/editCanteenClub/' . getValue('id', $resultArray);
            // echo form_open(base_url($redirect_url), 'class="form-horizontal"');
            ?> 
            
        <div>
            <p style="font-size:18px">Your order details are as follows:</p>
            <br>
            <div class="row head">
                <div class="col-sm-4">Item</div>
                <div class="col-sm-4">Quantity</div>
                <div class="col-sm-4">Amount</div>
            </div>
            <hr style="background-color:#0b095a;">
            <br>
            <div class="row">
                <div class="col-sm-4">Vodka</div>
                <div class="col-sm-4">5</div>
                <div class="col-sm-4">$30</div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-4">Rum</div>
                <div class="col-sm-4">3</div>
                <div class="col-sm-4">$20</div>
            </div>
            <br>
            <hr>
            <div class="row">
                <div class="col-sm-4 total">Total</div>
                <div class="col-sm-4"></div>
                <div class="col-sm-4 total">$50</div>
            </div>
            <br><br>
            <div class="thankyou">
                <h4>Thankyou!</h4>
            </div>
        </div>
            <?php echo form_close(); ?>
        </div>
        <!-- /.box-body -->
    </div>
</center>
</section> 
<!--</div>-->
<script src="<?= base_url() ?>assets/js/module/common/validation.js"></script>
<script src="<?= base_url() ?>assets/js/module/canteen/canteenDetail.js"></script>