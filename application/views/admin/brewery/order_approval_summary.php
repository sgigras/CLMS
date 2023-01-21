<!-- Content Wrapper. Contains page content -->
<?php
$resultArray = (isset($resultArray)) ? $resultArray : new stdClass;
/*
    to display cart details in summarized form according to the page load or url called
    author:Jitendra Pal
    date:-28-10-2021
*/
?>
<!--<div class="content-wrapper" style="margin-top: 55px;min-height: 580.08px !important;">-->
<!-- Main content -->
<script>
    var page_hit = '<?= $page_hit ?>';
    var entity_id = '<?= $entity_id ?>';
    var cart_id = '<?= $check_out_cart_id ?>';
    var order_code = '<?= $order_code ?>';

    console.log("page_hit--" + page_hit + "-----" + "entity_id---" + entity_id + "-----" + "cart_id--" + cart_id + "-----" + "order_code---" + order_code + "-----");
</script>
<?php
?>

<!-- <script> -->
<script>
    var page_label = '<?php echo ORDER_DELIVERY_SUMMARY ?>';
</script>
<section class="content">
    <?php
    ?>
    <div class="card card-default" style="box-shadow: none;">
        <div class="card-header">
            <div class="d-inline-block">
                <!-- <h3 class="card-title"> <i class="<?= $fa_form_icon ?>"></i>
                    Liqour Order</h3> -->
            </div>
            <!-- <div class="d-inline-block float-right">
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div> -->  
        </div>
        <div class="card-body" style="padding:30px;">
            <!-- <div class="row">
                <div class="col-4">
                    <div class="input-group input-group-md">
                         <label> Order Code</label>
                        <br> -
                        <input type="text" style="display: none;" class="form-control" id="select_order" placeholder="Order Code ex:MXGDHFJD">
                        
                        <span class="input-group-append">
                            <button type="button" class="btn btn-info" style="display: none;" id="searchCartDetails" ><i class="fa fa-search fa-sm" style="color: white;"></i></button>
                        </span>
                        
                    </div>
                    <span class="error" id="error" name="error" style="color:red;"></span>
                  
                </div>
            </div> -->
            <!-- <br><br> -->

            <!-- <div class="row">

            <?php ?> -->
<div class="cart-summary animate__animated animate__fadeInRight">
    <div class="card-body  p-0">
        <table class="table table-condensed">
            <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Liquor Name</th>
                            <th style="width:150px;">Liquor Type</th>
                            <th style="width:150px;">Base Price</th>
                            <th style="width:150px;">Quantity</th>
                            <th style="width:150px;">Total</th>
                        </tr>

                        <tr>
                            <th>1</th>
                            <th>OldMonk the Legend Deluxe Premium Rum Very Old Vatted Full</th>
                            <th >Rum</th>
                            <th >100</th>
                            <th >35</th>
                            <th >3500</th>
                        </tr>
                               
                    <!-- <?php
                    $table_head_array = unserialize($cart_total_table_header);
                    foreach ($table_head_array as $column_header) {
                        $column_array = explode(":", $column_header);
                        echo "<th><h6>" . trans($column_array[0]) . "<h6></th>";
                    }
                    ?> -->
                <!-- </tr> -->
            </thead>
            <tbody>
                <?php
                $total_price = 0.0;
                $total_unit = 0;
               /* foreach ($cart_data_array as $record) {

                    $total_price = round(floatval($total_price), 2) + round(floatval($record["total_quantity_cost"]), 2);

                    // if (sizeof($table_head_array) == 5) {
                    //     $total_price = round(floatval($total_price), 2) + round(floatval($record["total_quantity_cost"]), 2);
                    // } else {
                    //     $total_price = round(floatval($total_price), 2) + round(floatval($record["total_cost"]), 2);
                    //     $total_unit = round(floatval($total_unit), 2) + round(floatval($record["total_quantity_cost"]), 2);
                    // }


                    echo "<tr>";
                    foreach ($table_head_array as $column_header) {
                        $data_key = explode(":", $column_header);

                        if (strpos($data_key[0], '_buttons') !== false) {
                            $entity_product_id = explode('_', $record[$data_key[1]]);
                            $quantity = $entity_product_id[0];
                            $product_id = $entity_product_id[1];
                            $cart_id = $entity_product_id[2];
                            $field_id = $product_id . "_" . $cart_id;
                            echo "<td id='$field_id" . "_" . "$data_key[0]' class='cart_total_table_quantity'>" . $quantity . "</td>";
                        } else {

                            echo "<td>" . $record[$data_key[1]] . "</td>";
                        }
                    }

                    echo "</tr>";
                } */
                    // echo "<tr><td>Total</td><td colspan='3'></td><td>$total_price</td></tr>";
                  echo "<tr><td>Total</td><td colspan='2'></td><td></td><td>$total_price</td></tr>";

                // if (sizeof($table_head_array) == 5) {
                //     echo "<tr><td>Total</td><td colspan='3'></td><td>$total_price</td></tr>";
                // } else {
                //     echo "<tr><td>Total</td><td colspan='4'></td><td>$total_unit</td><td>$total_price</td></tr>";
                // }
                ?>
            </tbody>
                </table>
              
               
    </div>
        <div class="modal-footer">
          <label class="col-sm-2 control-label">Chairman Remark</label>
          <div class="col-sm-10">
            <input type="email" class="form-control" id="inputEmail3" placeholder="remark">
          </div>
        </div>
                 <div class="modal-footer">
                    <button type="button" class="btn btn-info" onclick="submit_order_delivery_check()">Approve</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Reject</button>
                </div>
</div>



                <div class="col-12" id="hold_cart_collapsible_view">

                </div>
            </div>
        </div>
    </div>
    </div>
    <div id="myModal" class="modal fade" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg" style="max-width: 60%;">

            <!-- Modal content-->
            <div class="modal-content">
                <!-- <div class="modal-header">
                        <h4 class="modal-title" id="modalHeader"></h4>
                    </div> -->
                <div class="modal-body p-0" id="confirmDeliveryModal">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" onclick="submit_order_delivery_check()">Confirm</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
<script src="<?= base_url() ?>assets/js/module/brewery/brewery_order_summary.js"></script>
<script>
    checkPageHit();
</script>