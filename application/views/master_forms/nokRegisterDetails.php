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
                    Register NOK </h3>
            </div>
            <div class="d-inline-block float-right">
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
        </div>
        <form action="/action_page_binary.asp" method="post" enctype="multipart/form-data">
            <div class="card-body">

                <!-- For Messages -->
                <?php $this->load->view('admin/includes/_messages.php');   ?>
                <?php
                // $redirect_url =  'user_details/RegisterRetiree/index';
                ?>
                <div class="card">
                    <div class="card-header bg-info">
                        <h5 class="text-center text-white">Personnel Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <?php
                                $this->load->view('master/select_field', array("field_id" => "personnel_no", "label" => "personnel_no", "place_holder" => "Select Personnel No.", "option_record" => array(), "option_value" => "id", "option_text" => "personnel_no", "selected_value" => ''));
                                ?>
                            </div>
                            <div class="col-6">
                                <?php $this->load->view('master/alphabet_space_field', array("field_id" => "retiree_name", "label" => "name", "max_length" => "50", "place_holder" => "Enter a name", "value" => "")); ?>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-4">
                                <?php
                                $this->load->view('master/select_field', array("field_id" => "force_type", "label" => "force_type", "place_holder" => "Select a force", "option_record" => $force_select_option_array, "option_value" => "force_code", "option_text" => "force_details", "selected_value" => ''));
                                ?>
                            </div>
                            <div class="col-4">
                                <?php $this->load->view('master/select_field', array("field_id" => "posting_unit_type", "label" => "posting_unit_type", "place_holder" => "Select a posting unit", "option_record" => $posting_unit_select_option_array, "option_value" => "id", "option_text" => "posting_unit", "selected_value" => '')); ?>
                            </div>
                            <div class="col-4">
                                <?php $this->load->view('master/alphabet_space_field', array("field_id" => "rank", "label" => "rank", "max_length" => "10", "value" => "NOK")); ?>
                            </div>
                        </div>
                        <!-- <br> -->
                        <div class="row">
                            <!-- <div class="col-4">
                        <?php $this->load->view('master/numeric_field', array("field_id" => "mobile_no", "label" => "mobile_no", "max_length" => "10", "place_holder" => "Enter a mobile no", "value" => "")); ?>
                    </div>
                    <div class="col-4">
                        <?php $this->load->view('master/email_field', array("field_id" => "email_id", "label" => "email_id", "max_length" => "70", "place_holder" => "Enter an email id", "value" => "")); ?>
                    </div> -->
                            <div class="col-4">
                                <?php $this->load->view('master/date_field', array("field_id" => "date_of_birth", "label" => "date_of_birth", "place_holder" => "Select a date of birth", "value" => "")); ?>
                                <span id="date_of_birth_error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="card" style="margin-top: -20px;">
                    <div class="card-header bg-info">
                        <h5 class="text-center text-white">NOK Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <!-- <?php $this->load->view('master/alphabet_space_field', array("field_id" => "retiree_name", "label" => "name", "max_length" => "50", "place_holder" => "Enter a name", "value" => "")); ?> -->
                                <?php $this->load->view('master/alphabet_space_field', array("field_id" => "nok_name", "label" => "name", "max_length" => "50", "place_holder" => "Enter a NOK name", "value" => "")); ?>
                            </div>
                            <div class="col-4">
                                <?php $this->load->view('master/numeric_field', array("field_id" => "mobile_no", "label" => "mobile_no", "max_length" => "10", "place_holder" => "Enter a mobile no", "value" => "")); ?>
                            </div>
                            <div class="col-4">
                                <?php $this->load->view('master/numeric_field', array("field_id" => "aadhar_card_no", "label" => "aadhar_card_no", "max_length" => "12", "place_holder" => "Enter Aadhar Card No.", "value" => "")); ?>
                            </div>

                        </div>
                        <!-- <div class="row">
                            <div class="col-4">
                                <?php $this->load->view('master/date_field', array("field_id" => "joining_date", "label" => "joining_date", "place_holder" => "Select Joining Date", "value" => "")); ?>
                            </div>
                            <div class="col-4">
                                <?php $this->load->view('master/date_field', array("field_id" => "retirement_date", "label" => "retirement_date", "place_holder" => "Select Retirement Date", "value" => "")); ?>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-4">
                                <?php $this->load->view('master/email_field', array("field_id" => "email_id", "label" => "email_id", "max_length" => "70", "place_holder" => "Enter an email id", "value" => "")); ?>
                            </div>
                            <div class="col-4">
                                <?php $this->load->view('master/select_field', array("field_id" => "relationship", "label" => "relationship_type", "place_holder" => "Select a relationship", "option_record" => $relationship_select_option_array, "option_value" => "relationship_type", "option_text" => "relationship_type", "selected_value" => '')); ?>   
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3">
                                <div class="card" style="height: 295px;">
                                    <div class="card-header" style="padding-bottom:3px; background-color:#7ac2c4;">
                                        <h5 class="text-center text-white">NOK Photo</h5>
                                    </div>
                                    <div class="card-body">
                                        <?php $this->load->view('master/photo_upload_field', array("field_id" => "personnel_photo", "name" => "personnel_photo", "css_class" => "photostyle", "width" => "150px", "height" => "150px", "image_title" => "")) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card" style="height: 295px;">
                                    <div class="card-header" style="padding-bottom:3px; background-color:#7ac2c4;">
                                        <h5 class="text-center text-white">Aadhar Card Photo</h5>
                                    </div>
                                    <div class="card-body" style="height: 295px;">
                                        <?php $this->load->view('master/photo_upload_field', array("field_id" => "aadhar_card_photo", "name" => "aadhar_card_photo", "css_class" => "photostyle", "width" => "150px", "height" => "150px", "image_title" => "")) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card" style="height: 295px;">
                                    <div class="card-header" style="padding-bottom:3px; background-color:#7ac2c4;">
                                        <h5 class="text-center text-white">ID Card Photo</h5>
                                    </div>
                                    <div class="card-body">
                                        <?php $this->load->view('master/photo_upload_field', array("field_id" => "id_card_photo", "name" => "id_card_photo", "css_class" => "photostyle", "width" => "150px", "height" => "150px", "image_title" => "")) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card" style="height: 295px">
                                    <div class="card-header" style="padding-bottom:3px; background-color:#7ac2c4;">
                                        <h5 class="text-center text-white">Signed Form Photo</h5>
                                    </div>
                                    <div class="card-body">
                                        <?php $this->load->view('master/photo_upload_field', array("field_id" => "signed_form_photo", "name" => "signed_form_photo", "css_class" => "photostyle", "width" => "150px", "height" => "150px", "image_title" => "")) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <button id="register_retiree" class="btn btn-primary pull-right">Register</button>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- <br> -->


            </div>
        </form>
    </div>
</section>
<script src="<?= base_url() ?>assets/js/module/common/validation.js"></script>
<script src="<?= base_url() ?>assets/js/module/nokRegisterDetails.js"></script>