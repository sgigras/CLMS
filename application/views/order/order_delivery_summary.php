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
                <h3 class="card-title"> <i class="<?= $fa_form_icon ?>"></i>
                    Liqour Order</h3>
            </div>
            <div class="d-inline-block float-right">
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
        </div>
        <div class="card-body" style="padding:30px;">
            <div class="row">
                <div class="col-4">
                    <div class="input-group input-group-md">
                        <!-- <label> Order Code</label>
                        <br> -->
                        <input type="text" class="form-control" id="select_order" placeholder="Order Code ex:MXGDHFJD">
                        
                        <span class="input-group-append">
                            <button type="button" class="btn btn-info" id="searchCartDetails" ><i class="fa fa-search fa-sm" style="color: white;"></i></button>
                        </span>
                        
                    </div>
                    <span class="error" id="error" name="error" style="color:red;"></span>
                    <?php
                    // $this->load->view('master/select_field', array("field_id" => "select_order", "label" => "type", "place_holder" => "Search Order code", "option_record" => $order_code_data, "option_value" => "cart_id", "option_text" => "order_code", "selected_value" => getValue('order_code', $resultArray)));
                    ?>
                </div>
                <div class="col-8">
                    <!-- <button type="button" class="btn btn-info" style="margin-top: 34px !important;margin-left: -15px !important;" id="searchCartDetails"><i class="fa fa-search fa-sm" style="color: white;"></i></button> -->
                </div>
            </div>
            <br><br>

            <div class="row">
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
<script src="<?= base_url() ?>assets/js/module/order/order_delivery_summary.js"></script>
<script>
    checkPageHit();
</script>