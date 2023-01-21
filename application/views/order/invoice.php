<?php date_default_timezone_set('Asia/Kolkata');  ?>

<style>
  @page {
    size: auto;
    margin: 0mm;
  }

  #tblPrint table,
  td {
    border-bottom: 1px dashed black;
    padding: 5px;
  }

  #tblPrint table {
    border-spacing: 5px;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark animate__animated animate__backInDown">Invoice</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#"><?= trans('home') ?></a></li>
          <li class="breadcrumb-item active">Invoice</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">

    <!-- Info boxes -->
    <div class="row">
      <!-- put the content -->
      <div class="col-8 text-center">
        <div id="printableArea">
          <center>
            <table id="tblPrint" align="left" style="max-width: 400px;margin-left:15px">
              <tr>
                <th colspan="4">
                  <center><?= $canteen_name ?></center>
                </th>
              </tr>
              <tr>
                <th colspan="4">
                </th>
              </tr>
              <tr>
                <th colspan="4" style="border-bottom-style: solid; border-bottom: thin dashed #000;">
                  <center>
                    <?= $canteen_address ?>
                    <br>&nbsp;
                  </center>
                </th>
              </tr>
              <tr>
                <td colspan="4"">
                          <center>Bill</center>
                            </td>
                          </tr>
                          <tr>
                            <td>Bill No :<?= $order_code ?></td>
                            <td colspan=" 3" align="right"><?php echo date("d/m/Y h:i a"); ?></td>
              </tr>
              <tr>
                <td colspan="1">Bill to : <?= $irla ?></td>
                <td colspan="3">Name: <?= $name ?></td>

              </tr>

              <?php $this->load->view('master/table_tr_td', array("table_header" => BILL_TABLE_HEAD, "table_data_array" => $liquor_details)) ?>
              <tr>

                <td colspan="3">Total </td>
                <td colspan="1"> <?= array_sum(array_column($liquor_details, 'total_quantity_cost')); ?></td>
              </tr>
            </table>
          </center>
        </div>
        <a id="print_receipt" target="_blank" type="button" onclick="printDiv('printableArea')" value="print a div!"></a>
      </div>
    </div>
    <!-- /.row -->
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
<script>
  $(document).ready(function() {
    $("#print_receipt").trigger('click');
  });



  function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();
    document.body.innerHTML = originalContents;
    // window.location.href = DOMAIN + "order/OrderDetails/index"

  }
</script>