<!-- Content Wrapper. Contains page content -->
<?php
extract($brewerysummary[0]);
?>
<script>
var order_id = '<?= $brewery_order_id ?>';
var order_code = '<?= $brewery_order_code ?>';
</script>
<section class="content">
    <div class="card card-default" style="box-shadow: none;">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="<?= (isset($fa_form_icon)?$fa_form_icon:"") ?>"></i>
                    <?= (isset($title)?trans($title):"") ?></h3>
            </div>
        </div>
        <div class="card-body" style="padding:30px;">
            <div class="cart-summary animate__animated animate__fadeInRight p-0 ">
                <div class="card-body  p-0">
                    <div class="card card-info card-outline " style="margin-bottom:0px !important">
                        <div class="card-header" style="background-color: #007bff;">
                            <div style="display:flex;font-size: 4px !important;">
                                <h3 class="card-title">Brewery Name &nbsp;</h3>
                                <h4 class="card-title"><?= '' . ' : ' . $brewery_name ?></h4>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <h3 class="card-title" style="border-left: solid 1px; padding-left:15px;">Order Code &nbsp;</h3>
                                <h4 class="card-title"><?= '' . ' : ' . $brewery_order_code ?></h4>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <h3 class="card-title" style="border-left: solid 1px; padding-left:15px;">Requested By &nbsp;</h3>
                                <h4 class="card-title"><?= '' . ' : ' . $requested_by ?></h4>
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
                        <div class="card-footer">
                            <div class="row">
                                <label class="col-md-2 control-label">Chairman Remark</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="chairman_remark" maxlength="150" placeholder="remark">
                                </div>
                            </div>
                            <br>
                            <div class="d-inline-block float-right">
                                <button type="button" class=" btn btn-primary float-left" name="approve" id="approve" onclick="approve_reject_order('A')"><i class="<?= (isset($fa_button_icon1)?$fa_button_icon1:"") ?>"></i>Approve</button>
                                &nbsp;&nbsp;
                                <button type="button" class="btn btn-secondary float-right" name="reject" id="reject" onclick="approve_reject_order('R')"><i class="<?= (isset($fa_button_icon2)?$fa_button_icon2:"") ?>"></i>Reject</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</section>
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = "<?php echo base_url(); ?>";
</script>
<script src="<?= base_url() ?>assets/js/module/brewery/brewery_order_approval.js"></script>