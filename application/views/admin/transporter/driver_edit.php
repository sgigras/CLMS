<!-- Author:ujwal jain
Subject:Driver Registration Viewpage
Date:01-09-21 -->

<?php // $form_data=$this->session->flashdata('form_data');  ?>
<script src="<?= base_url()?>/assets/plugins/jquery/jquery.inputmask.js"></script>
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
            <?= trans('driver_registration') ?></h3>
          </div>
          <div class="d-inline-block float-right">
            <a href="<?= base_url('transporter/DriverAPI'); ?>" class="btn btn-secondary"><i class="fa fa-list"></i> <?= trans('driver_list') ?></a>
          </div>
        </div>


        <!-- /.card-header -->
        <!-- form start -->
        <?php $this->load->view('admin/includes/_messages.php') ?>
     
        <?php echo form_open_multipart(base_url('transporter/DriverAPI/edit/'.$driver['driver_id']), 'class="form-horizontal"');  ?> 
        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
              <!-- text input -->
              <div class="form-group">
                <label><?= trans('driver_name') ?><sup class="mandatory">*</sup></label>
                <input type="text" name="driver_name"  onkeypress="return /[A-Za-z ]/i.test(event.key)" maxlength="45" class="form-control" placeholder="<?= trans('driver_name') ?>" value="<?= $driver['drivername'] ?>">
              </div>
            </div>

            <div class="col-sm-6">
              <!-- text input -->
              <div class="form-group">
                <label><?= trans('mobile_no') ?><sup class="mandatory">*</sup></label>
                <input type="text" name="mobile_no"  onkeypress="return /[0-9]/i.test(event.key)" maxlength="10"  class="form-control" placeholder="<?= trans('mobile_no') ?>" value="<?= $driver['mobileno'] ?>">
              </div>
            </div>

            <!-- <div class="col-sm-6"> -->
              <!-- text input -->
              <!-- <div class="form-group">
                <label><?= trans('driver_license_no') ?><sup class="mandatory">*</sup></label>
                <input type="text" name="driver_license_no" maxlength="20"  onkeypress="return /[A-Z0-9 -]/i.test(event.key)" onkeyup="this.value = this.value.toUpperCase()"  class="form-control" placeholder="<?= trans('driver_license_no') ?>" value="<?= $driver['dl_no']  ?>">
              </div>
            </div> -->
           
           
            <!-- <div class="col-sm-6">
              <div class="form-group">
                <label><?= trans('commercial_dl_expiry_date') ?><sup class="mandatory">*</sup></label>
                <input type="date" name="commercial_dl_expiry_date" id="commercial_dl_expiry_date" class="form-control from-datepicker" placeholder="yyyy-mm-dd" value="<?= $driver['expiry_dl'] ?>">
              </div>
            </div> -->

             <!-- <div class="col-sm-6"> -->
              <!-- text input -->
              <!-- <div class="form-group">
                <label><?= trans('aadhar_no') ?><sub class="non_mandatory">(non mandatory)</sub></label>
                <input type="text" name="aadhar_no" data-inputmask="'mask': '9999 9999 9999'" maxlength="20" class="form-control input-field" placeholder="<?= trans('aadhar_no') ?>" value="<?= $driver['aadhar_no'] ?>">
              </div>
            </div> -->

            <!-- <div class="col-md-6">
              <div class="form-group">
                <div class="input-group-prepend">
                   <label><?= trans('upload_dl_photo') ?><sup class="mandatory">*</sup></label>
                </div>
                <div class="custom-file">
                   <input type="file" name="upload_dl_photo" accept="image/*"  id="upload_dl_photo" class="custom-file-input">
                   <label class="custom-file-label" for="upload_dl_photo"><?= trans('upload_photo') ?></label>
                   <input type="hidden" id="hiddenupload" name="hiddenupload" value='0'>
                </div>
                <div class="uploaded-image " id="UploadedImage">
                <div class="d-flex align-items-center">
                  <img src="<?= base_url($driver['img_driver_licence_path']) ?>" class=" elevation-2" alt="Driver Image" width="75px">
                  <p class="mb-0 ml-3" id="image_name"></p>
                </div>
              </div>  
            </div> -->
          </div>

            <div class="col-md-12">
              <div class="form-group">
                <div class="card-footer mt-4 card-footer bg-transparent p-0 d-flex justify-content-end">
                  <!-- <button class="btn btn-light mr-2"><i class="fa fa-backspace mr-2"></i><?= trans('back') ?></button> -->
                  <!-- <button class="btn btn-warning mr-2"><i class="fa fa-eraser mr-2"></i><?= trans('reset') ?></button> -->
                  <button data-toggle="modal" data-target="#modal-sm" type="submit" value="<?= trans('update_driver') ?>" name="submit" class="btn btn-success"><i class="fa fa-save mr-2"></i><?= trans('update_driver') ?></button>  
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



<!-- <script src="<?= base_url() ?>assets/plugins/lightbox/lightbox.js"></script> -->
<!-- ./wrapper -->

  <script type="text/javascript">
  
    $(document).ready(function(){
      // var image_path="<?= $driver['img_driver_licence_path'] ?>";
      // $(":input").inputmask();
      // var image_name= image_path.split("/")[4];
      // console.log(image_name);
      // $("#image_name").html(image_name);

    // $(document).on('change', 'input[type="file"]', function (event) { 
    //   var filename = $(this).val();
    //   if (filename == undefined || filename == ""){
    //     $(this).next('.custom-file-label').html('No file chosen');
    //   }
    //   else 
    //     { 
    //       $('#UploadedImage').hide();
    //       $('#hiddenupload').val('1');
    //       $(this).next('.custom-file-label').html(event.target.files[0].name); }
    // });

  //      $(function(){
  //   var dtToday = new Date();
    
  //   var month = dtToday.getMonth() + 1;
  //   var day = dtToday.getDate();
  //   var year = dtToday.getFullYear();
  //   if(month < 10)
  //     month = '0' + month.toString();
  //   if(day < 10)
  //     day = '0' + day.toString();
  //   var maxDate = year + '-' + month + '-' + day;
  //   $('#commercial_dl_expiry_date').attr('min', maxDate);
   
  
  // });

  });
  </script>