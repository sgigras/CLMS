<!-- Author:Hriday Mourya
Subject:Vehicle List Viewpage
Date:03-09-21 -->

<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css"> 
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/lightbox/lightbox.css"> 


<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->
  <section class="content">
    <!-- For Messages -->
    <?php $this->load->view('admin/includes/_messages.php') ?>
    <div class="card">
      <div class="card-header">
        <div class="d-inline-block">
          <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?= trans('vehicle_list') ?></h3>
        </div>
        <div class="d-inline-block float-right">
          <a href="<?= base_url('transporter/VehicleAPI/addvehicles'); ?>" class="btn btn-secondary"><i class="fa fa-plus"></i> <?= trans('add_new_vehicle') ?></a>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body table-responsive">
        <table id="na_datatable" class="table datatable datatable-bordered table-bordered datatable-striped" width="100%">
          <thead class="m0 mb5">
            <tr>
              <th><?= trans('id') ?></th>
              <th><?= trans('vehicle_no') ?></th>
              <th><?= trans('vehicle_type') ?></th>
              <th><?= trans('box_count') ?></th>
              <th><?= trans('capacity') ?></th>
              <th><?= trans('puc_expiry_date') ?></th>
              <th><?= trans('insurance_expiry_date') ?></th>
              <th><?= trans('r/c_expiry_date') ?></th>
              

              <th><?= trans('status') ?></th>
              <th width="100" class="text-right"><?= trans('action') ?></th>
            </tr>
          </thead>
          <tbody>
            <?php $i=0; ?>
            <?php foreach($vehicle_data as $row): ?>
              <tr>
               <td>
                 <?= ++$i;?>
               </td>
               <td>
                <?=$row['vehicleno']?>
              </td>
              <td>
                <?= ($row['vehicle_type'] == 1)? 'Reefer': (($row['vehicle_type'] == 2)? 'Ambient': 'PTL');?>
              </td> 
              <td>
               <?=$row['box_count']?>
             </td>
             <td>
              <?=$row['capacity']?>
            </td>
            <td>
              <?=$row['expiry_puc']?>
            </td>
            <td>
              <?=$row['expiry_insurance']?>
            </td>
            <td>
              <?=$row['expiry_rto']?>
            </td>
            
           


               <!--  <td class="text-center">
                  <img src="<?=base_url($row['img_driver_licence_path'])?>" class="elevation-2" alt="Driver Image" width="75px">
                </td>  -->
                <td><input class='tgl tgl-ios tgl_checkbox' 
                  data-id="<?=$row['vehicleid']?>" 
                  id='cb_<?=$row['vehicleid']?>' 
                  type='checkbox' <?php echo ($row['isactive'] == 1)? "checked" : ""; ?> />
                  <label class='tgl-btn' for='cb_<?=$row['vehicleid']?>'></label>
                </td>
                <td>
                  <a href="<?= base_url("transporter/VehicleAPI/edit/".$row['vehicleid']); ?>" title="Edit" class="btn btn-warning btn-xs mr5" >
                    <i class="fa fa-edit"></i>
                  </a>
                  <!-- <a href="<?= base_url("admin/admin/delete/".$row['admin_id']); ?>" onclick="return confirm('are you sure to delete?')" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a> -->
                </td>
              </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </section>  
</div>


<!-- DataTables -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>
<script src="<?= base_url() ?>assets/plugins/lightbox/lightbox.js"></script>





<script type="text/javascript">

  $(document).ready( function () {
    $('#na_datatable').DataTable();
  } );



  $("body").on("change",".tgl_checkbox",function(){
    // console.log('checked');
    $.post('<?=base_url("transporter/VehicleAPI/change_status")?>',
    {
      '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
      id : $(this).data('id'),
      status : $(this).is(':checked') == true?1:0
    },
    function(data){
      $.notify("Status Changed Successfully", "success");
    });
  });
</script>


