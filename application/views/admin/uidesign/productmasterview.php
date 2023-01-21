<!-- Content Wrapper. Contains page content -->
<?php
// $resultArray = (isset($canteen_club_data)) ? $canteen_club_data[0] : new stdClass;
// $city_select_array = (isset($city_list)) ? $city_list : array();
// $distributor_name_select_array = (isset($distributor_name_list)) ? $distributor_name_list : array();
?>
<!--<div class="content-wrapper" style="margin-top: 55px;min-height: 580.08px !important;">-->
<!-- Main content -->
<style>
    .photostyle{
        height: 310px;
        width:300px;
        border: 1px solid #34343434;
        border-radius:10px;
       
       
    }
    .btn{
        background-color: #0b095a;
        width:120px;
        height: 38px;
        border-radius:20px;
        text-align: center;
        color:white;
    }

    .submitstyle{
        background-color: #fbb710;
        width:120px;
        height: 38px;
        border-radius:20px;
        text-align: center;
        color:white;
    }
    .col-4{
        padding-left:10px !important;
       
        border-right: 1px solid #34343434;   
        /* margin-top:40px; */
    }
    .div-padding{
        padding-top:10px;
    }

    .col-6{
        margin-left:30px;  
    }
</style>
<section class="content">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-plus"></i>
                    Add Product </h3>
            </div>
            <div class="d-inline-block float-right">
                <a href="<?= base_url('master/CanteenMaster'); ?>" class="btn btn-secondary"><i class="fa fa-list"></i>  <?= trans('canteen_list') ?></a>
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
        </div>
        <div class="card-body">

            <!-- For Messages -->
            <?php // $this->load->view('admin/includes/_messages.php')   ?>

            <?php
            // $redirect_url = ($mode == 'A') ? 'master/CanteenMaster/addCanteenClub' : 'master/CanteenMaster/editCanteenClub/' . getValue('id', $resultArray);
            // echo form_open(base_url($redirect_url), 'class="form-horizontal"');
            ?> 

            <!--outlet and canteen name-->
            <div class="row">
                <div class="col-4">
                  <div class="photostyle"></div>
                  <br>
                  <div style="margin-top:10px;">
               <div class="form-group">
                  
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile" style="padding-right: 65%;">Choose file</label>
                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text"  id="">Upload</span>
                      </div>
                    </div>
                  </div>
                </div>
                </div>
                <div class="col-6">
                    <div>
                    <label>Name</label>
                    <input type="text" class="form-control"> 
                    </div>
                    
                    <div class="div-padding">
                    <label>Type</label>
                    <select class="form-control"> 
                        <option></option>
                    </select>
                    </div>
                   
                    <div class="div-padding">
                    <label>MOQ for Product</label>
                    <input type="text" class="form-control"> 
                    </div>
                   
                    <div class="div-padding">
                    <label>MOQ for Sale</label>
                    <input type="text" class="form-control"> 
                    </div>
                    <br>
                    <div class="div-padding">
                    <center>  <button class="btn submitstyle">Submit</button></center>
                    </div>
                   
               </div>
            </div>

            <!--state and city name-->
            <div class="row">
               
            </div>



            <!--Address -->
            <div class="row">
               
            </div>
            <!--executive supervisor-->
            <div class="row">
                
            </div>
            <!--</div>-->

            <!--distributor distributor name-->
            <div class="row">
              
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <!-- /.box-body -->
    </div>
</section> 
<!--</div>-->
<script src="<?= base_url() ?>assets/js/module/common/validation.js"></script>
<script src="<?= base_url() ?>assets/js/module/canteen/canteenDetail.js"></script>