  <?php  $form_data=$this->session->flashdata('form_data');   ?>
  <!-- Content Wrapper. Contains page content -->
    <link  href="<?= base_url()?>assets/plugins/select2/select2.css" rel="stylesheet" />
    <script src="<?= base_url()?>assets/plugins/select2/select2.js" defer></script>
<style >
  .mandatory{
    color: red;
  }
</style>
  <!-- <div class="content-wrapper"> -->
    <!-- Main content -->
    <section class="content">
      <div class="card card-default color-palette-bo">
        <div class="card-header">
          <div class="d-inline-block">
            <h3 class="card-title"> <i class="fa fa-pencil"></i>
              <?= trans('edit_transporter') ?></h3>
            </div>
            <div class="d-inline-block float-right">
              <a href="<?= base_url('transporter/TransporterAPI/transporter_list'); ?>" class="btn btn-secondary"><i class="fa fa-list"></i> <?= trans('transporter_list') ?></a>
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

                    <?php echo form_open(base_url('transporter/TransporterAPI/edit/'.$transporter['id']), 'class="form-horizontal"');  ?> 
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-6">

                          <label for="type" class="col-md-12 control-label"><?= trans('select_transporter_type') ?><sup class="mandatory">*</sup></label>
                          <div class="col-md-12">
                            <select name="transporter_type" id="trans_type" class="form-control" >
                              <option value="0" disabled selected ><?= trans('select_transporter_type') ?></option>
                              <option value="1" <?= ((isset($form_data['transporter_type']) AND $form_data['transporter_type']==1)? "selected":""); ?><?= ($transporter['transporter_type']==1? "selected":""); ?>>Reefer</option>
                              <option value="2" <?= ((isset($form_data['transporter_type']) AND $form_data['transporter_type']==2)? "selected":""); ?> <?= ($transporter['transporter_type']==2? "selected":""); ?>>Ambient</option>
                              <option value="3" <?= ((isset($form_data['transporter_type']) AND $form_data['transporter_type']==3)? "selected":""); ?> <?= ($transporter['transporter_type']==3? "selected":""); ?>>PTL</option>
                            </select>
                          </div> 

                        </div>
                        <div class="col-md-6">
                          <label for="plant" class="col-md-12 control-label"><?= trans('select_plant') ?><sup class="mandatory">*</sup></label>
                          <div class="col-md-12">
                            <select name="plant[]" class="form-control" multiple  id="plant">
                              <option value="" disabled ><?= trans('select_plant') ?></option>
                              <?php foreach($plants as $plant): ?>
                                <option value="<?= $plant['id']; ?>"><?= $plant['plant_name']; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>                     
                      </div>
                    </div>
                    <div class="form-group">
                     <div class="row">
                      <div  class="col-sm-6">
                        <label for="name" class="col-md-12 control-label"><?= trans('transporter_name') ?><sup class="mandatory">*</sup></label>
                        <div class="col-md-12">
                         <input type="text" name="name" onkeypress="return /[A-Za-z]/i.test(event.key)" maxlength="30" class="form-control" id="name" value="<?= (isset($form_data['name'])? $form_data['name']:"");?><?= $transporter['transporter_name'] ?>" placeholder="">
                       </div>
                     </div>
                     <div  class="col-sm-6">
                      <label for="contactperson" class="col-md-12 control-label"><?= trans('contact_person') ?><sup class="mandatory">*</sup></label>
                      <div class="col-md-12">
                       <input type="text" name="contactperson" onkeypress="return /[A-Za-z ]/i.test(event.key)" maxlength="30" class="form-control" id="contactperson" value="<?= (isset($form_data['contactperson'])? $form_data['contactperson']:"");?><?= $transporter['contact_person'] ?>" placeholder="">
                     </div>
                   </div>
                 </div>
               </div>

               <div class="form-group">
                 <div class="row">
                  <div class="col-sm-6">
                    <label for="email" class="col-md-12 control-label"><?= trans('email') ?><sup class="mandatory">*</sup></label>
                    <div class="col-md-12">
                      <input type="email" name="email" class="form-control" maxlength="60" id="email" placeholder="" value="<?= (isset($form_data['email'])? $form_data['email']:"");?><?= $transporter['email_id'] ?>">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <label for="mobile_no" class="col-md-12 control-label"><?= trans('mobile_no') ?><sup class="mandatory">*</sup></label>
                    <div class="col-md-12">
                      <input type="text" name="mobile_no" class="form-control" onkeypress="return /[0-9]/i.test(event.key)" maxlength="10" id="mobile_no" value="<?= (isset($form_data['mobile_no'])? $form_data['mobile_no']:"");?><?= $transporter['phone_number'] ?>" placeholder="">
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <div class="form-group">
                <div class="col-md-12">
                  <input type="submit" name="submit" value="<?= trans('update_transporter') ?>" class="btn btn-primary pull-right">
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

<script type="text/javascript">
  $( document ).ready(function() {

    $('#plant').select2();

    var selectedPlant= "<?= $transporter['plant_id'] ?>";
    var plantArray= selectedPlant.split(",");
    $('#plant').val(plantArray).trigger('change');
     
  });

</script>

<!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
-->
