<?php ?>
<div class="cart-summary animate__animated animate__fadeInRight">
    <div class="card-body  p-0">
        <table class="table table-condensed">
            <thead>
                <tr>
                    <?php
                    $table_head_array = unserialize($cart_total_table_header);
                    foreach ($table_head_array as $column_header) {
                        $column_array = explode(":", $column_header);
                        echo "<th><h6>" . trans($column_array[0]) . "<h6></th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_price = 0.0;
                $total_unit = 0;
                foreach ($cart_data_array as $record) {

                    $total_price = round(floatval($total_price), 2) + round(floatval($record["total_quantity_cost"]), 2);

                    // if (sizeof($table_head_array) == 5) {
                    //     $total_price = round(floatval($total_price), 2) + round(floatval($record["total_quantity_cost"]), 2);
                    // } else {
                    //     $total_price = round(floatval($total_price), 2) + round(floatval($record["total_cost"]), 2);
                    //     $total_unit = round(floatval($total_unit), 2) + round(floatval($record["total_quantity_cost"]), 2);
                    // }


                    echo "<tr>";
                    foreach ($table_head_array as $column_header) {
                        $data_key = explode(":", $column_header);

                        if (strpos($data_key[0], '_buttons') !== false) {
                            $entity_product_id = explode('_', $record[$data_key[1]]);
                            $quantity = $entity_product_id[0];
                            $product_id = $entity_product_id[1];
                            $cart_id = $entity_product_id[2];
                            $field_id = $product_id . "_" . $cart_id;
                            echo "<td id='$field_id" . "_" . "$data_key[0]' class='cart_total_table_quantity'>" . $quantity . "</td>";
                        } else {

                            echo "<td>" . $record[$data_key[1]] . "</td>";
                        }
                    }

                    echo "</tr>";
                }
                    // echo "<tr><td>Total</td><td colspan='3'></td><td>$total_price</td></tr>";
                  echo "<tr><td>Total</td><td colspan='2'></td><td></td><td>$total_price</td></tr>";

                // if (sizeof($table_head_array) == 5) {
                //     echo "<tr><td>Total</td><td colspan='3'></td><td>$total_price</td></tr>";
                // } else {
                //     echo "<tr><td>Total</td><td colspan='4'></td><td>$total_unit</td><td>$total_price</td></tr>";
                // }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!--    <div class="col-12 col-md-8"></div>
</div>-->