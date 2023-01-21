<div class="col-lg-3 content-div border rounded animate__animated animate__fadeInDown">
    <!-- Single Catagory -->

    <!--<a href="shop.html">-->
    <img class="imgstyle" src="<?= base_url() . "assets/dist/img/product-img/" . $image_path ?>" alt="">
    <div class="hover-content-div">
        <h5><?= $product_name ?></h5>
        <?php
        if ($user_type == 'consumer') {
            echo '<p class="pstyle">' . $product_price . '</p>';
        }
        ?>
        <div class="row">
            <input type="hidden" value="<?= $product_id ?>">
            <div class="col-sm-2"><p>Qty</p></div>
            <div class="col-sm-10">
                <div class="quantity">
                    <div class="row">
                        <div class="col-sm-2 qty">      
                            <button class="btn" id="btn_sub_<?= $field_id ?>" onclick="increment_decrement_quantity('<?= $field_id ?>_qty', '<?= $product_id ?>', 'S')">-</button>
                        </div>
                        <div class="col-sm-6 qty-number">      
                            <input type="text" class="form-control" style="background-color: #d6d6d7; color: black;text-align:center" id="<?= $field_id ?>_qty" step="1"  name="quantity" value="<?= $previous_quantity ?>">
                        </div>
                        <div class="col-sm-2 qtyplus" >      
                            <button class="btn" id="btn_add_<?= $field_id ?>" onclick="increment_decrement_quantity('<?= $field_id ?>_qty', '<?= $product_id ?>', 'A')">+</button>  
                        </div>                         
                    </div>    
                </div>
            </div>
        </div>
    </div>
    <div>
        <?php
        if ($product_already_in_cart == 0) {
            ?>
            <center> <a class="btn addtocart-btn" id="add_to_cart_<?= $field_id ?>" onclick="cart_processing('<?= $product_id ?>', '<?= $product_name ?>', '<?= $product_price ?>', '<?= $field_id ?>_qty', 'add_to_cart_<?= $field_id ?>', '<?= base_url() . $cart_path ?>', '0')">Add to Cart</a></center>
            <?php
        } else if ($product_already_in_cart == 1) {
            ?>
            <center> <a class="btn addtocart-btn" id="add_to_cart_<?= $field_id ?>" onclick="cart_processing('<?= $product_id ?>', '<?= $product_name ?>', '<?= $product_price ?>', '<?= $field_id ?>_qty', 'add_to_cart_<?= $field_id ?>', '<?= base_url() . $cart_path ?>', '1')">Remove</a></center>
            <?php
        }
        ?>
    </div>
    <!--</a>-->

</div>