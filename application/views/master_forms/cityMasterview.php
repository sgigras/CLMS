<!-- Author: SUJIT N. MISHRA
Created on:23/10/2021
Scope: City master view
Source:
-->
<?php

$resultArray = (isset($city_district_name)) ? $city_district_name : new stdClass ;

?>
<section class="content">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-plus"></i>
                   <?= $title ?> </h3>
               </div>
               <div class="d-inline-block float-right">
                <a href="<?= base_url('master/City_masterAPI'); ?>" class="btn btn-secondary"><i class="fa fa-list"></i>  <?= trans('city_state_list') ?></a>
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
        </div>
        <div class="card-body">

            <!-- For Messages -->
            <?php $this->load->view('admin/includes/_messages.php');   ?>
            <?php
            $redirect_url = ($mode == 'A') ? 'master/City_masterAPI/addCityDetails' : 'master/City_masterAPI/editCityDetails/'  . getValue('id', $resultArray); 
            echo form_open(base_url($redirect_url), 'class="form-horizontal"');  
            ?>

            <div class="row">


                <div class="col-6">
                    <?php
                    $this->load->view('master/select_field', array("field_id" => "select_state", "label" => "select_state", "place_holder" => "Select a state", "option_record" => $state_record, "option_value" => "id", "option_text" => "state", "name"=>"select_state", "selected_value" => getValue('stateid', $resultArray))); ?>
                </div>
                
                <div class="col-6">
                    <?php $this->load->view('master/alphabet_space_field', array("field_id" => "city_name", "label" => "city_name", "max_length" => "50", "place_holder" => "Enter city name","name"=> "city_name", "value" => getValue('city_district_name', $resultArray))); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="submit" name="submit" value="<?= $title ?>" class="btn btn-primary pull-right">
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</section>
<script src="<?= base_url() ?>assets/js/module/common/validation.js"></script>
<script src="<?= base_url() ?>assets/js/module/master/city_master.js"></script>
