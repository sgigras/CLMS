  <?php  $form_data=$this->session->flashdata('form_data');   ?>
  <!-- Content Wrapper. Contains page content -->
    <link  href="<?= base_url()?>assets/plugins/select2/select2.css" rel="stylesheet">
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
            <h3 class="card-title"> <i class="fa fa-plus"></i>
              <?= trans('add_new_transporter') ?></h3>
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

                    <?php echo form_open(base_url('transporter/TransporterAPI/add'), 'class="form-horizontal"');  ?> 
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-6">

                          <label for="type" class="col-md-12 control-label"><?= trans('select_transporter_type') ?><sup class="mandatory">*</sup></label>
                          <div class="col-md-12">
                            <select name="transporter_type" id="trans_type" class="form-control" >
                              <option value="0" disabled selected ><?= trans('select_transporter_type') ?></option>
                              <option value="1" <?= ((isset($form_data['transporter_type']) AND $form_data['transporter_type']==1)? "selected":""); ?>>Reefer</option>
                              <option value="2" <?= ((isset($form_data['transporter_type']) AND $form_data['transporter_type']==2)? "selected":""); ?>>Ambient</option>
                              <option value="3" <?= ((isset($form_data['transporter_type']) AND $form_data['transporter_type']==3)? "selected":""); ?>>PTL</option>
                            </select>
                          </div> 

                        </div>
                        <div class="col-md-6">
                          <label for="plant" class="col-md-12 control-label"><?= trans('select_plant') ?><sup class="mandatory">*</sup></label>
                          <div class="col-md-12">
                            <select name="plant[]" class="form-control" multiple  id="plant"  >
                              <option value="" disabled ><?= trans('select_plant') ?></option>
                              <?php foreach($plants as $plant): ?>
                                <option value="<?= $plant['id']; ?>" <?= ((isset($form_data['plant']) AND $plant['id']==$form_data['plant'])? "selected":""); ?>><?= $plant['plant_name']; ?></option>
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
                         <input type="text" name="name" onkeypress="return /[A-Za-z ]/i.test(event.key)" maxlength="30" class="form-control" id="name" value="<?= (isset($form_data['name'])? $form_data['name']:"");?>" placeholder="<?= trans('e_transporter_name') ?>">
                       </div>
                     </div>
                     <div  class="col-sm-6">
                      <label for="contactperson" class="col-md-12 control-label"><?= trans('contact_person') ?><sup class="mandatory">*</sup></label>
                      <div class="col-md-12">
                       <input type="text" name="contactperson" onkeypress="return /[A-Za-z ]/i.test(event.key)" maxlength="30" class="form-control" id="contactperson" value="<?= (isset($form_data['contactperson'])? $form_data['contactperson']:"");?>" placeholder="<?= trans('e_contact_person') ?>">
                     </div>
                   </div>
                 </div>
               </div>

               <div class="form-group">
                 <div class="row">
                  <div class="col-sm-6">
                    <label for="email" class="col-md-12 control-label"><?= trans('email') ?><sup class="mandatory">*</sup></label>
                    <div class="col-md-12">
                      <input type="email" name="email" class="form-control" id="email" maxlength="30" placeholder="<?= trans('email') ?>" value="<?= (isset($form_data['email'])? $form_data['email']:"");?>">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <label for="mobile_no" class="col-md-12 control-label"><?= trans('mobile_no') ?><sup class="mandatory">*</sup></label>
                    <div class="col-md-12">
                      <input type="text" name="mobile_no" onkeypress="return /[0-9]/i.test(event.key)" class="form-control" id="mobile_no" maxlength="10" value="<?= (isset($form_data['mobile_no'])? $form_data['mobile_no']:"");?>" placeholder="<?= trans('mobile_no') ?>">
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <h3 class="card-title"><i class="fa fa-plus"></i>
                  <?= trans('login_credentials') ?></h3>
                  <hr>
                </div>
                <div class="form-group">
                 <div class="row">
                  <div class="col-md-6">
                    <label for="username" class="col-md-12 control-label"><?= trans('username') ?></label>
                    <div class="col-md-12">
                      <input type="username" name="username" class="form-control" id="username" onkeypress="return /[A-Za-z0-9_ ]/i.test(event.key)" placeholder="" value="<?= (isset($form_data['username'])? $form_data['username']:"");?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label for="password" class="col-md-12 control-label"><?= trans('password') ?></label>
                    <div class="col-md-12">
                      <input type="password" name="password" class="form-control" id="password" readonly=""  value="<?= (isset($form_data['password'])? $form_data['password']:"");?>" placeholder="">
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <div class="form-group">
                <div class="col-md-12">
                  <input type="submit" name="submit" value="<?= trans('add_transporter') ?>" class="btn btn-primary pull-right">
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
    $("#contactperson").blur(function(){  

      var contact_person=$("#contactperson").val();


      if(contact_person !=""){
        var name=contact_person.replace(/\s+/g, "_").toLowerCase();
        // var username=name.replace(" ","_",);
        $("#username").val(name);
        $("#password").val(makeid(8));


      }
    });

    function makeid(length) {
      var result           = '';
      var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      var charactersLength = characters.length;
      for ( var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * 
         charactersLength));
      }
      return result;
    }  

    $('#plant').select2();

   
  });

</script>

<!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
-->
