<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/select2/select2.min.css">
<style>
    .hh-grayBox {
        background-color: #F8F8F8;
        margin-bottom: 20px;
        padding: 35px;
        margin-top: 20px;
    }

    .pt45 {
        padding-top: 45px;
    }

    .order-tracking {
        text-align: center;
        width: 33.33%;
        position: relative;
        display: block;
    }

    .order-tracking .is-complete {
        display: block;
        position: relative;
        border-radius: 50%;
        height: 30px;
        width: 30px;
        border: 0px solid #AFAFAF;
        background-color: #f7be16;
        margin: 0 auto;
        transition: background 0.25s linear;
        -webkit-transition: background 0.25s linear;
        z-index: 2;
    }

    .order-tracking .is-complete:after {
        display: block;
        position: absolute;
        content: '';
        height: 14px;
        width: 7px;
        top: -2px;
        bottom: 0;
        left: 5px;
        margin: auto 0;
        border: 0px solid #AFAFAF;
        border-width: 0px 2px 2px 0;
        transform: rotate(45deg);
        opacity: 0;
    }

    .order-tracking.completed .is-complete {
        border-color: #27aa80;
        border-width: 0px;
        background-color: #27aa80;
    }

    .order-tracking.completed .is-complete:after {
        border-color: #fff;
        border-width: 0px 3px 3px 0;
        width: 7px;
        left: 11px;
        opacity: 1;
    }

    .order-tracking p {
        color: #A4A4A4;
        font-size: 16px;
        margin-top: 8px;
        margin-bottom: 0;
        line-height: 20px;
    }

    .order-tracking p span {
        font-size: 14px;
    }

    .order-tracking.completed p {
        color: #000;
    }

    .order-tracking::before {
        content: '';
        display: block;
        height: 3px;
        width: calc(100% - 40px);
        background-color: #f7be16;
        top: 13px;
        position: absolute;
        left: calc(-50% + 20px);
        z-index: 0;
    }

    .order-tracking:first-child:before {
        display: none;
    }

    .order-tracking.completed:before {
        background-color: #27aa80;
    }
</style>
<style>
    td {
        text-align: center;
        vertical-align: middle;
    }
</style>

<div class="cart-table-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="container">
                    <div class="row">
                        <div class="col-4">

                        </div>
                        <div class="col-4">
                            <?php $this->load->view('master/alpha_numeric_space_field', array("field_id" => "ordercode", "label" => "Order Code", "max_length" => "10", "place_holder" => "Enter Order Code", "value" => "")); ?>
                        </div>
                        <div class="col-4"><br>
                            <button id="submitordercode" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-4">

                        </div>
                        <div class="col-4">
                        <button id="submitordercode" class="btn btn-primary" >Submit</button>  
                        </div>
                    </div> -->
                </div>
                <!-- <div class="row">
                    <div class="col-12 col-md-12 hh-grayBox pt45 pb20">
                        <div class="row justify-content-between">
                            <div class="order-tracking completed">
                                <span class="is-complete"></span>
                                <p>Ordered<br><span>Mon, June 24</span></p>
                            </div>
                            <div class="order-tracking">
                                <span class="is-complete"></span>
                                <p>Shipped<br><span>Tue, June 25</span></p>
                            </div>
                            <div class="order-tracking">
                                <span class="is-complete"></span>
                                <p>Delivered<br><span>Fri, June 28</span></p>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

            <div id="orderdescdiv" class="card-body  p-0">
                <table class="table datatable datatable-bordered table-bordered datatable-striped">
                    <thead>
                        <tr>
                            <th class="text-center">LIQUOR NAME</th>
                            <th class="text-center">LIQUOR TYPE</th>
                            <th class="text-center">LIQUOR BOTTLE SIZE</th>
                            <th class="text-center">LOT QUANTITY</th>
                            <th class="text-center">UNIT LOT BOTTLES</th>
                            <th class="text-center">TOTAL BOTTLE COUNT</th>
                            <th class="text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody id="orderdesctbody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = "<?php echo base_url(); ?>";
</script>
<script src="<?= base_url() ?>assets/plugins/select2/select2.full.min.js"></script>
<script src="<?= base_url() ?>assets/js/module/order/orderdetails.js"></script>