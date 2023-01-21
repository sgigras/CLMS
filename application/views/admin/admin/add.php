  <?php $form_data=$this->session->flashdata('form_data');?>
  
  <!-- Content Wrapper. Contains page content -->
  <!-- <div class="content-wrapper"> -->
    <!-- Main content -->
    <section class="content">
      <div class="card card-default color-palette-bo">
        <div class="card-header">
          <div class="d-inline-block">
              <h3 class="card-title"> <i class="fa fa-plus"></i>
              <?= trans('add_new_admin') ?> </h3>
          </div>
          <div class="d-inline-block float-right">
            <a href="<?= base_url('admin/admin'); ?>" class="btn btn-secondary"><i class="fa fa-list"></i> <?= trans('admin_list') ?></a>
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

                  <?php echo form_open(base_url('admin/admin/add'), 'class="form-horizontal"');  ?> 
                  <div class="form-group">
                    <label for="role" class="col-md-12 control-label"><?= trans('select_admin_role') ?>*</label>

                    <div class="col-md-12">
                      <select name="role" class="form-control">
                        <option value=""><?= trans('select_role') ?></option>
                        <?php foreach($admin_roles as $role): ?>
                          <option value="<?= $role['ID']; ?>" <?= ((isset($form_data['role']) AND $role['ID']==$form_data['role'])? "selected":""); ?>><?= $role['admin_role_title']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
               
                  <div class="form-group">
                    <label for="firstname" class="col-md-12 control-label"><?= trans('firstname') ?>*</label>

                    <div class="col-md-12">
                      <input type="text" name="firstname" onkeypress="return /[A-Za-z ]/i.test(event.key)" maxlength="30" class="form-control" id="firstname" placeholder="" value="<?= (isset($form_data['firstname'])? $form_data['firstname']:""); ?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="lastname" class="col-md-12 control-label"><?= trans('lastname') ?>*</label>

                    <div class="col-md-12">
                      <input type="text" name="lastname" onkeypress="return /[A-Za-z ]/i.test(event.key)" maxlength="30" class="form-control" id="lastname" placeholder="" value="<?= (isset($form_data['lastname'])? $form_data['lastname']:""); ?>">
                    </div>
                  </div>

                     <div class="form-group">
                    <label for="username" class="col-md-12 control-label"><?= trans('username') ?>*</label>

                    <div class="col-md-12">
                      <input type="text" name="username" onkeypress="return /[A-Za-z@# ]/i.test(event.key)" maxlength="30" class="form-control" id="username" placeholder="" value="<?= (isset($form_data['username'])? $form_data['username']:""); ?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="email" class="col-md-12 control-label"><?= trans('email') ?>*</label>

                    <div class="col-md-12">
                      <input type="email" name="email" class="form-control" maxlength="50" id="email" placeholder="" value="<?= (isset($form_data['email'])? $form_data['email']:""); ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="mobile_no" class="col-md-12 control-label"><?= trans('mobile_no') ?></label>

                    <div class="col-md-12">
                      <input type="text" name="mobile_no" onkeypress="return /[0-9]/i.test(event.key)" maxlength="10" class="form-control" id="mobile_no" placeholder="" value="<?= (isset($form_data['mobile_no'])? $form_data['mobile_no']:""); ?>">
                    </div>
                  </div>
                <!--   <div class="form-group">
                    <label for="password" class="col-md-12 control-label"><?= trans('password') ?></label>

                    <div class="col-md-12">
                      <input type="password" name="password" class="form-control" id="password" placeholder="">
                    </div>
                  </div> -->


                  <div class="form-group">
                    <div class="col-md-12">
                      <input type="submit" name="submit" value="<?= trans('add_admin') ?>" class="btn btn-primary pull-right">
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

<!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 -->
  <script type="text/javascript">
  $( document ).ready(function() {
      $("#lastname").blur(function(){  

        var first_name=$("#firstname").val();
        var last_name=$("#lastname").val();

    if(first_name !="" && last_name !=""){
      var username= first_name.toLowerCase() +"_"+last_name.toLowerCase();
      $("#username").val(username);

    }
    });  
});

  </script>