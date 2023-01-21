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


    <section class="content">
      <div class="card card-default color-palette-bo">
        <div class="card-header">
          <div class="d-inline-block">
            <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; 
              <?= trans('view_rfq') ?></h3>
            </div>
            <div class="d-inline-block float-right">
              <a href="<?= base_url('rfq/RequestquotationAPI/add'); ?>" class="btn btn-secondary"><i class="fa fa-plus"></i> <?= trans('add_rfq') ?></a>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="box">
                  <!-- form start -->
                  <div class="box-body">
                    <!-- For Messages -->
                    <?php $this->load->view('admin/includes/_messages.php') ?>
                    <?php echo form_open(base_url('rfq/RequestquotationAPI/corporate_view'), 'class="form-horizontal"');  ?> 
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-4">
                         <img src="<?=base_url('./assets/plugins/icons/auction.png')?>" class="" alt="" width="50px" height="50px">&nbsp;<label>RFQ Push to Gocomet For Auction</label>
                          </div>
                          <div class="col-md-3">
                        <img src="<?=base_url('./assets/plugins/icons/Verification_Pending.png')?>" class="" alt="" width="50px" height="50px">&nbsp;<label>Verification Pending</label> 
                      </div>
                      <div class="col-md-5">
                        <img src="<?=base_url('./assets/plugins/icons/vendor_det.png')?>" class="" alt="" width="50px" height="50px">&nbsp;<label>Transporter Details Received From Gocomet</label>
                           </div>
                       <div id="table_card" class="card-body table-responsive">
                        <table id="order" class="table table-hover table-bordered table-striped" width="100%">
                          <thead class="m0 mb5">
                            <tr>
                              <th><?= trans('id') ?></th>
                              <th><?= trans('order') ?></th>
                              <th><?= trans('shipper') ?></th>
                              <th><?= trans('truck_type') ?></th>
                              <th><?= trans('des_city') ?></th>
                              <th><?= trans('rfq_status') ?></th>
                              <th><?= trans('action') ?></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php $i=0; ?>
                            <?php foreach($rfq as $row): ?>
                              <tr>
                               <td>
                                 <?= ++$i;?>
                               </td>
                               <td class="text-center">
                                <?=$row['order_no']?>
                              </td>

                              <td class="text-center" >
                               <?=$row['plant_name_for_gocomet']?>
                             </td>

                             <td><table  style='border: none;'>
                              <?php $truck_type= explode(",",$row['truck_type']);
                              foreach($truck_type as $cnt): ?>
                                <tr>
                                  <td><?= $cnt ?></td>
                                </tr>
                              <?php endforeach;  ?>
                            </table></td>

                            
                            <td class="text-center"><table style='border: none;'>
                              <?php $des_city= explode(",",$row['destination_city']);
                              foreach($des_city as $city): ?>
                                <tr>
                                  <td><?= $city ?></td>
                                </tr>
                              <?php endforeach;  ?>
                            </table></td>


                           <td class="text-center" width="10%">
                            <?php $icon= (($row['rfq_status'] == 0)? './assets/plugins/icons/Verification_Pending.png': (($row['rfq_status'] == 1)? './assets/plugins/icons/auction.png': (($row['rfq_status'] == 2)? './assets/plugins/icons/vendor_det.png': 'None')));?>
                            <img src="<?=base_url($icon)?>"  alt="" width="70%" height="50%">
                          </td>

                          <td class="text-center">
                           <a title="View" id="change" class="view btn btn-sm btn-info" href="<?= base_url("rfq/RequestquotationAPI/corporate_view/".$row['id']) ?>"> <i class="fa fa-eye"></i></a>
                         </td>




                       </tr>
                     <?php endforeach;?>
                   </tbody>
                 </table>
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





<script type="text/javascript">
  $(document).ready(function(){
    $('#order').DataTable({
       "ordering": false
    });

  });
</script>



