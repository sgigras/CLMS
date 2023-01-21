<!-- to create cart table productid is from mapping product table -->
<div class="cart-table-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="cart-table clearfix">
                    <!-- <input type> -->
                    <table class="table cart-table-responsive" id="<?= $cart_id ?>_cart">
                        <thead style='white-space: nowrap'>
                            <tr>
                                <?php
                                $table_head_array = unserialize($cart_header);
                                $column_count = 0;
                                foreach ($table_head_array as $column_header) {
                                    $column_array = explode(":", $column_header);
                                    if (strpos($column_array[0], '_hidden') !== false) {

                                        echo "<th class='cart-table-text-align' style='display:none'><h6>" . trans($column_array[0]) . "<h6></th>";
                                    } else {
                                        echo "<th class='cart-table-text-align'><h6>" . trans($column_array[0]) . "<h6></th>";
                                    }
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($cart_data_array as $row => $record) {
                                $cart_details = $record['quantity']; //to extract cart_entity_liquor_id and cart_id and  2_3_15 2-quantity value represents 3-liquor_entity_id 15-cart_id
                                $entity_product_id = explode('_', $cart_details);
                                $quantity = $entity_product_id[0];
                                $product_id = $entity_product_id[1];
                                $cart_id = $entity_product_id[2];
                                $field_id = $product_id . "_" . $cart_id; ?>
                                <tr>
                                    <?php
                                    foreach ($table_head_array as $column_header) {
                                        $data_key = explode(":", $column_header);
                                        $details = $record[$data_key[1]]; ?>


                                        <?php if (strpos($data_key[0], '_image') !== false) {
                                        ?>
                                            <td>
                                                <div class="cart_product_img animate__animated animate__fadeInLeft ">
                                                    <img src="<?= base_url() . $details ?>" alt="Product" class="img-size">
                                                </div>

                                            </td>
                                        <?php
                                        } elseif (strpos($data_key[0], '_buttons') !== false) { ?>
                                            <td class="qty" style="max-width:300px">
                                                <input type="hidden" value="<?php $product_id . "_" . $cart_id ?>" />
                                                <?php $this->load->view('master/increment_decrement_new_field', array("field_id" => $field_id, "product_id" => $product_id, "cart_id" => $cart_id, "quantity" => $quantity)) ?>
                                            </td>
                                        <?php } elseif (strpos($data_key[0], '_hidden') !== false) { ?>
                                            <td class="cart_product_desc" style="display: none;">
                                                <input type='hidden' value='<?= $details ?>'>
                                                <!-- <h5 class="cart-item-display-line-height cart-table-text-align" ><?= $details ?></h5> -->
                                            </td>
                                        <?php } elseif (strpos($data_key[0], 'remove') !== false) { ?>
                                            <td class="cart_product_desc">

                                                <input type='hidden' id='<?= $field_id ?>_remove_flag' value='<?= $details ?>'>
                                                <button id='<?= $field_id ?>_remove_liquor"' class="btn     remove_liquor " style="margin-top: 4vh;background: none;    margin-left: 4vw"><i style="color: red;text-shadow: 1px 1px 1px #ccc;    font-size: 2.5em;" class="fa fa-times-circle-o" aria-hidden="true"></i></button>
                                            </td>
                                        <?php } elseif (strpos($data_key[0], 'cart_table_liquor_name') !== false) { ?>
                                            <td class="cart_product_desc">
                                                <h5 style="padding-top:20px;" class="cart-item-display-line-height cart-table-text-align"><?= $details ?></h5>
                                            </td>
                                        <?php } else {
                                        ?>
                                            <td class="cart_product_desc">
                                                <h5 class="cart-item-display-line-height cart-table-text-align"><?= $details ?></h5>
                                            </td>
                                        <?php
                                        } ?>











                                    <?php } ?>
                                    <!-- end for cloum header loop to extract row wise data using keys-->


                                <?php } //end of for loop data recors for td    
                                ?>
                                </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

?>