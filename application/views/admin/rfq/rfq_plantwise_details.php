<!-- Author:Hriday Mourya
Subject:RFQ List Viewpage
Date:16-09-21 -->

<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css"> 

<style>
.mandatory{
  color: red;
}
#bold{
  font-weight: bold;
}
</style>

<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->
  <link  href="<?= base_url()?>assets/plugins/select2/select2.css" rel="stylesheet">
  <script src="<?= base_url()?>assets/plugins/select2/select2.js" ></script>
  <section class="content">
    <!-- For Messages -->
    <?php $this->load->view('admin/includes/_messages.php') ?>

    <section class="content">

      <div class="card card-default color-palette-bo">
        
          <div class="card-body">
             
            <div class="row">
              <div class="col-md-12">
                <div class="box">
                  <!-- form start -->
                  <div class="box-body">
                    <!-- For Messages -->
                    <?php $this->load->view('admin/includes/_messages.php') ?>
                    <?php echo form_open(base_url('rfq/RequestquotationAPI/plantwise_view'), 'class="form-horizontal"');  ?> 
                  <div class="form-group" id="vehicle">
                   <div class="d-inline-block">
            <h3 style="font-weight:bold;" class="card-title">&nbsp; 
              <?= trans('rfq_details') ?></h3>
            </div>
                    <div class="card-body" >
                      <div class="row">
                        <div class="col-md-12">
                          <div class="box">
                            <!-- form start -->
                            <div class="box-body">
                              <div class="form-group">
                                <div class="row">
                                  
                                  <div class="col-sm-12">
                                    <div class="card">
                                      <div class="card-body table-responsive">
                                        <table id="na_datatable" class="table table-hover datatable datatable-bordered table-bordered datatable-striped"  width="100%">
                                          <thead class="m0 mb5" style="background-color:  #E8E8E8;">
                                            <?php foreach($info  as $row): ?>
                                              <tr>
                                                <th><?= trans('order_no') ?>:&nbsp;&nbsp;<?=$row['order_no']?></th>
                                                <th><?= trans('details') ?></th>
                                              </tr>

                                            </thead>
                                            <tbody>
                                              <tr>
                                                <td id="bold"><?= trans('mode') ?></td>
                                                <td> <?=$row['mode']?></td>

                                              </tr>

                                              
                                              <tr>
                                                <td id="bold"><?= trans('number_of_trucks') ?><br><br><br><?= trans('truck_type') ?></td>
                                                <td><table style='border: none;'>
                                                  <tr>
                                                  <?php $Content= explode(",",$row['no_of_trucks']);
                                                  foreach($Content as $cnt): ?>
                                                      
                                                      <td><?= $cnt ?></td>
                                                    
                                                  <?php endforeach;  ?>
                                                   </tr><tr>
                                                  <?php $truck_type= explode(",",$row['truck_type']);
                                                  foreach($truck_type as $cnt): ?>
                                                   
                                                      
                                                      <td><?= $cnt ?></td>
                                                    
                                                    
                                                  <?php endforeach;  ?>
                                                </tr>
                                                </table></td>


                                              </tr>
                                              <tr>
                                                <td id="bold"><?= trans('picup_date') ?></td>
                                                <td>
                                                  <?=$row['picup_date']?>
                                                </td>

                                              </tr>
                                              <tr>
                                                <td id="bold"><?= trans('origin_add') ?><br><br><?= trans('origin_zip_code') ?></td>
                                                <td><table style='border: none;'>
                                                  <tr>
                                                  <?php $Content= explode("|",$row['origin_address']);
                                                  foreach($Content as $cnt): ?>
                                                      
                                                      <td><?= $cnt ?></td>
                                                    
                                                  <?php endforeach;  ?>
                                                   </tr><tr>
                                                  <?php $origin= explode(",",$row['origin_zip_code']);
                                                  foreach($origin as $cnt): ?>
                                                   
                                                      
                                                      <td><?= $cnt ?></td>
                                                    
                                                    
                                                  <?php endforeach;  ?>
                                                </tr>
                                                </table></td>


                                              </tr>
                                              

                                             <tr>
                                                <td id="bold"><?= trans('destination_add') ?><br><br><br><?= trans('des_city') ?><br><br><?= trans('destination_zip_code') ?></td>
                                                <td><table style='border: none;'>
                                                  <tr>
                                                  <?php $des_add= explode("|",$row['destination_address']);
                                                  foreach($des_add as $cnt): ?>
                                                      
                                                      <td><?= $cnt ?></td>
                                                    
                                                  <?php endforeach;  ?>
                                                   </tr><tr>
                                                  <?php $des_city= explode(",",$row['destination_city']);
                                                  foreach($des_city as $cnt): ?>
                                                   
                                                      
                                                      <td><?= $cnt ?></td>
                                                    
                                                    
                                                  <?php endforeach;  ?>
                                                </tr>
                                                <tr>
                                                  <?php $des_zip= explode(",",$row['destination_zip_code']);
                                                  foreach($des_zip as $cnt): ?>
                                                   
                                                      
                                                      <td><?= $cnt ?></td>
                                                    
                                                    
                                                  <?php endforeach;  ?>
                                                </tr>
                                                </table></td>


                                              </tr>
                                            <?php endforeach;?>
                                          </tbody>
                                        </table>
                                        <div class="card-footer mt-4 card-footer bg-transparent p-0 d-flex justify-content-end">
                                          <a href="<?= base_url('rfq/RequestquotationAPI/') ?>" class="btn btn-secondary mr-2" role="button" aria-pressed="true"><i class="fas fa-backspace mr-2"></i><?= trans('back') ?></a>

                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  
                                </div>
                              </div>
                            </div>
                            <!-- /.box-body -->
                          </div>
                        </div>
                      </div>  
                    </div>
                  
                </div>
                
                <?php echo form_close(); ?>
              </div>
              <!-- /.box-body -->
            </div>
          </div>
        </div>  
      </div>

    </div>
  </section> 

</div>

<!-- DataTables -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>
<script src="<?= base_url() ?>assets/plugins/lightbox/lightbox.js"></script>








