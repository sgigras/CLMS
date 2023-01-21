<img class="imgstyle" src="  base_url() .  image_path  " alt="">
<div class="hover-content-div" style="padding: 4px 0px 0px 13px;">
    <h4 style="margin-bottom:0px;">product_name liquor_description
        <p style="font-size:22px;font-weight: lighter;margin-bottom: 0vh;"><b>30ml</b></p>
    </h4>
    <!-- <h5></h5> -->
    <p class="pstyle"> ( cart_type == 'consumer') ? 'Rs ' . liquor_price_lot_size : "Lot Size:" . liquor_price_lot_size </p>
    <div class="row">
        <input type="hidden" value="product_id  ">
        <div class="col-sm-2">
            <p>Qty</p>
        </div>
        <div class="col-sm-10">
            <div class="quantity">
                <div class="row">
                    <div class="col-sm-2 qty">
                        <button class="btn" id="btn_sub_field_id  " onclick="increment_decrement_quantity('field_id_qty', 'product_id  ', 'S')">-</button>
                    </div>
                    <div class="col-sm-6 qty-number">
                        <input type="text" class="form-control" style="background-color: #d6d6d7; color: black;text-align:center" id="field_id_qty" step="1" name="quantity" value="1">
                    </div>
                    <div class="col-sm-2 qtyplus">
                        <button class="btn" id="btn_add_field_id  " onclick="increment_decrement_quantity('field_id_qty', 'product_id  ', 'A')">+</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-5"></div>
    </div>
</div>
<div>
    <center><a class="btn addtocart-btn" id="add_to_cart_field_id ?>" onclick="cart_processing('<?= $product_id ?>', '<?= $product_name ?>', '<?= $liquor_price_lot_size ?>', '<?= $field_id ?>_qty', 'add_to_cart_<?= $field_id ?>', '<?= base_url() . $cart_path ?>', '0')">Add to Cart</a></center>
</div>