<?php

$resultArray = (isset($city_district_name)) ? $city_district_name : new stdClass;

?>
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = '<?= base_url() ?>';
</script>
<section class="content">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-plus"></i>
                    <?= $title ?> </h3>
            </div>
            <div class="d-inline-block float-right">
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
        </div>
        <div class="card-body">

            <!-- For Messages -->
            <?php $this->load->view('admin/includes/_messages.php');   ?>
            <?php
            // $redirect_url =  'user_details/RegisterRetiree/index';
            ?>
            <div class="row">
                <div class="col-6">
                    <?php $this->load->view('master/numeric_field', array("field_id" => "irla_no", "label" => "irla_no", "max_length" => "10", "place_holder" => "Enter a irla no", "value" => "")); ?>
                </div>
                <div class="col-6">
                    <?php $this->load->view('master/alphabet_space_field', array("field_id" => "retiree_name", "label" => "name", "max_length" => "70", "place_holder" => "Enter a name", "value" => "")); ?>
                </div>

            </div>
            <!-- <br> -->
            <div class="row">
                <div class="col-4">
                    <?php $this->load->view('master/numeric_field', array("field_id" => "mobile_no", "label" => "mobile_no", "max_length" => "10", "place_holder" => "Enter a mobile no", "value" => "")); ?>
                </div>
                <div class="col-4">
                    <?php $this->load->view('master/email_field', array("field_id" => "email_id", "label" => "email_id", "max_length" => "70", "place_holder" => "Enter an email id", "value" => "")); ?>
                </div>
                <div class="col-4">
                    <?php $this->load->view('master/date_field', array("field_id" => "date_of_birth", "label" => "date_of_birth", "place_holder" => "Select a date of birth", "value" => "")); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    <?php
                    $this->load->view('master/select_field', array("field_id" => "force_type", "label" => "force_type", "place_holder" => "Select a force", "option_record" => $force_select_option_array, "option_value" => "id", "option_text" => "force_details", "selected_value" => ''));
                    ?>
                </div>
                <div class="col-4">
                    <?php $this->load->view('master/select_field', array("field_id" => "posting_unit_type", "label" => "posting_unit_type", "place_holder" => "Select a posting unit", "option_record" => $posting_unit_select_option_array, "option_value" => "id", "option_text" => "posting_unit", "selected_value" => '')); ?>
                </div>
                <div class="col-4">
                    <?php
                    $this->load->view('master/select_field', array("field_id" => "rank", "label" => "rank", "place_holder" => "Select a force", "option_record" => $rank_select_option_array, "option_value" => "id", "option_text" => "rank", "selected_value" => ''));
                    ?>
                </div>
            </div>
            <!-- <br> -->

            <div class="form-group">
                <div class="col-md-12">
                    <button id="register_retiree" class="btn btn-primary pull-right">Register</button>
                </div>
            </div>
        </div>

    </div>
</section>
<script src="<?= base_url() ?>assets/js/module/common/validation.js"></script>
<script src="<?= base_url() ?>assets/js/module/registerRetireeDetails.js"></script>