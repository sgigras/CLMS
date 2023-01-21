 <!-- Author:Hriday Mourya
Subject: Config Variables Master view
Date:25-09-21 -->
<style>
 .mandatory{
  color: red;
}
#bold{
  font-weight: bold;
}
</style>

<!-- <div class="content-wrapper"> -->
  <link  href="<?= base_url()?>assets/plugins/select2/select2.css" rel="stylesheet">
  <script src="<?= base_url()?>assets/plugins/select2/select2.js" ></script>
  <!-- Main content -->
  <section class="content">
    <div class="card card-default color-palette-bo">
      <div class="card-header">
        <div class="d-inline-block">
          <h3 style="font-weight:bold; font-family: sans-serif;" class="card-title">
            <?= trans('config_master') ?></h3>
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
                 
                  <div class="form-group">
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <a href="<?= base_url('configvarible/Config_MasterAPI/add') ?>"  style="width:100%;" class="btn btn-success"><?= trans('add_new_variable') ?></a>
                            </div>
                          <div class="col-md-4"></div><br><br>
                          
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <a href="<?= base_url('configvarible/Config_MasterAPI/update') ?>"" style="width:100%;"  class="btn btn-warning mr-2"><?= trans('update_config_variables') ?></a>
                            </div>

                        <div class="col-md-4"></div>
                          </div>
                        </div>
                  
              
                  </div>
                  <!-- /.box-body -->
                </div>
              </div>
            </div>  
          </div>
        </div>
      </section> 
    </div>


