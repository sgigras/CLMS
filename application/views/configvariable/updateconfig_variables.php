 <!-- Author:Hriday Mourya
Subject:Update Config Variables view
Date:25-09-21 --> 
  <?php  $form_data=$this->session->flashdata('form_data');   ?>
  <!-- Content Wrapper. Contains page content -->
  <style type="text/css">
  .mandatory{
    color: red;
  }
</style>
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/lightbox/gallery.css"> 
<!-- <div class="content-wrapper"> -->
  <link  href="<?= base_url()?>assets/plugins/select2/select2.css" rel="stylesheet">
  <script src="<?= base_url()?>assets/plugins/select2/select2.js" ></script>
  <!-- Main content -->
  <section class="content">
    <div class="card card-default color-palette-bo">
      <div class="card-header">
        <div class="d-inline-block">
          <h3 class="card-title"> <i class="fa fa-plus"></i>
            <?= trans('update_variable') ?></h3>
          </div>

        </div>

        <div class="card-body" id="variable">
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <!-- form start -->
                <div class="box-body">

                  <!-- For Messages -->
                  <?php $this->load->view('admin/includes/_messages.php') ?>

                  <?php echo form_open(base_url('configvarible/Config_MasterAPI/update'), 'class="form-horizontal"');  ?> 
                  <div class="card-body" >
                    <div class="form-group">
                      <div class="row">
                        <div  class="col-sm-3"></div>
                        <div  class="col-sm-6">
                          <label><?= trans('select_variable') ?><sup class="mandatory">*</sup></label>
                          <select  class="form-control" id="select_variable" name="select_variable" >
                           <option value="0" disabled selected><?= trans('select_variable') ?></option>
                           <?php foreach($variables as $var): ?>
                            <option value="<?= $var['id']; ?>"><?= $var['variable']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div  class="col-sm-3"></div>
                    </div>
                  </div>

                  <div  id="config_var" style="display: none;" >
                    <div class="form-group">
                     <div class="row">
                      <div  class="col-sm-3"></div>
                      <div  class="col-sm-6">
                        <label for="name" class="col-md-12 control-label"><?= trans('variable_name') ?><sup class="mandatory">*</sup></label>
                        <div class="col-md-12">
                         <input type="text" name="variable_name" onkeypress="return /[A-Za-z ]/i.test(event.key)" maxlength="100" class="form-control" id="variable_name" value="<?= (isset($form_data['variable_name'])? $form_data['variable_name']:"");?>" placeholder="<?= trans('e_variable_name') ?>">
                       </div>
                     </div>
                     <div  class="col-sm-3"></div>
                   </div>
                 </div>


                 <div class="form-group">
                   <div class="row">
                    <div  class="col-sm-3"></div>
                    <div  class="col-sm-6">
                      <label for="value" class="col-md-12 control-label"><?= trans('value') ?><sup class="mandatory">*</sup></label>
                      <div class="col-md-12">
                       <input type="text" name="variable_value" onkeypress="return /[0-9.]/i.test(event.key)" maxlength="3" class="form-control" id="variable_value" value="<?= (isset($form_data['variable_value'])? $form_data['variable_value']:"");?>" placeholder="<?= trans('e_value') ?>">
                     </div>
                   </div>
                   <div  class="col-sm-3"></div>
                 </div>
               </div>

               <div class="form-group">
                 <div class="row">
                  <div  class="col-sm-3"></div>
                  <div  class="col-sm-6">
                    <label for="value" class="col-md-12 control-label"><?= trans('description') ?><sup class="mandatory">*</sup></label>
                    <div class="col-md-12">
                     <textarea name="description" onkeypress="return /[A-Za-z0-9. ]/i.test(event.key)" maxlength="250" class="form-control" id="description" value="<?= (isset($form_data['description'])? $form_data['description']:"");?>" placeholder="<?= trans('e_description') ?>"></textarea>
                   </div>
                 </div>
                 <div  class="col-sm-3"></div>
               </div>
             </div><br>
               <div class="form-group">
                 <div class="row">
                  <div  class="col-sm-3"></div>
                  <div  class="col-sm-6">

                    <label class="container"><?= trans('active') ?>
                    <input type="checkbox" name="active" id="IS_ACTIVE" value="1" >
                    <span class="checkmark"></span>
                  </label>


                </div>
                <div  class="col-sm-3"></div>
              </div>
            </div>

            <div class="form-group">
             <div class="row">
              <div  class="col-sm-3"></div>
              <div class="col-sm-6">
               <p><strong>NOTE&nbsp;-</strong>&nbsp;&nbsp;If the Active checkbox is left Unckecked the Variable will be Inactive.</p>



             </div>
             <div  class="col-sm-3"></div>
           </div>
         </div>

         <div class="form-group">

           <div class="row">
            <div  class="col-sm-4"></div>
            <div class="col-sm-4">

              <div class="card-footer mt-4 card-footer bg-transparent p-0 d-flex justify-content-end">
               <center>
                <a href="<?= base_url('configvarible/Config_MasterAPI/') ?>" class="btn btn-secondary  active" role="button" aria-pressed="true"><i class="fas fa-backspace mr-2"></i><?= trans('back') ?></a>

                <button class="btn btn-warning mr-2"><i class="fas fa-eraser mr-2"></i><?= trans('reset') ?></button>
                <button type="submit" name="submit" value="<?= trans('save') ?>" class="btn btn-success"> <i class="fas fa-save mr-2"></i><?= trans('update') ?></button>
              </center>
            </div>

          </div>
          <div  class="col-sm-4"></div>
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

<script type="text/javascript">

  var obj;
  $(document).ready(function(){

    $("#select_variable").select2({

    });



    $("#select_variable").change(function ()
    {
      var selected_value = $('#select_variable').val();

      if (selected_value != '') {
        $.ajax({
          url:"<?= base_url('configvarible/Config_MasterAPI/getVariableDetails') ?>",
          type: 'POST',
          datatype: "json",
          data: {selected_value: selected_value},
          async: false,
          success: function (response)
          {
            obj = JSON.parse(response);

            document.getElementById("variable_name").value = obj[0].variable;
            document.getElementById("variable_value").value = obj[0].value;
            document.getElementById("description").value = obj[0].description;
            if (obj[0].status == 1) {
              $("#IS_ACTIVE").prop("checked", true);
            } else {
              $("#IS_ACTIVE").prop("checked", false);
            }

            $('#config_var').show();

          }
        });
      }
    }
    );


  });

</script>
