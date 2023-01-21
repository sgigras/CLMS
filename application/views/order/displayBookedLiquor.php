<section class="content">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="<?= $fa_form_icon; ?>"></i>
                    <?= trans($title); ?>
                </h3>
            </div>
        </div>
    </div>

    <?php

    if (count($booked_liquor) == 0) {
        echo '<h3 class="text-danger" style="padding-left:20px;">You have not Ordered anything Yet!!</h3>';
    }




    foreach ($booked_liquor as $cart_table_data) {
        // // die();
        $cart_type = $cart_table_data[0]['cart_type'];
        $cart['cart_header'] = ($cart_type == 'consumer') ? CONSUMER_CART_TABLE : ENTITY_CART_TABLE;
        $cart['cart_total_table_header'] = ($cart_type == 'consumer') ? CONSUMER_CART_TOTAL_TABLE : ENTITY_CART_TOTAL_TABLE;
        $cart['cart_data_array'] = $cart_table_data;
        // $order_code = ($cart_table_data[0]['order_code'] !== '' && ($mode == 'order_summary' || $mode == 'order_delivery_summary')) ? $cart_table_data[0]['order_code'] : '';
        // $cart['order_code'] = $order_code;
        // $mode
        $order_code = $cart_table_data[0]['order_code'];
        $liquor_order_time = $cart_table_data[0]['liquor_order_time'];

        $name = $cart_table_data[0]['name'];
        $irla = $cart_table_data[0]['irla'];
        $entity_type = $cart_table_data[0]['entity_type'];
        $entity_details = $cart_table_data[0]['entity_details'];

        $callout = 'callout-info';
        // $order_code = ($cart_table_data[0]['order_code'] !== '' && ($mode == 'order_summary' || $mode == 'order_delivery_summary')) ? $cart_table_data[0]['order_code'] : '';

    ?>
        <div class=" p-2">
            <div class="card callout collapsed-card <?= $callout ?> p-0 " style="margin-bottom:1px !important">
                <div class="card-header">
                    <div style="display:flex">
                        <?php if ($cart_type == 'consumer') { ?>
                            <h3 class="card-title" style="padding-left:15px;">Name &nbsp;</h3>
                            <h4 class="card-title"><?= '' . ' : ' . $name ?></h4>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                            <h3 class="card-title" style="border-left: solid 1px; padding-left:15px;">Irla &nbsp;</h3>
                            <h4 class="card-title"><?= '' . ' : ' . $irla ?></h4>
                        <?php } else { ?>
                            <h3 class="card-title" style="border-left: solid 1px; padding-left:15px;">Outlet Type &nbsp;</h3>
                            <h4 class="card-title"><?= '' . ' : ' . $entity_type ?></h4>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <h3 class="card-title" style="border-left: solid 1px; padding-left:15px;">Outlet Details &nbsp;</h3>
                            <h4 class="card-title"><?= '' . ' : ' . $entity_details ?></h4>
                        <?php } ?>


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
    <!-- </div> -->
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