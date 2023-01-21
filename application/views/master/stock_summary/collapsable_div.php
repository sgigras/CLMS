<div id="infotable">
    <div class="card animate__animated animate__fadeInLeft collapsed-card" style="margin-bottom: 1px !important; border-radius:0px !important; background-color: darkorange !important;">
        <div class="card-header">
            <h5 class="">
                <div class="row">
                    <div class="col-md-2">
                        <center>Liquor Image</center>
                    </div>
                    <div class="col-md-2">
                        <center>Name</center>
                    </div>
                    <div class="col-md-1">
                        <center>Type</center>
                    </div>
                    <div class="col-md-1">
                        <center>Volume</center>
                    </div>
                    <div class="col-md-1">
                        <center>Cost Price</center>
                    </div>
                    <div class="col-md-1">
                        <center>Selling Price</center>
                    </div>
                    <div class="col-md-2">
                        <center>Available Quantity</center>
                    </div>
                    <div class="col-md-2">
                        <center>Physical Quantity</center>
                    </div>
                </div>
            </h5>
        </div>
    </div>
</div>
<!-- for loop -->
<?php for ($i = 0; $i < count($liquor_details); $i++) { ?>
    <div id="infotable">
        <div class="card animate__animated animate__fadeInLeft callout callout-<?= $liquor_details[$i]->class ?> collapsed-card" style="margin-bottom: 1px !important; border-radius:0px !important;">
            <div class="card-header">
                <h6 class="">
                    <div class="row">
                        <div class="col-md-2"><img src="<?= base_url($liquor_details[$i]->liquor_image) ?>" width="80px"></div>
                        <div class="col-md-2" style="margin-top:20px">
                            <center><?= $liquor_details[$i]->liquor_name ?></center>
                        </div>
                        <div class="col-md-1" style="margin-top:20px">
                            <center><?= $liquor_details[$i]->liquor_type ?></center>
                        </div>
                        <div class="col-md-1" style="margin-top:20px">
                            <center><?= $liquor_details[$i]->liquor_ml ?></center>
                        </div>
                        <div class="col-md-1" style="margin-top:20px">
                            <center><?= $liquor_details[$i]->purchase_price ?></center>
                        </div>
                        <div class="col-md-1" style="margin-top:20px">
                            <center><?= $liquor_details[$i]->selling_price ?></center>
                        </div>
                        <div class="col-md-2" style="margin-top:20px">
                            <center><?= $liquor_details[$i]->available_quantity ?></center>
                        </div>
                        <div class="col-md-2" style="margin-top:20px">
                            <center><?= $liquor_details[$i]->actual_available_quantity ?></center>
                        </div>
                    </div>
                </h6>
            </div>
        </div>
    </div>
<?php } ?>