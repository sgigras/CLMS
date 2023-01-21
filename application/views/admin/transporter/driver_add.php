<!-- Author:ujwal jain
Subject:Driver Registration Viewpage
Date:01-09-21 -->

<?php  $form_data=$this->session->flashdata('form_data');  ?>
<script src="<?= base_url()?>/assets/plugins/jquery/jquery.inputmask.js"></script>
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
     
        <?php echo form_open_multipart(base_url('transporter/DriverAPI/add'), 'class="form-horizontal"');  ?> 
        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
              <!-- text input -->
              <div class="form-group">
                <label><?= trans('driver_name') ?><sup class="mandatory">*</sup></label>
                <input type="text" name="driver_name"  onkeypress="return /[A-Za-z ]/i.test(event.key)" maxlength="45" class="form-control" placeholder="<?= trans('driver_name') ?>" value="<?= (isset($form_data['driver_name'])? $form_data['driver_name']:""); ?>">
              </div>
            </div>

            <div class="col-sm-6">
              <!-- text input -->
              <div class="form-group">
                <label><?= trans('mobile_no') ?><sup class="mandatory">*</sup></label>
                <input type="text" name="mobile_no"  onkeypress="return /[0-9]/i.test(event.key)" maxlength="10"  class="form-control" placeholder="<?= trans('mobile_no') ?>" value="<?= (isset($form_data['mobile_no'])? $form_data['mobile_no']:""); ?>">
              </div>
            </div>

            <!-- <div class="col-sm-6"> -->
              <!-- text input -->
              <!-- <div class="form-group">
                <label><?= trans('driver_license_no') ?><sup class="mandatory">*</sup></label>
                <input type="text" name="driver_license_no" maxlength="16" id="driver_license_no"  onkeypress="return /[A-Z0-9 -]/i.test(event.key)" onkeyup="this.value = this.value.toUpperCase()"  class="form-control" placeholder="<?= trans('driver_license_no') ?>" value="<?= (isset($form_data['driver_license_no'])? $form_data['driver_license_no']:""); ?>">
                <span id="dl_span" style="color:red"></span>
              </div>
            </div> -->
           
           
            <!-- <div class="col-sm-6">
              <div class="form-group">
                <label><?= trans('commercial_dl_expiry_date') ?><sup class="mandatory">*</sup></label>
                <input type="date" name="commercial_dl_expiry_date" id="commercial_dl_expiry_date" class="form-control form-datepicker"  placeholder="yyyy-mm-dd" value="<?= (isset($form_data['commercial_dl_expiry_date'])? $form_data['commercial_dl_expiry_date']:""); ?>">
              </div>
            </div> -->

             <!-- <div class="col-sm-6"> -->
              <!-- text input -->
              <!-- <div class="form-group">
                <label><?= trans('aadhar_no') ?><sub class="non_mandatory">(non mandatory)</sub></label>
                <input type="text" name="aadhar_no" data-inputmask="'mask': '9999 9999 9999'" maxlength="20" class="form-control input-field" placeholder="<?= trans('aadhar_no') ?>" value="<?= (isset($form_data['aadhar_no'])? $form_data['aadhar_no']:""); ?>">
              </div>
            </div> -->
            
              <!-- <div class="col-md-6">
              <div class="form-group">
                <div class="input-group-prepend">
                 <label><?= trans('upload_dl_photo') ?><sup class="mandatory">*</sup></label>
               </div>
               <div class="custom-file">
                <input type="file" name="upload_dl_photo" accept="image/*"  id="upload_dl_photo" class="custom-file-input" value="<?= (isset($form_data['upload_dl_photo'])? $form_data['upload_dl_photo']:""); ?>">
                <label class="custom-file-label" for="upload_dl_photo"><?= trans('upload_photo') ?></label>
              </div>
            </div> -->
          </div>

            <div class="col-md-12">
              <div class="form-group">
                <div class="card-footer mt-4 card-footer bg-transparent p-0 d-flex justify-content-end">
                  <!-- <button class="btn btn-light mr-2"><i class="fa fa-backspace mr-2"></i><?= trans('back') ?></button> -->
                  <button class="btn btn-warning mr-2"><i class="fa fa-eraser mr-2"></i><?= trans('reset') ?></button>
                  <button data-toggle="modal" data-target="#modal-sm" type="submit" value="<?= trans('save') ?>" id="submit" name="submit" class="btn btn-success"><i class="fa fa-save mr-2"></i><?= trans('add_new_driver') ?></button>  
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




<!-- ./wrapper -->

  <script type="text/javascript">
    $(document).ready(function(){
    
    // $(":input").inputmask();
    

    // $("#driver_license_no").blur(function() {

    //   var reg = /^(([A-Z]{2}[0-9]{2})( )|([A-Z]{2}-[0-9]{2}))((19|20)[0-9][0-9])[0-9]{7}$/;
    //   if (!reg.test($("#driver_license_no").val())) {
    //     $('#dl_span').html("Your driving licence number is not valid.");
    //     $("#driver_license_no").val("");
    //   }else{
    //     $('#dl_span').html("");
    //   }
    // });

    // $(document).on('change', 'input[type="file"]', function (event) { 
    //   var filename = $(this).val();
    //   if (filename == undefined || filename == ""){
    //     $(this).next('.custom-file-label').html('No file chosen');
    //   }
    //   else 
    //     { $(this).next('.custom-file-label').html(event.target.files[0].name); }
    // });

  //   $(function(){
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