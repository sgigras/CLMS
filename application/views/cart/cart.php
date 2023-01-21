<!-- Content Wrapper. Contains page content -->
<?php

?>

<script>
    var page_mode = '<?= $page_mode ?>';
    var cart_id = '<?= $cart_id ?>';
    var redirect_url = '<?= $redirect_url ?>';
    var cart_type = '<?= $cart_type ?>';
    var delivarable_entity_id = <?= $delivarable_entity_id ?>;
    console.log(cart_type);
    // var delivarable_entity_id='';
</script>
<!--<div class="content-wrapper" style="margin-top: 55px;min-height: 580.08px !important;">-->
<!-- Main content -->
<section class="content p-0">
    <div class="card card-default" style="border-radius:0px !important">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fas fa-shopping-cart"></i>
                    <?= trans($title) ?> </h3>
            </div>
        </div>
        <div class="card-body p-0">
            <!-- For Messages -->
            <?php // $this->load->view('admin/includes/_messages.php')   
            ?>

            <?php
            $redirect_url = 'cart/CartDetails/placeOrder';
            $id = $cart_id . "_cart_details_form";
            echo form_open(base_url($redirect_url), array('class' => 'form-horizontal', 'name' => 'cart_details', 'id' => 'cart_details'), 'class="form-horizontal"');
            if ($mode !== 'A') {
                $id = getValue('id', $resultArray);
                echo "<input type='hidden' name='edit_id' id='edit_id' value='$id' >";
            }

            $cart['cart_data_array'] = $cart_table_data;
            $cart['cart_header'] = ($cart_type == 'consumer') ? CONSUMER_CART_TABLE : ENTITY_CART_TABLE;
            $cart['cart_total_table_header'] = ($cart_type == 'consumer') ? CONSUMER_CART_TOTAL_TABLE : ENTITY_CART_TOTAL_TABLE;

            ?>
            <div class="cart-table-area p-0">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-12 col-lg-12 p-0">
                            <?php $this->load->view('cart/cart_table', $cart); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class=row>
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                        <button id="<?= $cart_id ?>_placeOrder_btn" class="btn btn-lg btn-outline-primary place_order mx-1" style="float:right"><i class="fas fa-shopping-cart"></i>&nbsp;<?= trans($cart_button_2) ?></button>

                        <button id="<?= $cart_id ?>_continueShopping_btn" class="btn btn-lg btn-outline-secondary continue_shopping mx-1" style="float:right"><i class="fa fa-arrow-left"></i>&nbsp;<?= trans($cart_button_1) ?></button>
                        <input type="hidden" name="submit" value="submit" />
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
<script src="<?= base_url() ?>assets/js/module/cart/cart.js"></script>