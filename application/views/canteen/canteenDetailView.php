<!-- Content Wrapper. Contains page content -->
<?php
$resultArray = (isset($canteen_club_data)) ? $canteen_club_data[0] : new stdClass;
$city_select_array = (isset($city_list)) ? $city_list : array();
$distributor_name_select_array = (isset($distributor_name_list)) ? $distributor_name_list : array();
?>
<!--<div class="content-wrapper" style="margin-top: 55px;min-height: 580.08px !important;">-->
<!-- Main content -->
<section class="content">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-plus"></i>
                    <?= $title ?> </h3>
            </div>
            <div class="d-inline-block float-right">
                <a href="<?= base_url('master/CanteenMaster'); ?>" class="btn btn-secondary"><i class="fa fa-list"></i> <?= trans('canteen_list') ?></a>
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
        </div>
        <div class="card-body">

            <!-- For Messages -->
            <?php // $this->load->view('admin/includes/_messages.php')    
            ?>

            <?php
            $redirect_url = ($mode == 'A') ? 'master/CanteenMaster/addCanteenClub' : 'master/CanteenMaster/editCanteenClub/0';
            echo form_open(base_url($redirect_url), array('class' => 'form-horizontal', 'id' => 'entity_details'), 'class="form-horizontal"');
            ?>
            <?php
            if ($mode != 'A') { ?>
                <input type="hidden" value="<?php echo getValue('id', $resultArray) ?>" name="entity_id" id="entity_id">
            <?php }
            ?>

            <!--outlet and canteen name-->
            <div id="sub_depot" class="row">
                <div class="col-6">
                    <?php
                    //                    $outlet_type_select_option_array = array(array("id" => "1", "outlet" => "Canteen"), array("id" => "2", "outlet" => "Club"));
                    $this->load->view('master/select_field', array("field_id" => "outlet_type", "for" => "outlet_type", "id" => "outlet_type", "label" => "type", "place_holder" => "Select an outlet", "option_record" => $outlet_type_select_option_array, "option_value" => "id", "option_text" => "entity_type", "selected_value" => getValue('entity_type', $resultArray)));
                    ?>
                </div>
                <div class="col-6">
                    <?php $this->load->view('master/select_field', array("field_id" => "battalion_unit", "for" => "battalion_unit", "id" => "battalion_unit", "label" => "unit_type", "place_holder" => "Select an Unit", "option_record" => $battalion_unit_select_option_array, "option_value" => "id", "option_text" => "posting_unit", "selected_value" => getValue('posting_unit', $resultArray))); ?>
                </div>
                <div class="col-6">
                    <?php $this->load->view('master/alpha_numeric_space_field', array("field_id" => "canteen_name", "id" => "canteen_name", "label" => "canteen_name", "max_length" => "70", "place_holder" => "Enter a name", "value" => getValue('entity_name', $resultArray))); ?>
                </div>
                <!--state and city name-->

                <div class="col-6">
                    <?php $this->load->view('master/select_field', array("field_id" => "select_state", "label" => "state", "place_holder" => "Select a state", "option_record" => $state_record, "option_value" => "id", "option_text" => "state", "selected_value" => getValue('state', $resultArray))); ?>
                </div>
                <div class="col-6">
                    <?php $this->load->view('master/select_field', array("field_id" => "select_city", "label" => "city", "place_holder" => "Select a city", "option_record" => $city_select_array, "option_value" => "id", "option_text" => "city_district_name", "selected_value" => getValue('city', $resultArray))); ?>
                </div>

                <!--Address & Chairman-->
                <div class="col-6">
                    <?php $this->load->view('master/text_area_field', array("field_id" => "address", "label" => "address", "max_length" => "70", "place_holder" => "", "value" => getValue('address', $resultArray))); ?>
                </div>
                <div class="col-6">
                    <?php $this->load->view('master/select_field', array("field_id" => "select_chairman", "CSS_CLASS" => "select_canteen_user", "label" => "chairman", "place_holder" => "Select a chairman", "option_record" => $user_details, "option_value" => "id", "option_text" => "name", "selected_value" => getValue('chairman', $resultArray))); ?>
                </div>
                <!--executive supervisor-->
                <div class="col-6">
                    <?php $this->load->view('master/select_field', array("field_id" => "select_executive", "CSS_CLASS" => "select_canteen_user", "label" => "executive", "place_holder" => "Select a executive", "option_record" => $user_details, "option_value" => "id", "option_text" => "name", "selected_value" => getValue('executive', $resultArray))); ?>
                </div>
                <div class="col-6">
                    <?php $this->load->view('master/select_field', array("field_id" => "select_supervisor", "CSS_CLASS" => "select_canteen_user", "label" => "supervisor", "place_holder" => "Select a supervisor", "option_record" => $user_details, "option_value" => "id", "option_text" => "name", "selected_value" => getValue('supervisor', $resultArray))); ?>
                </div>
                <!--distributor distributor name-->
                <div class="col-6">
                    <?php $this->load->view('master/select_field', array("field_id" => "select_distrubuting_authority", "label" => "distrubuting_authority", "place_holder" => "Select a distributor authority", "option_record" => $distributor_authority_record, "option_value" => "id", "type" => "hidden", "option_text" => "distributor_authority", "selected_value" => getValue('authorised_distributor', $resultArray))); ?>
                </div>
                <!-- <div class="col-6" id="distrubuting_authority" style="display: none;">
                    <?php // $this->load->view('master/select_field', array("field_id" => "select_distributor_name_1", "label" => "distributor_name", "place_holder" => "Select a distributor name", "option_record" => $distributor_name_select_array, "option_value" => "id", "option_text" => "name", "selected_value" => getValue('store_id', $resultArray))); ?>
                </div> -->
                <div class="col-6" id="distrubuting_authority_1" style="display: none;">
                    <?php $this->load->view('master/select_field', array("field_id" => "select_distributor_name", "label" => "distributor_name", "place_holder" => "Select a distributor name", "option_record" => $distributor_name_select_array, "option_value" => "id", "option_text" => "name", "selected_value" => getValue('store_id', $resultArray))); ?>
                </div> 
                <!-- <div class="col-6" id="liquor_details_">
                <?php $this->load->view('master/select_field', array("field_id" => "select_liquor_name", "label" => "liquor_name", "place_holder" => "Select a liquor name", "option_record" => $liquor_name_select_array, "option_value" => "id", "option_text" => "store_name", "selected_value" => getValue('store_id', $resultArray))); ?>
                </div> -->
            </div>
            <!-- <div class="row">
            </div>
            <div class="row">
            </div>
            <div class="row">
            </div>
            <div class="row">
            </div>
            <div class="row">
            </div> -->

            <div class="form-group">
                <div class="col-md-12">
                    <button id="addEntity" class="btn btn-primary pull-right"><?= $title ?></button>
                    <!--<in[>-->
                    <input type="hidden" name="submit" value="submit" />
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <!-- /.box-body -->
    </div>
</section>
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = "<?php echo base_url(); ?>";
</script>
<!--</div>-->
<script src="<?= base_url() ?>assets/js/module/common/validation.js"></script>
<script src="<?= base_url() ?>assets/js/module/canteen/canteenDetail.js"></script>