  <!-- Content Wrapper. Contains page content -->
  <!-- <div class="content-wrapper"> -->
    <!-- Main content -->
    <section class="content">
      <div class="card card-default color-palette-bo">
        <div class="card-header">
          <div class="d-inline-block">
              <h3 class="card-title"> <i class="fa fa-pencil"></i>
              <?= trans('supervisor_edit') ?> </h3>
          </div>
          <div class="d-inline-block float-right">
            <a href="<?= base_url('transporter/TransporterSupervisorAPI'); ?>" class="btn btn-success"><i class="fa fa-list"></i> <?= trans('trans_supervisor_list') ?></a>
          </div>
        </div>
        <div class="card-body">   
           <!-- For Messages -->
            <?php $this->load->view('admin/includes/_messages.php') ?>
              
            <?php echo form_open(base_url('transporter/TransporterSupervisorAPI/edit/'.$supervisor['admin_id']), 'class="form-horizontal"' )?> 
            <div class="form-group">
                    <label for="plant" class="col-md-12 control-label"><?= trans('select_plant') ?>*</label>

                    <div class="col-md-12">
                      <select name="plant" class="form-control">
                        <option value=""><?= trans('select_plant') ?></option>
                        <?php foreach($transporter_plants as $plant): ?>
                          <option value="<?= $plant['id']; ?>" <?= ($supervisor['plant_id'] ==$plant['id']? "selected":""); ?>><?= $plant['plant_name']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div> 
               
                  <div class="form-group">
                    <label for="firstname" class="col-md-12 control-label"><?= trans('firstname') ?></label>

                    <div class="col-md-12">
                      <input type="text" name="firstname" class="form-control" onkeypress="return /[A-Za-z ]/i.test(event.key)" maxlength="30" id="firstname" placeholder="<?= trans('firstname') ?>" value="<?= $supervisor['firstname']; ?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="lastname" class="col-md-12 control-label"><?= trans('lastname') ?></label>

                    <div class="col-md-12">
                      <input type="text" name="lastname" class="form-control" onkeypress="return /[A-Za-z ]/i.test(event.key)" maxlength="30" id="lastname" placeholder="<?= trans('lastname') ?>" value="<?= $supervisor['lastname']; ?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="username" class="col-md-12 control-label"><?= trans('username') ?></label>

                    <div class="col-md-12">
                      <input type="text" name="username" class="form-control" onkeypress="return /[A-Za-z ]/i.test(event.key)" maxlength="30" id="username" placeholder="<?= trans('username') ?>" value="<?= $supervisor['username']; ?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="email" class="col-md-12 control-label"><?= trans('email') ?></label>

                    <div class="col-md-12">
                      <input type="email" name="email" class="form-control" maxlength="60" id="email" placeholder="<?= trans('email') ?>" value="<?= $supervisor['email'] ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="mobile_no" class="col-md-12 control-label"><?= trans('mobile_no') ?></label>

                    <div class="col-md-12">
                      <input type="text" name="mobile_no" onkeypress="return /[0-9]/i.test(event.key)" maxlength="10" class="form-control" id="mobile_no" placeholder="<?= trans('mobile_no') ?>" value="<?= $supervisor['mobile_no']; ?>">
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
                      <input type="submit" name="submit" value="<?= trans('update_supervisor') ?>" class="btn btn-primary pull-right">
                    </div>
                  </div>
                <?php echo form_close(); ?>
              </div>
              <!-- /.box-body -->
            </div>
    </section>
  </div>

  <script type="text/javascript">
  $( document ).ready(function() {
      $("#lastname,#firstname").blur(function(){  

        var first_name=$("#firstname").val();
        var last_name=$("#lastname").val();

    if(first_name !="" && last_name !=""){
      var username= first_name.toLowerCase() +"_"+last_name.toLowerCase();
      $("#username").val(username);

    }
    });  
});

  </script>