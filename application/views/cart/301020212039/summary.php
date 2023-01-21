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
$redirect_url = 'cart/CartDetails/addCartDetails';
$id = $cart_id . "_cart_details_form";
$liquor_count = $cart_table_data[0]['liquor_count'];
$cart['cart_header'] = ($cart_type == 'consumer') ? CONSUMER_CART_TABLE : ENTITY_CART_TABLE;
$cart['cart_total_table_header'] = ($cart_type == 'consumer') ? CONSUMER_CART_TOTAL_TABLE : ENTITY_CART_TOTAL_TABLE;
$cart['cart_data_array'] = $cart_table_data;
// $mode
$order_code = ($cart_table_data[0]['order_code'] !== '' && ($mode == 'order_summary' || $mode == 'order_delivery_summary')) ? $cart_table_data[0]['order_code'] : '';
?>
<input type="hidden" value="<?= $liquor_count ?>" id="liquor_count">
<input type="hidden" value="<?= $cart_id ?>" id="cart_id" name="cart_id">

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
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="<?= $fa_form_icon ?>"></i>
                    <?= trans($title) ?>
                </h3>
            </div>
            <div class="d-inline-block float-right">
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
        </div>
        <div class="card-body" style="padding:30px;">

            <div class="card card-info card-outline" style="background-color: #007bff;">
                <div class="card-header">
                    <div style="display:flex">

                        <h3 class="card-title">Canteen Details &nbsp;</h3>
                        <h4 class="card-title"><?= '' . ' : ' . $canteen_details ?></h4>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        <h3 class="card-title" style="border-left: solid 1px; padding-left:15px;">Liquor Details &nbsp;</h3>
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
                                    <?php $this->load->view('cart/cart_total_table', $cart); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="Card-footer">
                <div class="form-group">
                    <div class=row>
                        <div class="col-md-4"></div>
                        <div class="col-md-8" style="margin-left: 50vw;">
                            <?php if ($mode != 'order_summary' && $mode != 'order_delivery_summary') { ?>
                                <button id="<?= $cart_id . "_" . $button_id_1 ?>" class="btn btn-lg btn-info <?= $button_class_1 ?>"><i class="fa fa-arrow-left"></i>&nbsp;<?= trans($button_label_1) ?></button>

                                <button id="<?= $cart_id . "_" . $button_id_2 ?>" class="btn btn-lg btn-primary <?= $button_class_2 ?>"><i class="<?= $fa_button_icon ?>"></i>&nbsp;<?= trans($button_label_2) ?></button>
                            <?php } ?>
                            <input type="hidden" name="submit" value="submit" />
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
<script src="<?= base_url() ?>assets/js/module/cart/summary.js"></script>
<script>
    var mode = "<?= $mode ?>";
    var order_code = "<?= $order_code ?>";
    displayMessage(mode);
</script>