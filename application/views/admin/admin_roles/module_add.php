  <!-- Content Wrapper. Contains page content -->
  <!-- <div class="content-wrapper"> -->
    <!-- Main content -->
    <section class="content">
      <div class="card card-default">
        <div class="card-header">
          <div class="d-inline-block">
              <h3 class="card-title"> <i class="fa fa-plus"></i>
              <?= $title ?> </h3>
          </div>
          <div class="d-inline-block float-right">
            <a href="<?= base_url('admin/admin_roles/module'); ?>" class="btn btn-secondary"><i class="fa fa-list"></i>  <?= trans('module_list') ?></a>
          </div>
        </div>
        <div class="card-body">
   
           <!-- For Messages -->
            <?php $this->load->view('admin/includes/_messages.php') ?>

            <?php echo form_open(base_url('admin/admin_roles/module_add'), 'class="form-horizontal"');  ?> 
              <div class="form-group">
                <label for="module_name" class="col-md-2 control-label"><?= trans('module_name') ?></label>

                <div class="col-md-12">
                  <input type="text" name="module_name" class="form-control" id="module_name" placeholder="" onkeypress="return checkValidInputKeyPress(alphabet_space_regex_pattern);" >
                  <small><?= trans('lang_index_message') ?></small>
                </div>
              </div>
              <div class="form-group">
                <label for="controller_name" class="col-md-2 control-label"><?= trans('controller_name') ?></label>

                <div class="col-md-12">
                  <input type="text" name="controller_name" class="form-control" id="controller_name" placeholder="" onkeypress="return checkValidInputKeyPress(slash_underscore_dash_regex_pattern);">
                </div>
              </div>
              
              <div class="form-group">
                <label for="fa_icon" class="col-md-2 control-label"><?= trans('fa_icon') ?></label>

                <div class="col-md-12">
                  <input type="text" name="fa_icon" class="form-control" id="fa_icon" placeholder="" onkeypress="return checkValidInputKeyPress(hyphen_space_regex_pattern);">
                </div>
              </div>

              <div class="form-group">
                <label for="operation" class="col-md-2 control-label"><?= trans('operations') ?></label>

                <div class="col-md-12">
                  <input type="text" name="operation" class="form-control" id="operation" placeholder="eg. add|edit|delete" onkeypress="return checkValidInputKeyPress(pipe_underscore_hyphen_regex_pattern);">
                </div>
              </div>
              <div class="form-group">
                <label for="sort_order" class="col-md-2 control-label"><?= trans('sort_order') ?></label>

                <div class="col-md-12">
                  <input type="number" name="sort_order" class="form-control" id="sort_order" placeholder="">
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-12">
                  <input type="submit" name="submit" value="<?= trans('add_module') ?>" class="btn btn-primary pull-right">
                </div>
              </div>
            <?php echo form_close( ); ?>
        </div>
          <!-- /.box-body -->
      </div>
    </section> 
  </div>