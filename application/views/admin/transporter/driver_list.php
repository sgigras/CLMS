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
          <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?= trans('driver_list') ?></h3>
        </div>
        <div class="d-inline-block float-right">
            <a href="<?= base_url('transporter/DriverAPI/add'); ?>" class="btn btn-secondary"><i class="fa fa-plus"></i> <?= trans('add_new_driver') ?></a>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body table-responsive">
        <table id="na_datatable" class="table table-bordered table-striped" width="100%">
          <thead>
            <tr>
              <th><?= trans('id') ?></th>
              <th><?= trans('driver_name') ?></th>
              <th><?= trans('mobile_no') ?></th>
              <!-- <th><?= trans('driver_license_no') ?></th>
              <th><?= trans('expiry_date') ?></th>
              <th><?= trans('dl_photo') ?></th> -->
              <th><?= trans('status') ?></th>
              <th width="100" class="text-right"><?= trans('action') ?></th>
            </tr>
          </thead>
          <tbody>
          <?php $i=0; ?>
            <?php foreach($driver_data as $row): ?>
              <tr>
            	  <td>
					          <?= ++$i;?>
                </td>
                <td>
					          <h4 class="m0 mb5"><?=$row['drivername']?></h4>
                </td>
                <td>
                    <?=$row['mobileno']?>
                </td> 
                <!-- <td>
					          <?=$row['dl_no']?>
                </td>
                <td>
                  <?=$row['expiry_dl']?>
                </td>
                <td class="text-center">
                  <img src="<?=base_url($row['img_driver_licence_path'])?>" class="elevation-2" alt="Driver Image" width="75px">
                </td>  -->
                <td><input class='tgl tgl-ios tgl_checkbox' 
                    data-id="<?=$row['driver_id']?>" 
                    id='cb_<?=$row['driver_id']?>' 
                    type='checkbox' <?php echo ($row['is_active'] == 1)? "checked" : ""; ?> />
                    <label class='tgl-btn' for='cb_<?=$row['driver_id']?>'></label>
                </td>
                <td>
                    <a href="<?= base_url("transporter/DriverAPI/edit/".$row['driver_id']); ?>" title="Edit" class="btn btn-warning btn-xs mr5" >
                    <i class="fa fa-edit"></i>
                    </a>
                    <!-- <a href="<?= base_url("admin/admin/delete/".$row['driver_id']); ?>" onclick="return confirm('are you sure to delete?')" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a> -->
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
        </table>
      </div>
    </div>
  </section>  
</div>

<!-- <div id="myModal" class="modal" role="dialog" style="width:1000px;padding-left: 500px;" aria-hidden="true">
    <span class="close cursor" onclick="closeModal()">&times;</span>
    <div class="modal-content">

      <div class="mySlide">
        <img id="modal_image" style="width:100%">
      </div>

    </div>
  </div> -->



<!-- DataTables -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>
<!-- <script src="<?= base_url() ?>assets/plugins/lightbox/lightbox.js"></script> -->

<script>
  //---------------------------------------------------

  $('#na_datatable').DataTable();
  // var table = $('#na_datatable').DataTable( {
  //   "processing": true,
  //   "serverSide": false,
  //   "ajax": "<?=base_url('transporter/DriverAPI/datatable_json')?>",
  //   "order": [[1,'asc']],
  //   "columnDefs": [
  //   { "targets": 0, "name": "id", 'searchable':true, 'orderable':true},
  //   { "targets": 1, "name": "driver_name", 'searchable':true, 'orderable':true},
  //   { "targets": 2, "name": "mobile_no", 'searchable':true, 'orderable':true},
  //   { "targets": 3, "name": "driver_license_no", 'searchable':true, 'orderable':true},
  //   { "targets": 4, "name": "expiry_date", 'searchable':true, 'orderable':true},
  //   { "targets": 5, "name": "dl_photo", 'searchable':true, 'orderable':true},
  //   { "targets": 6, "name": "created_at", 'searchable':false, 'orderable':false},
  //   { "targets": 7, "name": "is_active", 'searchable':true, 'orderable':true},
  //   { "targets": 8, "name": "Action", 'searchable':false, 'orderable':false,'width':'100px'}
  //   ]
  // });
</script>


<script type="text/javascript">

  $("body").on("change",".tgl_checkbox",function(){
    // console.log('checked');
    $.post('<?=base_url("transporter/DriverAPI/change_status")?>',
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


