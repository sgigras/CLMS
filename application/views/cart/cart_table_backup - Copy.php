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
                                //                                print_r($record);
                            ?>
                                <tr>
                                    <?php
                                    foreach ($table_head_array as $column_header) {
                                        $data_key = explode(":", $column_header);
                                        $button_column_head=(in_array('cart_quantity_in_lot_buttons',$data_key))? 'cart_quantity_in_lot_buttons':'cart_quantity_buttons';
                                        $cart_details=$record['quantity'];
                                        $entity_product_id = explode('_', $cart_details);
                                        $quantity = $entity_product_id[0];
                                        $product_id = $entity_product_id[1];
                                        $cart_id = $entity_product_id[2];
                                        $field_id = $product_id . "_" . $cart_id;
                                        



                                        //                                        if(strpos($data_key[0], '_id'))
                                        if (strpos($data_key[0], '_image') !== false) {
                                    ?>
                                            <!--//                                        ;-->
                                            <td>
                                                <div class="cart_product_img animate__animated animate__fadeInLeft ">
                                                    <img src="<?= base_url() . $details ?>" alt="Product" class="img-size">
                                                </div>

                                            </td>
                                        <?php
                                        } elseif (strpos($data_key[0], '_buttons') !== false) {
                                            
                                        ?>

                                            <td class="qty">
                                                <input type="hidden" value="<?php $product_id . "_" . $cart_id ?>" />
                                                <?php $this->load->view('master/increment_decrement_new_field', array("field_id" => $field_id, "product_id" => $product_id, "cart_id" => $cart_id, "quantity" => $quantity)) ?>
                                            </td>
                                        <?php } elseif (strpos($data_key[0], '_hidden') !== false) { ?>
                                            <td class="cart_product_desc" style="display: none;">
                                                <input type='hidden' value='<?= $details ?>'>
                                                <!-- <h5 class="cart-item-display-line-height cart-table-text-align" ><?= $details ?></h5> -->
                                            </td>

                                        <?php } elseif(strpos($data_key[0], '_hidden') !== false){?>
                                            <td class="cart_product_desc">
                                                <button id=""></button>
                                            </td>
                                        
                                        <?php}else {
                                        ?>
                                            <td class="cart_product_desc">
                                                <h5 class="cart-item-display-line-height cart-table-text-align"><?= $details ?></h5>
                                            </td>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tr>
                            <?php } //end of for loop data recors for td    
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


