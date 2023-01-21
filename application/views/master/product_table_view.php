<div class="row">
    <?php
    $split = 0;
    for ($i = 0; $i < count($product_data); $i++) {
        $value_cart = count($product_cart_BSF) != 0 ? $product_cart_BSF['CART1'] : array();
        $product_already_in_cart = 0; //0-No,1-Yes
        $previous_quantity = 1;
        for ($j = 0; $j < count($value_cart); $j++) {
            $cart_product_id = $value_cart[$j]['product_id'];
            if ($product_data[$i]->id == $cart_product_id) {
                $product_already_in_cart = 1;
                $previous_quantity = $value_cart[$j]['product_quantity'];
                break;
            }
        }
       
        $this->load->view('master/product_display_field', array(
            "field_id" => "product_" . $product_data[$i]->id,
            "product_id" => $product_data[$i]->id,
            "product_name" => $product_data[$i]->product_name,
            "liquor_description" => $product_data[$i]->liquor_description,
            "available_quantity" => $product_data[$i]->available_quantity,
            "liquor_ml" => $product_data[$i]->liquor_ml,
            "cart_path" => "shop.html",
            "image_path" =>  $product_data[$i]->product_image,
            "cart_type" => $cart_type,
            "liquor_price_lot_size" => $product_data[$i]->unit_lot_cost,
            "liquor_price_per_bottel" => $product_data[$i]->unit_per_bottel


        ));

        $split++;
        if ($split % 4 == 0) {
    ?>
</div><br><br>
<div class="row">
<?php
        }
    }
?>
</div>
