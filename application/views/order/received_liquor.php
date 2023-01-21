<section class="content p-0">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fas fa-shopping-cart"></i>
                    <?= trans($title) ?> </h3>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="form-group">
                    <div class="row">
                        <div class="col-4 col-lg-4">
                            <label> Order Code</label>
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control" onkeypress="return checkValidInputKeyPress(alphanumeric_regex_pattern);" id="order_code" placeholder="Order Code ex:MXGDHFJD">
                                <span class="input-group-append">
                                    <button type="button" class="btn btn-info" id="searchCartDetails"><i class="fa fa-search fa-sm" style="color: white;"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-lg-12">
                            <pre>
                            <?php
                            $entity_id = $this->session->userdata('entity_id');
                            // echo $entity_id;
                            ?>
                        </pre>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">

                        <div class="col-12 col-lg-12" id="hold_details">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer" >
            <div class="form-group" >
                <div class=row>
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                        <button class="btn btn-lg btn-outline-danger  mx-1" style="float:right" onclick="window.location.reload();"><i class="fa fa-refresh"></i>&nbsp;Reload</button>
                        <button id="receive_liquor" class="btn btn-lg btn-outline-primary mx-1" style="float:right"><i class="fa fa-list"></i>&nbsp;Receive</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</section>
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = "<?php echo base_url(); ?>";
</script>
<script src="<?= base_url() ?>assets/js/module/order/received_liquor.js"></script>