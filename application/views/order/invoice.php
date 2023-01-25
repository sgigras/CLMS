<?php date_default_timezone_set('Asia/Kolkata');  ?>
<?php
extract($brewerysummary[0]);
?>
<style>
  table {
      margin: 0 auto;
      font-size: large;
      border: 1px solid rgb(230, 9, 9);
  }

  h1 {
      text-align: center;
      color: #000000;
      font-size: xx-large;
      font-family: 'Gill Sans', 'Gill Sans MT',
          ' Calibri', 'Trebuchet MS', 'sans-serif';
  }

  td {
      background-color: #f9f9f9;
      border: 1px solid black;
  }

  th,
  td {
      font-weight: bold;
      border: 1px solid black;
      padding: 10px;
      text-align: center;
  }

  td {
      font-weight: lighter;
  }

  h3 {
      text-align: right;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12">
        <h1 class="m-0 text-dark animate__animated animate__backInDown">Invoice</h1>
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
              <div class="col-12 text-center">
                <div id="printableArea">
                <div style="margin-top: 50px;text-align:center">
                <h5>DTE General</h5>
                <h5>Block 2 CGO Complex</h5>
            </div>
            <div class="row">
              <div  class="col-6 text-left">
                  <h5>No:-<?php echo $brewerysummary[0]["brewery_order_code"];?></h5>
              </div>

              <div class="col-6 text-right">
                  <h5>Date:-<?php echo date("Y-m-d H:i:s") ;?></h5>
              </div>
          </div>
          <div class="row">
            <div  class="col-12 text-left">
                To,<br>Mr.Pernod Recard India(P)
            </div>
          </div>
          <br/><br/>
          <div class="row">
            <div  class="col-12 text-left">
                <b>Subject:</b>Regarding Supply Order
            </div>
          </div>
          <div class="row">
            <div  class="col-12 text-left">
                Approval of the competent authority has been obtained for purchase of followingquantity/brand of IMFL/FL/Beer
                products from your firm
            </div>
          </div>
            <br>
            <div class="card-body" style="padding:30px;">
            <div class="cart-summary animate__animated animate__fadeInRight p-0 ">
                <div class="card-body  p-0">
                    <div class="card card-info card-outline " style="margin-bottom:0px !important">
            <div class="card-header" style="background-color: #007bff;">
                <div style="display:flex;font-size: 1px !important;">
                    <h3 class="card-title">Name &nbsp;</h3>
                    <h4 class="card-title"><?= '' . ' : ' . $brewery_name ?></h4>
                    &nbsp;
                    <h3 class="card-title" style="border-left: solid 1px; padding-left:15px;">Code &nbsp;</h3>
                    <h4 class="card-title"><?= '' . ' : ' . $brewery_order_code ?></h4>
                    &nbsp;
                    <h3 class="card-title" style="border-left: solid 1px; padding-left:15px;">By &nbsp;</h3>
                    <h4 class="card-title"><?= '' . ' : ' . $requested_by ?></h4>
                    &nbsp;
                    <h3 class="card-title" style="border-left: solid 1px; padding-left:15px;">Status&nbsp;</h3>
                    <h4 class="card-title"><?= '' . ' : ' . $approval_status ?></h4>
                </div>
            </div>
            <div class="card-body p-0 mb-0">
                  <table class="table table-condensed">
                      <thead>
                          <tr>
                              <th style="width:150px;">Sr.No</th>
                              <th style="width:150px;">Brand</th>
                              <th style="width:150px;">Liquor</th>
                              <th style="width:150px;">Liquor Type</th>
                              <th style="width:150px;">Demand</th>
                              <th style="width:150px;">Amount</th>
                              <th style="width:150px;">Tax</th>
                              <th style="width:150px;">Total Price Per Unit</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php
                          $row_count = 1;
                          foreach ($brewerysummary as $row) {
                          ?>
                              <tr>
                                  <td><?php echo $row_count; ?>.</td>
                                  <td><?php echo $row["brand"]; ?></td>
                                  <td><?php echo $row["liquor_description"]; ?></td>
                                  <td><?php echo $row["liquor_type"]; ?></td>
                                  <td><?php echo $row["total_quantity"]; ?></td>
                                  <td><?php echo $row["liquor_base_price"]; ?></td>
                                  <td><?php echo '' ?></td>
                                  <td><?php echo $row["total_purchase_price"]; ?></td>
                              </tr>
                          <?php 
                          $row_count++;
                          } ?>
                      </tbody>
                  </table>
              </div>
              </div>
              </div>
            </div>
          </div>
            <br>
            <div style="margin-left: 40px;text-align: left;">
                <ol>
                    <li>1. Please collect above four numbers of impory permits from the commissioner of prohibition and Excise Department
                    for early supply as per the terms and condition of liquor brand as per the permits</li>
                    <li>2. PRINTED IN RED COLOUR ON THE LABEL" FOR SALE SERVICE AND RETIRED CAPF PERSONALL ONLY"</li>
                    <li>3. Invoice should be in favor of <b>'HOO/Comdt,'Block 2, CGO Complex'</b></li>
                    <li>4. The bill/invoice in four copies after payment action duly signed Re1/- Revenue stamp may also be forwarded to
                    this office . The payments are to be made to your firm directly into the bank account through RTGS/ECS</li>
                    <li>5. F.O.R at <b>'HOO/Comdt,'Block 2, CGO Complex'</b></li>
                    <li>6. Above goods are supplies within 30 Days</li>
                    <li>7. Supplier will responsible for any Breakage leakage</li>
            </ol>
            </div>

            <div style="margin-left: 800px;">
                <b>Officer-In-charge<br>Dte Gen</b><br>Indo tibetian border police
            </div>
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