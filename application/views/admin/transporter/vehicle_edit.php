<!-- Author:Hriday Mourya
Subject:Vehicle edit Viewpage
Date:01-09-21 -->

<?php  $form_data=$this->session->flashdata('form_data');   ?>
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/lightbox/lightbox.css"> 


<style >
.mandatory{
  color: red;
}
</style>
<!-- <div class="content-wrapper"> -->
  <section class="content">
    <div class="card card-default color-palette-bo">
      <div class="card-header">
        <div class="d-inline-block">
          <h3 class="card-title"> <i class="fa fa-plus"></i>
            <?= trans('vehicle_registration') ?></h3>
          </div>
          <div class="d-inline-block float-right">
            <a href="<?= base_url('transporter/VehicleAPI/vehicle_list'); ?>" class="btn btn-secondary"><i class="fa fa-list"></i> <?= trans('vehicle_list') ?></a>
          </div>
        </div>


        <!-- /.card-header -->
        <!-- form start -->
        <?php $this->load->view('admin/includes/_messages.php') ?>
        <?php echo form_open_multipart(base_url('transporter/VehicleAPI/edit/'.$vehicle['vehicleid']), 'class="form-horizontal"');  ?> 
        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
              <!-- text input -->
              <div class="form-group">
                <label><?= trans('vehicle_no') ?><sup class="mandatory">*</sup></label>
                <input type="text" name="vehicle_no" onkeypress="return /[A-Z0-9 ]/i.test(event.key)" onkeyup="this.value = this.value.toUpperCase()" value="<?= (isset($form_data['vehicle_no'])? $form_data['vehicle_no']:"");?><?= $vehicle['vehicleno'] ?>" class="form-control" placeholder="<?= trans('e_vehicle_no') ?>">
              </div>
            </div>
            <div class="col-sm-6">
              <!-- text input -->
              <div class="form-group">
                <label><?= trans('vehicle_type') ?><sup class="mandatory">*</sup></label>
                <select class="form-control select2" name="vehicle_type" style="width: 100%;">
                  <option value="0" disabled selected><?= trans('select_vehicle_type') ?></option>
                  <option value="1" <?= ((isset($form_data['vehicle_type']) AND $form_data['vehicle_type']==1)? "selected":""); ?><?= ($vehicle['vehicle_type']==1? "selected":""); ?>>Reefer</option>

                  <option value="2" <?= ((isset($form_data['vehicle_type']) AND $form_data['vehicle_type']==2)? "selected":""); ?><?= ($vehicle['vehicle_type']==2? "selected":""); ?>>Ambient</option>
                 
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <div class="input-group-prepend">
                 <label><?= trans('select_plant') ?><sup class="mandatory">*</sup></label>
               </div>
               <div class="custom-file">
                <select name="plant" class="form-control">
                  <option value=""  disabled><?= trans('select_plant') ?></option>
                  <?php foreach($transporter_plants as $plant): ?>
                    <option value="<?= $plant['id']; ?>" <?= ($vehicle['plant_id'] ==$plant['id']? "selected":""); ?>><?= $plant['plant_name']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div> 

          <div class="col-sm-6">
            <div class="form-group">
              <label><?= trans('capacity_in_mt') ?><sup class="mandatory">*</sup></label>
              <input type="text" maxlength="6" onkeypress="return /[0-9.]/i.test(event.key)" name="capacity_in_mt" value="<?= (isset($form_data['capacity_in_mt'])? $form_data['capacity_in_mt']:"");?><?= $vehicle['capacity'] ?>" class="form-control" placeholder="<?= trans('e_capacity_in_mt') ?>">
            </div>
          </div>
          <div class="col-sm-6">
            <!-- text input -->
            <div class="form-group">
              <label><?= trans('box_capacity') ?><sup class="mandatory">*</sup></label>
              <input type="text" maxlength="3" onkeypress="return /[0-9]/i.test(event.key)" name="box_capacity" value="<?= (isset($form_data['box_capacity'])? $form_data['box_capacity']:"");?><?= $vehicle['box_count'] ?>" class="form-control" placeholder="<?= trans('e_box_capacity') ?>">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label><?= trans('insurance_expiry_date') ?><sup class="mandatory">*</sup></label>
              <input type="date" name="insurance_expiry_date" id="insurance_date" value="<?= (isset($form_data['insurance_expiry_date'])? $form_data['insurance_expiry_date']:"");?><?= $vehicle['expiry_insurance'] ?>" class="form-control from-datepicker"  max="9999-12-31" placeholder="yyyy-mm-dd">

            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label><?= trans('puc_expiry_date') ?><sup class="mandatory">*</sup></label>
              <input type="date" name="puc_expiry_date" id="puc_expiry_date" value="<?= (isset($form_data['puc_expiry_date'])? $form_data['puc_expiry_date']:"");?><?= $vehicle['expiry_puc'] ?>" max="9999-12-31" class="form-control from-datepicker" placeholder="yyyy-mm-dd">

            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label><?= trans('r/c_expiry_date') ?><sup class="mandatory">*</sup></label>
              <input type="date" name="r/c_expiry_date" id="rc_expiry_date" value="<?= (isset($form_data['r/c_expiry_date'])? $form_data['r/c_expiry_date']:"");?><?= $vehicle['expiry_rto'] ?>" max="9999-12-31" class="form-control from-datepicker" placeholder="yyyy-mm-dd">
            </div>
          </div>

<div class="col-md-12">
  <div class="form-group">
    <div class="card-footer mt-4 card-footer bg-transparent p-0 d-flex justify-content-end">
      <button class="btn btn-warning mr-2"><i class="fa fa-eraser mr-2"></i><?= trans('reset') ?></button>
      <button type="submit" name="submit" value="<?= trans('save') ?>" id="vehicle_add" class="btn btn-success"><i class="fa fa-save mr-2"></i><?= trans('update_vehicle') ?></button>
    </div>
  </div>
</div>

<?php echo form_close(); ?>
</div><!-- /.container-fluid -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->




<script src="<?= base_url() ?>assets/plugins/lightbox/lightbox.js"></script>
<script type="text/javascript"> 
  
  $(function(){
    var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
      month = '0' + month.toString();
    if(day < 10)
      day = '0' + day.toString();
    var maxDate = year + '-' + month + '-' + day;
    $('#insurance_date').attr('min', maxDate);
    $('#puc_expiry_date').attr('min', maxDate);
    $('#rc_expiry_date').attr('min', maxDate);
  });


</script>