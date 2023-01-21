<!-- Content Wrapper. Contains page content -->
<?php
/*
    to display cart collapsible for accordion details in summarized form according to the page load or url called
    author:Jitendra Pal
    date:-28-10-2021
*/
?>
<!--<div class="content-wrapper" style="margin-top: 55px;min-height: 580.08px !important;">-->
<!-- Main content -->

<?php
$redirect_url = 'cart/CartDetails/addCartDetails';
$id = $cart_id . "_cart_details_form";
$liquor_count = $cart_table_data[0]['liquor_count'];
$cart['cart_header'] = ($cart_type == 'consumer') ? CONSUMER_CART_TABLE : ENTITY_CART_TABLE;
$cart['cart_total_table_header'] = ($cart_type == 'consumer') ? CONSUMER_CART_TOTAL_TABLE : ENTITY_CART_TOTAL_TABLE;
$cart['cart_data_array'] = $cart_table_data;
// $mode
$order_code = ($cart_table_data[0]['order_code'] !== '' && ($mode == 'order_summary' || $mode == 'order_delivery_summary')) ? $cart_table_data[0]['order_code'] : '';
?>

<div class="card card-info card-outline" style="background-color: #007bff;">
    <div class="card-header">
        <h3 class="card-title">Liquor Details</h3>
        <h4 class="card-title"><?= $order_code ?></h4>
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
<!-- <?php  ?> -->
<?php
if ($mode != 'order_summary') {
    $button_function_1 = "onclick=edit_order_delivery(" . "'" . $id . "'" . ")";
    $button_function_2 = "onclick=submit_order_delivery(" . "'" . $id . "'" . ")";

    $this->load->view('cart/cart_summary_footer', array(
        'cart_id' => $cart_id, 'button_id_1' => $button_id_1,
        'button_class_1' => $button_class_1,
        'button_label_1' => $button_label_1,
        'button_id_2' => $button_id_2,
        'button_function_1' => $button_function_1,
        'button_function_2' => $button_function_2,
        'button_class_2' => $button_class_2,

        'button_label_2' => $button_label_2

    ));
} ?>