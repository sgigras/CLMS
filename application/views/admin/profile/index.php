<!-- Content Wrapper. Contains page content -->
  <!-- <div class="content-wrapper"> -->
    <!-- Main content -->
    <section class="content">
      <div class="card card-default color-palette-bo">
        <div class="card-header">
          <div class="d-inline-block">
              <h3 class="card-title"> <i class="fa fa-pencil"></i>
              &nbsp; <?= trans('profile') ?> </h3>
          </div>
          <div class="d-inline-block float-right">
            <a href="<?= base_url('admin/profile/change_pwd'); ?>" class="btn btn-secondary "><i class="fa fa-list"></i> <?= trans('change_password') ?></a>
          </div>
        </div>
        <div class="card-body">   
           <!-- For Messages -->
            <?php $this->load->view('admin/includes/_messages.php') ?>
            <?php echo form_open_multipart(base_url('admin/profile'), 'class="form-horizontal"' )?> 
              <div class="form-group">
                <label for="username" class="col-sm-2 control-label"><?= trans('irla_no') ?></label>
                <div class="col-md-12">
                  <input readonly="true" type="text" name="username" value="<?= $admin['username']; ?>" class="form-control" id="username" placeholder="">
                </div>
              </div>
              <div class="form-group">
                <label for="firstname" class="col-sm-2 control-label"><?= trans('firstname') ?></label>
                <div class="col-md-12">
                  <input type="text" name="firstname" value="<?= $admin['firstname']; ?>" class="form-control" id="firstname" placeholder="">
                </div>
              </div>
              <div class="form-group">
                <label for="email" class="col-sm-2 control-label"><?= trans('email') ?></label>
                <div class="col-md-12">
                  <input type="email" name="email" value="<?= $admin['email']; ?>" class="form-control" id="email" placeholder="">
                </div>
              </div>
              <div class="form-group">
                <label for="mobile_no" class="col-sm-2 control-label"><?= trans('mobile_no') ?></label>
                <div class="col-md-12">
                  <input type="number" name="mobile_no" value="<?= $admin['mobile_no']; ?>" class="form-control" id="mobile_no" placeholder="">
                </div>
              </div>
              <div class="form-group">
                <label for="profile_image" class="col-sm-2 control-label"><?= trans('profile_image') ?></label>
                <div class="col-12" style='padding-left:10px !important; border-right: 1px solid #34343434;'>
                            <?php $this->load->view('master/upload_image_block_field', array("name"=>"profilepics[]","field_id" => "profile_image", "css_class" => "photostyle","width" => "110", "height" => "300px", "image_title" => $admin['image'], "image_path" => $admin['image'])) ?>
                        </div>
              </div>
              
              <div class="form-group">
                <div class="col-md-12">
                  <input type="submit" name="submit" value="<?= trans('update_profile') ?>" class="btn btn-success pull-right">
                </div>
              </div>
            <?php echo form_close(); ?>
        </div>
        <!-- /.box-body -->
      </div>
    </section>
  </div> 