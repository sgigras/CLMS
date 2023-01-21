<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/select2/select2.min.css">
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
<style>
    .pretty.p-svg .state .svg {
        position: absolute;
        font-size: 1em;
        width: calc(1em + 2px);
        height: calc(1em + 2px);
        left: 0;
        z-index: 1;
        text-align: center;
        line-height: normal;
        top: calc((13% - (95% - 1em)) - 8%) !important;
        border: 1px solid transparent;
        opacity: 0;
    }
    .card {
        margin-bottom: 1px !important;
    }
</style>
<!-- <div class="content-wrapper"> -->
<!-- Main content -->
<section class="content">
    <div class="card card-default color-palette-bo">
        <div class="card-header">
            <div class="d-inline-block">
                <h1 class="card-title"> <i class="fa fa-university"></i>
                    &nbsp; Tax Mapping </h1>
            </div>
        </div>
        <div class="card-body">

            <?php $this->load->view('admin/includes/_messages.php') ?>

            <?php echo form_open(base_url('admin/tax/Tax/tax_Mapping'), 'class="form-horizontal"') ?>
            <!-- <div class="form-group">
                <label for="taxes" class="col-lg-2 control-label">Tax Name</label>

                <div class="col-md-6">
                    <select name="taxname" id="taxname" class="form-control select2" data-placeholder="Select Tax"
                        style="width: 100%;">
                        <option></option>
                        <?php foreach ($taxlist as $taxlist) : ?>
                        <option value="<?php echo $taxlist['id'] ?>"><?php echo $taxlist['tax_name'] ?></option>
                        <?php endforeach; ?>
                        
                    </select>
                    <small id="taxname_error"></small>
                </div>
            </div> -->
            <!-- <hr> -->
            <div class="form-group">
                <label for="states" class="col-lg-4 control-label">List Of Liquor Brands</label>

                <div class="col-md-6">
                    <select name="liquorlist" id="liquorlist" class="form-control select2" data-placeholder="Select Liquor Brand" style="width: 100%;">
                        <option></option>
                        <?php foreach ($liquorlist as $liquorlist) : ?>
                            <option value="<?php echo $liquorlist['id'] ?>"><?php echo $liquorlist['liquor_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small id="liquorlist_error"></small>
                </div>
            </div>
            
            <div id="collapsible_container">

            </div>
            <!-- <div id="tax_category" class="taxcategory"><button type="button" hidden></div> -->
            <br><br>
            <center>
                <div class="d-inline-block">

                    <!-- <button type="button" class="btn submitbtnstyle" id="submitstates">Submit</button> &nbsp; &nbsp; -->
                    <!-- <button class="btn backbtnstyle" id="back">Back</button> -->

                </div>
            </center>
            <div>
                <?php echo form_close(); ?>
            </div>
            <!-- /.box-body -->
        </div>
</section>
</div>
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = "<?php echo base_url(); ?>";
</script>
<script src="<?= base_url() ?>assets/plugins/select2/select2.full.min.js"></script>
<script src="<?= base_url() ?>assets/js/module/tax/taxstatemapping.js"></script>