<!-- Content Wrapper. Contains page content -->
<?php
/*
    to display cart details in summarized form according to the page load or url called
    author:Jitendra Pal
    date:-28-10-2021
*/
?>
<!--<div class="content-wrapper" style="margin-top: 55px;min-height: 580.08px !important;">-->
<!-- Main content -->

<?php

// $cart['cart_header'] = '';
// $cart['cart_total_table_header'] = serialize(array());
// $cart['cart_data_array'] = array();
// if ($mode != 'order_delivery_summary') {
// $redirect_url = 'cart/CartDetails/addCartDetails';
// $id = $cart_id . "_cart_details_form";

?>
<!-- <input type="hidden" value="<?php //$liquor_count 
                                    ?>" id="liquor_count">
<input type="hidden" value="<?php //$cart_id 
                            ?>" id="cart_id" name="cart_id"> -->

<?php
// } else {
//     $order_code = '';
// }
?>
<!-- <script> -->

<section class="content">
    <?php
    // echo form_open(base_url('cart/CartDetails/placeOrder'), array('class' => 'form-horizontal', 'name' => 'cart_details', 'id' => 'cart_details'), 'class="form-horizontal"');
    ?>
    <div class="card card-default" style="box-shadow: none;">
        <div class="card-header" style="margin-bottom:10px;">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="<?= $fa_form_icon; ?>"></i>
                    <?= trans($title); ?>
                </h3>
            </div>
            <!-- <div class="d-inline-block float-right">
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div> -->
        </div>


        <?php

        if (count($order_history) == 0) {
            echo '<h3 class="text-danger" style="padding-left:20px;">You have not Ordered anything Yet!!</h3>';
        }




        foreach ($order_history as $cart_table_data) {
            // echo '<pre>';
            // print_r($cart_table_data);
            // echo '</pre>';
            // // die();
            $cart_type = $cart_table_data[0]['cart_type'];

            $liquor_count = $cart_table_data[0]['liquor_count'];
            $cart['cart_header'] = ($cart_type == 'consumer') ? CONSUMER_CART_TABLE : ENTITY_CART_TABLE;
            $cart['cart_total_table_header'] = ($cart_type == 'consumer') ? CONSUMER_CART_TOTAL_TABLE : ENTITY_CART_TOTAL_TABLE;
            $cart['cart_data_array'] = $cart_table_data;
            // $mode
            $canteen_details = $cart_table_data[0]['canteen_details'];
            $order_code = $cart_table_data[0]['order_code'];
            $order_process = $cart_table_data[0]['order_process'];
            $liquor_order_time = $cart_table_data[0]['liquor_order_time'];
            $callout = 'callout-info';
            $status = 'Order Placed';
            if ($order_process == '1') {
                $callout = 'callout-info';
                $status = 'Order Placed';
            } else if ($order_process == '3') {
                $callout = 'callout-success';
                $status = 'Order Delivered';
            } else if ($order_process == '2') {
                $callout = 'callout-primary';
                $status = 'Order Dispatched';
            } else {
                $callout = 'callout-danger';
                $status = 'Order Cancelled';
            }
            // $order_code = ($cart_table_data[0]['order_code'] !== '' && ($mode == 'order_summary' || $mode == 'order_delivery_summary')) ? $cart_table_data[0]['order_code'] : '';

        ?>
            <div class="card-body p-0">
                <div class="card callout collapsed-card <?= $callout ?> p-0 " style="margin-bottom:1px !important">
                    <div class="card-header">
                        <div style="display:flex">
                            <h5 style="font-size:16px !important" class="card-title"><?= $display_type ?> &nbsp;</h5>
                            <h6 class="card-title" style="font-size:14px !important;"><?= '' . ' : ' . $canteen_details ?></h6>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <h5 class="card-title" style="font-size:16px !important; padding-left:15px;">Order Code &nbsp;</h5>
                            <h6 class="card-title" style="font-size:14px !important;"><?= '' . ' : ' . $order_code ?></h6>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <h5 class="card-title" style="font-size:16px !important; padding-left:15px;">Order Time &nbsp;
                            </h5>
                            <h6 class="card-title" style="font-size:14px !important;"><?= '' . ' : ' . $liquor_order_time ?></h6>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <h5 class="card-title" style="font-size:16px !important; padding-left:15px;">Order Status &nbsp;
                            </h5>
                            <h6 class="card-title" style="font-size:14px !important;"><?= '' . ' : ' . $status ?></h6>
                        </div>
                        <div class="card-tools">
                            <!-- <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
                            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                            <!-- <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="cart-table-area p-0">
                            <div class="container-fluid p-0">
                                <div class="row">
                                    <div class="col-12">
                                        <?php $this->load->view('cart/cart_total_table', $cart); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php echo form_close(); ?>
</section>
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = "<?php echo base_url(); ?>";
</script>
<script src="<?= base_url() ?>assets/js/module/cart/summary.js"></script>
<script>
    var mode = "<?= $mode ?>";
    var order_code = "<?= $order_code ?>";
    displayMessage(mode);
</script>