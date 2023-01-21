<div class="col-lg-3 content-div border rounded animate__animated animate__fadeInDown">
    <!-- Single Catagory -->

    <!--<a href="shop.html">-->
    <img class="imgstyle" src="<?= base_url() . $image_path ?>" alt="">
    <div class="hover-content-div" style="padding: 4px 0px 0px 13px;">
        <h4 class="text-muted" style="margin-bottom:0px;"><?= $product_name ?> <p style="font-size:22px;font-weight: lighter;margin-bottom: 0vh;"><?= $liquor_description ?></p>
            <p style="font-size:22px;font-weight: lighter;margin-bottom: 0vh;"><b><?= $liquor_ml ?> ml</b></p>
        </h4>
        <!--<h6>Available QUantity</h6>-->
        <?php
        // if ($cart_type == 'consumer') {
            if ($available_quantity == 0) {
        ?>
                <h4 class="text-muted" style="background-color:red">
                    Stock - Not Available
                </h4>
            <?php
            } else {
            ?>
                <h4 class="text-muted">Stock -
                    <b><?= $available_quantity ?></b>
                </h4>
                <h4 class="text-muted"><b><i class="fa fa-inr "> &nbsp; </i><?= $liquor_price_per_bottel ?></b>
                </h4>
        <?php

            }
        // }
        ?>
        <!-- <> -->
        <!-- <h5></h5> -->
        <!-- <h4>
            <p class="pstyle" style="font-size:20px"><?= ($cart_type == 'consumer') ? '<i class="fa fa-inr"></i> ' . $liquor_price_lot_size : "Lot Size - " . $liquor_price_lot_size ?> </p>
        </h4> -->

        <div class="row">
            <input type="hidden" value="<?= $product_id ?>">
            <div class="col-sm-2">
                <p style="white-space:nowrap">Qty <?= ($cart_type != 'consumer') ? '<sub></sub>' : '' ?></p> &nbsp;
            </div>
            <div class="col-sm-8">
                <div class="quantity">
                    <div class="row">
                        <div class="col-sm-2 qty">
                            <button class="btn" id="btn_sub_<?= $field_id ?>" onclick="increment_decrement_quantity('<?= $field_id ?>_qty', '<?= $product_id ?>', 'S')">-</button>
                        </div>
                        <div class="col-sm-6 qty-number">
                            <input type="text" class="form-control" onkeypress="return checkValidInputKeyPress(numeric_regex_pattern);" style="background-color: #d6d6d7; color: black;text-align:center" id="<?= $field_id ?>_qty" step="1" name="quantity" value="1">
                        </div>
                        <div class="col-sm-2 qtyplus">
                            <button class="btn" id="btn_add_<?= $field_id ?>" onclick="increment_decrement_quantity('<?= $field_id ?>_qty', '<?= $product_id ?>', 'A')">+</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-5"></div>
        </div>
    </div>
    <div>
        <?php

        if ($available_quantity == 0) {
        ?>
            <!--<center><a class="btn addtocart-btn">Product Not Available</a></center>-->
        <?php
        } else {
        ?>
            <center><a class="btn addtocart-btn" id="add_to_cart_<?= $field_id ?>" onclick="cart_processing('<?= $product_id ?>', '<?= $product_name ?>', '<?= $liquor_price_per_bottel ?>', '<?= $field_id ?>_qty', 'add_to_cart_<?= $field_id ?>', '<?= base_url() . $cart_path ?>', '0')">Add to Cart</a></center>
        <?php
        }
        // if ($product_already_in_cart == 0) {
        ?>
        <!--<center><a class="btn addtocart-btn" id="add_to_cart_<?= $field_id ?>" onclick="cart_processing('<?= $product_id ?>', '<?= $product_name ?>', '<?= $liquor_price_lot_size ?>', '<?= $field_id ?>_qty', 'add_to_cart_<?= $field_id ?>', '<?= base_url() . $cart_path ?>', '0')" <?= $available_quantity_ena_disable ?>>Add to Cart</a></center>-->
        <?php
        // } else if ($product_already_in_cart == 1) {
        ?>
        <!-- <center> <a class="btn addtocart-btn" id="add_to_cart_<?= $field_id ?>" onclick="cart_processing('<?= $product_id ?>', '<?= $product_name ?>', '<?= $liquor_price_lot_size ?>', '<?= $field_id ?>_qty', 'add_to_cart_<?= $field_id ?>', '<?= base_url() . $cart_path ?>', '1')">Remove</a></center> -->
        <?php
        // }
        ?>
    </div>
    <!--</a>-->

</div>
<!-- </center> -->