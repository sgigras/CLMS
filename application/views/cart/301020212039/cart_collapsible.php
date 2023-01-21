<!-- Content Wrapper. Contains page content -->
<?php
/*
    to display cart  collapsible for accordion details  in summarized cart data  form according to the page load or url called
    author:Jitendra Pal
    date:-28-10-2021
*/
$cart['cart_header'] = ($cart_type == 'consumer') ? CONSUMER_CART_TABLE : ENTITY_CART_TABLE;
$cart['cart_total_table_header'] = ($cart_type == 'consumer') ? CONSUMER_CART_TOTAL_TABLE : ENTITY_CART_TOTAL_TABLE;
$cart['cart_data_array'] = $cart_table_data;
$order_code = ($cart_table_data[0]['order_code'] !== '' && ($mode == 'order_summary' || $mode == 'order_delivery_summary')) ? $cart_table_data[0]['order_code'] : '';
$cart['order_code'] = $order_code;
?>


<div class="card card-info card-outline" style="background-color: #007bff;">
    <div class="card-header">
        <h3 class="card-title">Liquor Details</h3>
        <h4 class="card-title"><?= $cart['order_code'] ?></h4>
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
<?php $this->load->view('order/order_delivery_summary_cart_footer', array('cart_footer_data' => $cart_footer_buttons)); ?>