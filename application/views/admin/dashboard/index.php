  <?php
  extract($all_users[0]);
  ?>
  <!-- Content Wrapper. Contains page content -->
  <!-- <div class="content-wrapper"> -->
  <!-- Content Header (Page header) -->
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1 class="m-0 text-dark"><?= trans('dashboard') ?> v1</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="#"><?= trans('home') ?></a></li>
                      <li class="breadcrumb-item active"><?= trans('dashboard') ?> v1</li>
                  </ol>
              </div><!-- /.col -->
          </div><!-- /.row -->
      </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <script>
var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
var baseurl = '<?= base_url() ?>';
  </script>
  <!-- Main content -->
  
  <section class="content">
      <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
              <div class="col-lg-3 col-5">
                
                  <!-- small box -->
                  <div class="small-box bg-warning">
                      <div class="inner">
                          <h3><?= $hrms_user_count; ?></h3>
                          <p>Total HRMS Data</p>
                      </div>
                      <div class="icon">
                          <i class="ion ion-person-add"></i>
                      </div>
                      <!-- <a href="#" class="small-box-footer"><?= trans('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a> -->
                  </div>
              </div>
              <!-- ./col -->
             
              <!-- ./col -->
              <div class="col-lg-3 col-5">
                  <!-- small box -->
                  <div class="small-box bg-info">
                      <div class="inner">
                          <h3><?= $active_user_count; ?></h3>
                          <p><?= trans('active_users') ?></p>
                      </div>
                      <div class="icon">
                          <i class="ion ion-bag"></i>
                      </div>
                      <!-- <a href="#" class="small-box-footer"><?= trans('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a> -->
                  </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-5">
                  <!-- small box -->
                  <div class="small-box bg-danger">
                      <div class="inner">
                          <h3><?= $inactive_user_count; ?></h3>
                          <p>User Not Registered</p>
                      </div>
                      <div class="icon">
                          <i class="ion ion-pie-graph"></i>
                      </div>
                      <!-- <a href="#" class="small-box-footer"><?= trans('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a> -->
                  </div>
              </div>
              <div class="col-lg-3 col-5">
                  <!-- small box -->
                  <div class="small-box bg-success">
                      <div class="inner">
                    <h3>  <?php echo $this->dashboard_model->getConcurrentActiveUsers(); ?></h3> 
                     <p >Logged In Users:-  </p>
                     </div>
                    <div class="icon"> <i class="ion ion-pie-graph"></i>
             </div>
        </div>
              <!-- ./col -->
              <!-- <div class="col-lg-3 col-6">
            small box
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>65</h3>
                <p><?= trans('unique_visitors') ?></p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer"><?= trans('more_info') ?> <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
              <!-- ./col -->
          </div>
          <!-- /.row -->
          <!-- Main row -->
          <div class="card">
              <div class="card-header">
                  <div class="d-inline-block">
                      <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; User Details</h3>
                  </div>
                  <!-- <div class="d-inline-block float-right">
                <i onclick="open_table()" id="icon_btn" class="fa fa-plus"></i>
                </div> -->
              <div id="" class="card-body">
                  <div class="form-group">
                      <label> Irla No./Regiment No.</label>&nbsp;&nbsp;
                      <select class="form-control" placeholder="" id="irla_no" name="irla_no" style="width: 30%;">
                      </select>
                      <span id="span_irla_no" style="color:red"></span>
                  </div>
                  <div id="table_div">
                  </div>
               </div>
          </div> 
</div>
</div>
   
          <div class="card">
              <div class="card-header">
                  <div class="d-inline-block">
                      <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; User Details By Posting Unit</h3>
                  </div>
                  <!-- <div class="d-inline-block float-right">
                <i onclick="open_table()" id="icon_btn" class="fa fa-plus"></i>
                </div> -->
              </div>
              <div id="" class="card-body">
              <div class="form-group">
                  <label> Posting Unit </label>&nbsp;&nbsp;
                  <select class="form-control" placeholder="" id="posting_unit" name="posting_unit" style="width: 30%;">
                  </select>
                  <span id="span_posting_unit" style="color:red"></span>
              </div>
              <div id="posting_unit_div">
              </div>
          </div>
          </div>
          <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Morris.js charts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
  <script src="<?= base_url() ?>assets/plugins/morris/morris.min.js"></script>
  <!-- Sparkline -->
  <script src="<?= base_url() ?>assets/plugins/sparkline/jquery.sparkline.min.js"></script>
  <!-- jvectormap -->
  <script src="<?= base_url() ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
  <script src="<?= base_url() ?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="<?= base_url() ?>assets/plugins/knob/jquery.knob.js"></script>
  <!-- daterangepicker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
  <script src="<?= base_url() ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- datepicker -->
  <script src="<?= base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
  <!-- Bootstrap WYSIHTML5 -->
  <script src="<?= base_url() ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="<?= base_url() ?>assets/dist/js/pages/dashboard.js"></script>