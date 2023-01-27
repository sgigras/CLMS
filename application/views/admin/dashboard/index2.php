<?php $liquor_consumed = $user_details['liquor_consumed'][0]['liquor_consumed'];
$liquor_details = $user_details['liquor_details'];

$consumedLiquor = "100";
if (isset($user_data) && $user_data != "" && $liquor_consumed != "")
  $consumedLiquor = ($liquor_consumed / $this->dashboard_model->get_userquota()) * 100;
?>
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/animation/animate.min.css">
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark animate__animated animate__backInDown">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#"><?= trans('home') ?></a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
      <div class="col-md-6 card animate__animated animate__zoomIn animate__delay-1s">
        <p class="text-center">
        <h5>Quota</h5>
        </p>
        <div>
          <p>
            Alloted Quota: <?php echo $this->dashboard_model->get_userquota(); ?>
          </p>
        </div>
        <div class="progress-group">
          Used Quota
          <span class="float-right"><b><?= $liquor_consumed ?></b>/<?php echo $this->dashboard_model->get_userquota(); ?></span>
          <div class="progress progress-xl">
            <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="width:<?php echo $consumedLiquor?>%"></div>
          </div>
        </div>
        <!-- /.progress-group -->
        <br>
      </div>
      <div class="col-md-6">
        <!-- Widget: user widget style 1 -->
        <div class="card card-widget widget-user animate__animated animate__zoomIn animate__delay-2s">
          <!-- Add the bg color to the header using any of the bg-* classes -->
          <div class="widget-user-header bg-info-active">
            <h3 class="widget-user-username"><?= $this->session->userdata('full_name') ?></h3>
            <h5 class="widget-user-desc"> Rank:<?= $this->session->userdata('rank') ?></h5>
          </div>
          <div class="widget-user-image">
            <img class="img-circle elevation-2" src="<?php echo ($this->session->has_userdata('profile_picture') && $this->session->userdata('profile_picture') !== NULL && $this->session->userdata('profile_picture') !== '') ? base_url() . $this->session->userdata('profile_picture') : base_url() . 'assets/dist/img/users.png' ?>" alt="User Avatar">
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-sm-6 border-right">
                <div class="description-block">
                  <h5 class="description-header">Contact</h5>
                  <span class="description-text"><?= $this->session->userdata('mobile_no') ?></span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-6 border-right">
                <div class="description-block">
                  <h5 class="description-header">IRLA No</h5>
                  <span class="description-text"><?= $this->session->userdata('username') ?></span>
                </div>
                <!-- /.description-block -->
              </div>
            </div>
            <!-- /.row -->
          </div>
        </div>
        <!-- /.widget-user -->
      </div>
    </div>
    <!-- TABLE: LATEST ORDERS -->
    <div class="row">
      <div class="col-12 p-0">
        <?php if (count($liquor_details) > 0) {
          $cart_type = $liquor_details[0]['cart_type'];
          $order_code =  $liquor_details[0]['order_code'];
          $canteen_details = $liquor_details[0]['canteen_details'];
          $cart['cart_header'] = ($cart_type == 'consumer') ? CONSUMER_CART_TABLE : ENTITY_CART_TABLE;
          $cart['cart_total_table_header'] = ($cart_type == 'consumer') ? CONSUMER_CART_TOTAL_TABLE : ENTITY_CART_TOTAL_TABLE;
          $cart['cart_data_array'] = $liquor_details;
        ?>
          <div class="card card-info card-outline" style="background-color: #007bff;">
            <div class="card-header">
              <div style="display:flex">
                <h3 class="card-title">Canteen Details &nbsp;</h3>
                <h4 class="card-title"><?= '' . ' : ' . $canteen_details ?></h4>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <h3 class="card-title" style="border-left: solid 1px; padding-left:15px;">Order Code &nbsp;</h3>
                <h4 class="card-title"><?= '' . ' : ' . $order_code ?></h4>
              </div>
              <div class="card-tools">
              </div>
            </div>
            <div class="card-body p-0">
              <div class="cart-table-area p-0">
                <div class="container-fluid p-0">
                  <div class="row">
                    <div class="col-12">
                      <?php $this->load->view('cart/cart_total_table', $cart) ?>;
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <!--/. container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- PAGE PLUGINS -->
<!-- SparkLine -->
<script src="<?= base_url() ?>assets/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jVectorMap -->
<script src="<?= base_url() ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?= base_url() ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS 1.0.2 -->
<script src="<?= base_url() ?>assets/plugins/chartjs-old/Chart.min.js"></script>
<!-- PAGE SCRIPTS -->
<script src="<?= base_url() ?>assets/dist/js/pages/dashboard2.js"></script>