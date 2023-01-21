<!-- Content Wrapper. Contains page content -->
<?php
// $resultArray = (isset($canteen_club_data)) ? $canteen_club_data[0] : new stdClass;
// $city_select_array = (isset($city_list)) ? $city_list : array();
// $distributor_name_select_array = (isset($distributor_name_list)) ? $distributor_name_list : array();
?>
<!--<div class="content-wrapper" style="margin-top: 55px;min-height: 580.08px !important;">-->
<!-- Main content -->
<link rel="stylesheet" href="<?= base_url()?>assets/dist/css/style.css">
<link rel="stylesheet" href="<?= base_url()?>assets/plugins/animation/animate.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/iCheck/all.css">
<style>
   .submitstyle{
        background-color:  #dc3545;
        width:120px;
        height: 38px;
        border-radius:20px;
        text-align: center;
        color:white;
    }

    .addstyle{
        background-color:  #0b095a;
        width:120px;
        height: 38px;
        border-radius:20px;
        text-align: center;
        color:white;
    }
</style>
<section class="content">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-plus"></i>
                    Cart Details </h3>
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
           
            <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Select2</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
          <div class="cart-table clearfix">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th><center><h6>Name</h6></center></th>
                                        <th><center><h6>Price</h6></center></th>
                                        <th><center><h6>Quantity</h6></center></th>
                                        <th><center><h6>Check</h6></center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="cart_product_img animate__animated animate__fadeInLeft">
                                            <a href="#"><img src="<?= base_url()?>assets/dist/img/product-img/jackdaniels.jpg" alt="Product" class="img-size"></a>
                                        </td>
                                        <td class="cart_product_desc">
                                            <h5 class="lineheight">White Modern Chair</h5>
                                        </td>
                                        <td class="price">
                                            <span class="lineheight">$130</span>
                                        </td>
                                        <td class="qty" style="width:300px">
                                        
                                        <div class="row lineheight">
                                            <div class="col-sm-2 qty">      
                                                <button class="btn" style="margin-left: 9px;">-</button>
                                            </div>
                                            <div class="col-sm-6 qty-number" style="padding-top: 83px">      
                                            <input type="text" class="form-control" style="background-color: #d6d6d7; height: 37px; color: black;text-align:center" id="qty" step="1"  name="quantity" value="1">
                                            </div>
                                            <div class="col-sm-2 qtyplus" >      
                                            <button class="btn">+</button>  
                                            </div>                         
                                        </div>  
                                        </td>
                                        <td>
                                        <label  class="lineheight">
                                            <input type="checkbox" class="flat-red" >
                                          
                                        </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cart_product_img animate__animated animate__fadeInLeft">
                                            <a href="#"><img src="<?= base_url()?>assets/dist/img/product-img/oldmonk.jpg" alt="Product" class="img-size"></a>
                                        </td>
                                        <td class="cart_product_desc">
                                            <h5 class="lineheight">Minimal Plant Pot</h5>
                                        </td>
                                        <td class="price">
                                            <span class="lineheight">$10</span>
                                        </td>
                                        <td class="qty" style="width:300px">
                                        
                                        <div class="row lineheight">
                                            <div class="col-sm-2 qty">      
                                                <button class="btn" style="margin-left: 9px;">-</button>
                                            </div>
                                            <div class="col-sm-6 qty-number" style="padding-top: 83px">      
                                            <input type="text" class="form-control" style="background-color: #d6d6d7; height: 37px; color: black;text-align:center" id="qty" step="1"  name="quantity" value="1">
                                            </div>
                                            <div class="col-sm-2 qtyplus" >      
                                            <button class="btn">+</button>  
                                            </div>                         
                                        </div>  
                                        </td>
                                        <td>
                                        <label  class="lineheight">
                                            <input type="checkbox" class="flat-red" >
                                            
                                        </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cart_product_img animate__animated animate__fadeInLeft">
                                            <a href="#"><img src="<?= base_url()?>assets/dist/img/product-img/hienken_vodka.jpg" alt="Product" class="img-size"></a>
                                        </td>
                                        <td class="cart_product_desc">
                                            <h5 class="lineheight">Minimal Plant Pot</h5>
                                        </td>
                                        <td class="price">
                                            <span class="lineheight">$10</span>
                                        </td>
                                        <td class="qty" style="width:300px">
                                        
                                        <div class="row lineheight">
                                            <div class="col-sm-2 qty">      
                                                <button class="btn" style="margin-left: 9px;">-</button>
                                            </div>
                                            <div class="col-sm-6 qty-number" style="padding-top: 83px">      
                                            <input type="text" class="form-control" style="background-color: #d6d6d7; height: 37px; color: black;text-align:center" id="qty" step="1"  name="quantity" value="1">
                                            </div>
                                            <div class="col-sm-2 qtyplus" >      
                                            <button class="btn">+</button>  
                                            </div>                         
                                        </div>  
                                        </td>
                                        <td>
                                        <label  class="lineheight">
                                            <input type="checkbox" class="flat-red" >
                                           
                                        </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div>
                                <center><button class="btn addstyle">Add Product</button> &nbsp;&nbsp;&nbsp;<button class="btn submitstyle">Recreate cart</button></center>
                            </div>
                        </div>
            <!-- /.row -->
          </div>
          <!-- /.card-body -->
          
        </div> 
                   
               




        
            <?php echo form_close(); ?>
        </div>
        <!-- /.box-body -->
    </div>
</section> 
<!--</div>-->
<script src="<?= base_url() ?>assets/js/module/common/validation.js"></script>
<script src="<?= base_url() ?>assets/js/module/canteen/canteenDetail.js"></script>
<script src="<?= base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
<script>   //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })</script>