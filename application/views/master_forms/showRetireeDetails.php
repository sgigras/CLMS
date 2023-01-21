<?php $resultArray = $resultData[0];
// print_r($resultArray);
$force_array = array("BSF" => "Border Security Force (BSF)", "ITBP" => "Indo Tibetan Force");
$force = $force_array[$resultArray->capf_force];
?>
<div class="mb-0 card card-default">
    <div class="card-body">
        <form id="target">
            <!-- For Messages -->
            <div class="row">
                <div class="col-4">
                    <?php $this->load->view('master/numeric_field', array("field_id" => "irla_no", "label" => "irla_no", "max_length" => "10", "place_holder" => "Enter a irla no", "value" => "$resultArray->irla")); ?>
                </div>
                <div class="col-4">
                    <?php $this->load->view('master/numeric_field', array("field_id" => "ppo_no", "label" => "ppo_no", "max_length" => "70", "place_holder" => "", "value" => strtoupper($resultArray->ppo_no))); ?>
                </div>
                <div class="col-4">
                    <?php $this->load->view('master/alphabet_space_field', array("field_id" => "retiree_name", "label" => "name", "max_length" => "70", "place_holder" => "Enter a name", "value" => strtoupper($resultArray->name))); ?>
                </div>

            </div>
            <br>
            <div class="row">
                <div class="col-4">
                    <?php $this->load->view('master/numeric_field', array("field_id" => "mobile_no", "label" => "mobile_no", "max_length" => "10", "place_holder" => "Enter a mobile no", "value" => "$resultArray->mobile_no")); ?>
                </div>
                <div class="col-4">
                    <?php $this->load->view('master/email_field', array("field_id" => "email_id", "label" => "email_id", "max_length" => "70", "place_holder" => "Enter an email id", "value" => "$resultArray->email_id")); ?>
                </div>
                <div class="col-4">
                    <?php $this->load->view('master/alpha_numeric_field', array("field_id" => "adhaar_field", "label" => "adhaar_field", "place_holder" => "Select a Adhaar field", "value" => "$resultArray->adhaar_card")); ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-4">
                    <?php $this->load->view('master/alpha_numeric_field', array("field_id" => "date_of_birth", "label" => "date_of_birth",  "value" => "$resultArray->date_of_birth")); ?>
                </div>
                <div class="col-4">
                    <?php $this->load->view('master/alpha_numeric_field', array("field_id" => "date_of_joining", "label" => "date_of_joining", "value" => "$resultArray->joining_date")); ?>
                </div>
                <div class="col-4">
                    <?php $this->load->view('master/alpha_numeric_field', array("field_id" => "date_of_retireement", "label" => "date_of_retireement",  "value" => "$resultArray->retirement_date")); ?>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-4">
                    <?php
                    $this->load->view('master/alphabet_space_field', array("field_id" => "force_type", "label" => "force_type", "max_length" => "70", "place_holder" => "Enter a force", "value" => "$force"));
                    ?>
                </div>
                <div class="col-4">
                    <?php $this->load->view('master/alphabet_space_field', array("field_id" => "posting_unit_type", "label" => "posting_unit_type",  "max_length" => "70", "place_holder" => "Enter a posting unit", "value" => "$resultArray->posting_unit")); ?>
                </div>
                <div class="col-4">
                    <?php
                    $this->load->view('master/alphabet_space_field', array("field_id" => "rank", "label" => "rank",  "max_length" => "70", "place_holder" => "Enter a rank", "value" => "$resultArray->rank"));
                    ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <div class="col-12" style="padding-left:10px !important;">
                            <div class="form-group" style="justify-content: center">
                                <div class="photostyle">
                                    <?php $profile_img = $resultArray->user_photo; ?>
                                    <img src="<?= base_url() . $profile_img ?>" id="profile_image_img" class="photostyle" width="110" height="300px" alt="uploads/profilepics/snehaltalele/download.png">
                                </div>
                            </div>
                        </div>
                        <label for="profile_image" class="col-sm-12 control-label form-control">Personnel Photo</label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">


                        <div class="col-12" style="padding-left:10px !important;">
                            <div class="form-group" style="justify-content: center">
                                <div class="photostyle">
                                    <?php $card_img = $resultArray->signed_photo; ?>
                                    <img src="<?= base_url() . $card_img ?>" id="profile_signed_image_img" class="photostyle" width="110" height="300px" alt="uploads/profilepics/snehaltalele/download.png">
                                </div>
                            </div>
                        </div>
                        <label for="profile_image" class="col-sm-12 control-label form-control">Card Photo</label>
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-group">


                        <div class="col-12" style="padding-left:10px !important;">
                            <div class="form-group" style="justify-content: center">
                                <div class="photostyle">
                                    <?php $card_photo = $resultArray->card_photo; ?>
                                    <img src="<?= base_url() . $card_photo ?>" id="profile_signed_image_img" class="photostyle" width="110" height="300px" alt="uploads/profilepics/snehaltalele/download.png">
                                </div>
                            </div>
                        </div>
                        <label for="profile_image" class="col-sm-12 control-label form-control">Signed Form Photo</label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">


                        <div class="col-12" style="padding-left:10px !important;">
                            <div class="form-group" style="justify-content: center">
                                <div class="photostyle">
                                    <?php $ppo_photo = $resultArray->ppo_photo; ?>
                                    <img src="<?= base_url() . $ppo_photo ?>" id="profile_signed_image_img" class="photostyle" width="110" height="300px" alt="uploads/profilepics/snehaltalele/download.png">
                                </div>
                            </div>
                        </div>
                        <label for="profile_image" class="col-sm-12 control-label form-control">PPO Photo</label>
                    </div>
                </div>
                <!-- <div class="col-3">
                    <div class="form-group">


                        <div class="col-12" style="padding-left:10px !important;">
                            <div class="form-group" style="justify-content: center">
                                <div class="photostyle">
                                    <img src="<?= base_url() ?>/assets/dist/img/users.png" id="profile_image_img" class="photostyle" width="110" height="300px" alt="uploads/profilepics/snehaltalele/download.png">
                                </div>
                            </div>
                        </div>

                        <label for="profile_image" class="col-sm-12 control-label form-control">Signed Form upload</label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <div class="col-12" style="padding-left:10px !important;">
                            <div class="form-group" style="justify-content: center">
                                <div class="photostyle">
                                    <img src="<?= base_url() ?>/assets/dist/img/users.png" id="profile_signed_image_img" class="photostyle" width="110" height="300px" alt="uploads/profilepics/snehaltalele/download.png">
                                </div>
                            </div>
                        </div>
                        <label for="profile_image" class="col-sm-12 control-label form-control">PPO Photo</label>
                    </div>
                </div> -->
            </div>
            <!-- <br> -->
        </form>
        <div class="form-group">
            <div class="col-md-7">
                <button id="deny_retiree" class="btn btn-danger pull-right m-1">Deny</button>
            </div>
            <div class="col-md-12">
                <button id="approve_retiree" class="btn btn-success pull-right m-1">Approve</button>
            </div>

        </div>
    </div>

</div>