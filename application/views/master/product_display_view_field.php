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
                            <button class="btn" id="btn_sub_<?= $field_id ?>">-</button>
                        </div>
                        <div class="col-sm-6 qty-number">      
                            <input type="text" class="form-control" style="background-color: #d6d6d7; color: black;text-align:center" id="<?= $field_id ?>_qty" step="1"  name="quantity" value="1">
                        </div>
                        <div class="col-sm-2 qtyplus" >      
                            <button class="btn" id="btn_add_<?= $field_id ?>">+</button>  
                        </div>                         
                    </div>    
                </div>
            </div>
        </div>
    </div>
    <div>
        <center> <a href="<?= base_url() . $cart_path ?>" class="btn addtocart-btn ">Add to Cart</a></center>
    </div>
    <!--</a>-->

</div>