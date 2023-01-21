<!-- Content Wrapper. Contains page content -->
<!--<div class="content-wrapper">-->


<!-- Core Style CSS -->
<link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/core-style.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/style.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/animation/animate.min.css">


<!-- Main content -->
<section class="content">
    <div class="container">
        <div class="card">
            <div class="cart-table-area section-padding-20">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <?php
                            $cart['cart_data_array'] = $cart_table_data;
                            $cart['cart_header'] = ENTITY_CART_TABLE;
                            $this->load->view('cart/cart_table', $cart);
                            ?>
                            $this
                        </div>
                        <div class="col-12 col-lg-4">
                            <br><br>
                            <div class="cart-summary shadow animate__animated animate__fadeInRight">
                                <h4 style="color: #0b095a;">Cart Summary</h4>
                                <br><br>
                                <hr>
                                
                                
                                <ul class="summary-table">
                                    <li>

                                    </li>
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
<!--</div>-->
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
<script src="<?= base_url() ?>assets/js/active.js"></script>
