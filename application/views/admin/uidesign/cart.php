  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
 

<!-- Core Style CSS -->
<link rel="stylesheet" href="<?= base_url()?>assets/dist/css/core-style.css">
<link rel="stylesheet" href="<?= base_url()?>assets/dist/css/style.css">
<link rel="stylesheet" href="<?= base_url()?>assets/plugins/animation/animate.min.css">


    <!-- Main content -->
    <section class="content">
      <div class="container">
       <div class="card">
       <div class="cart-table-area section-padding-20">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="cart-title ">
                            <h2 class="animate__animated animate__fadeIn">Shopping Cart</h2>
                        </div>

                        <div class="cart-table clearfix">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th><h6>Name</h6></th>
                                        <th><h6>Price</h6></th>
                                        <th><h6>Quantity</h6></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="cart_product_img animate__animated animate__fadeInLeft">
                                            <a href="#"><img src="<?= base_url()?>assets/dist/img/product-img/jackdaniels.jpg" alt="Product" class="img-size"></a>
                                        </td>
                                        <td class="cart_product_desc">
                                            <h5 class="lineheight">White Modern Chair</h5>
                                        </td>
                                        <td class="price">
                                            <span class="lineheight">$130</span>
                                        </td>
                                        <td class="qty" style="width:300px">
                                        
                                        <div class="row lineheight">
                                                <div class="input-group mb-3 ">
                                                <div class="input-group-prepend">
                                                    <button class="input-group-text">-</button>
                                                </div>
                                                <input type="text" class="form-control">
                                                <div class="input-group-append">
                                                <button class="input-group-text">+</button>
                                                </div>
                                                </div>
                                            <!-- <div class="col-sm-2 qty">      
                                                <button class="btn" style="margin-left: 9px;">-</button>
                                            </div>
                                            <div class="col-sm-6 qty-number" style="padding-top: 83px">      
                                            <input type="text" class="form-control" style="background-color: #d6d6d7; height: 37px; color: black;text-align:center" id="qty" step="1"  name="quantity" value="1">
                                            </div>
                                            <div class="col-sm-2 qtyplus" >      
                                            <button class="btn">+</button>  
                                            </div>                          -->
                                        </div>  
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cart_product_img animate__animated animate__fadeInLeft">
                                            <a href="#"><img src="<?= base_url()?>assets/dist/img/product-img/oldmonk.jpg" alt="Product" class="img-size"></a>
                                        </td>
                                        <td class="cart_product_desc">
                                            <h5>Minimal Plant Pot</h5>
                                        </td>
                                        <td class="price">
                                            <span>$10</span>
                                        </td>
                                        <td class="qty">
                                            <div class="qty-btn d-flex">
                                                <p>Qty</p>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cart_product_img animate__animated animate__fadeInLeft">
                                            <a href="#"><img src="<?= base_url()?>assets/dist/img/product-img/hienken_vodka.jpg" alt="Product" class="img-size"></a>
                                        </td>
                                        <td class="cart_product_desc">
                                            <h5>Minimal Plant Pot</h5>
                                        </td>
                                        <td class="price">
                                            <span>$10</span>
                                        </td>
                                        <td class="qty">
                                            <div class="qty-btn d-flex">
                                                <p>Qty</p>
                                                
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <br><br>
                        <div class="cart-summary shadow animate__animated animate__fadeInRight">
                            <h4 style="color: #0b095a;">Cart Total</h4>
                            <br><br>
                            <ul class="summary-table">
                                <li><span>subtotal:</span> <span class="subcart">$140.00</span></li>
                                <br><li><span>delivery:</span> <span class="subcart">Free</span></li>
                                <br><li ><span style="color: #0b095a;font-size:20px">total:</span> <span style="color: #fbb710;font-size:20px" class="subcart" >$140.00</span></li>
                            </ul>
                            <br><br><br><br>
                           <center> <div>
                                <button class="btn" style="background-color: #D10024;color: white; border-radius: 30px; width: 200px;height: 45px;">Checkout</button>
                            </div>
                        </center>
                        <br><br>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
       </div>
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- PAGE PLUGINS -->
<!-- SparkLine -->
 <!-- ##### jQuery (Necessary for All JavaScript Plugins) ##### -->
 <!-- <script src="js/jquery/jquery-2.2.4.min.js"></script> -->
    <!-- Popper js -->
    <!-- <script src="js/popper.min.js"></script> -->
    <!-- Bootstrap js -->
    <!-- <script src="js/bootstrap.min.js"></script> -->
    <!-- Plugins js -->
    <!-- <script src="js/plugins.js"></script> -->
    <!-- Active js -->
    <script src="<?= base_url()?>assets/js/active.js"></script>
