<!-- Content Wrapper. Contains page content -->
<?php
$resultArray = (isset($brewery_data)) ? $brewery_data[0] : new stdClass;
?>
<!-- Main content -->
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: grey;
        border-color: black;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: red;
    }
</style>
<section class="content">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-plus"></i>
                    <?= $title ?> </h3>
            </div>
            <div class="d-inline-block float-right">
                <a href="<?= base_url('master/BreweryMaster'); ?>" class="btn btn-secondary"><i class="fa fa-list"></i> <?= trans('brewery_list') ?></a>
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
        </div>
        <div class="card-body">
            <!-- For Messages -->
            <?php // $this->load->view('admin/includes/_messages.php')   
            ?>
            <?php
            $redirect_url = ($mode == 'A') ? 'master/BreweryMaster/addBrewery' : 'master/BreweryMaster/editBrewery/' . getValue('id', $resultArray);
            echo form_open(base_url($redirect_url), array('class' => 'form-horizontal', 'id' => 'breweryregistrationfrm'), 'class="form-horizontal"');
            ?>
            <!--outlet and canteen name-->
            <div class="row">
                <div class="col-6">
                    <?php $this->load->view('master/alphabet_space_field', array("field_id" => "brewery_name", "label" => "brewery_name", "max_length" => "70", "place_holder" => "Enter Brewery Name", "value" => getValue('brewery_name', $resultArray))); ?>
                </div>
                <div class="col-6">
                    <?php $this->load->view('master/address_field', array("field_id" => "breweryaddress", "label" => "breweryaddress", "max_length" => "250", "place_holder" => "Enter Brewery Address", "value" => getValue('address', $resultArray))); ?>
                </div>
            </div>
            <!--state and city name-->
            <div class="row">
                <div class="col-6">
                    <?php $this->load->view('master/alphabet_space_field', array("field_id" => "contactperson", "label" => "contactperson", "max_length" => "70", "place_holder" => "Enter Contact Person Name", "value" => getValue('contact_person_name', $resultArray))); ?>
                </div>
                <div class="col-6">
                    <?php $this->load->view('master/numeric_field', array("field_id" => "mobilenumber", "label" => "mobilenumber", "max_length" => "10", "place_holder" => "Enter Mobile Number", "value" => getValue('mobile_no', $resultArray))); ?>
                </div>
            </div>
            <!--Address -->
            <div class="row">
                <div class="col-6">
                    <?php $this->load->view('master/email_field', array("field_id" => "emailaddress", "label" => "emailaddress", "max_length" => "70", "place_holder" => "Enter Email Address", "value" => getValue('mail_id', $resultArray))); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <input type="hidden" name="submit" value="submit" />
                    <button type="submit" Style="width: 100px;" id="breweryregistrationfrm" class=" btn btn-primary btn-md center-block"><?= trans('submit') ?></button> &nbsp; &nbsp;
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</section>
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = "<?php echo base_url(); ?>";
</script>
<!--</div>-->
<script src="<?= base_url() ?>assets/js/module/common/validation.js"></script>
<script src="<?= base_url() ?>assets/js/module/brewery/breweryaddnew.js"></script>