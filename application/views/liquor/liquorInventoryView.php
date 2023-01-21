<!-- Content Wrapper. Contains page content -->
<?php
$resultArray = (isset($liquor_data)) ? $liquor_data[0] : new stdClass;
//$liquor_data_array = (isset($liquor_data_list)) ? $liquor_data_list : array();
//$liquor_entity_array = (isset($liquor_entity_list)) ? $liquor_entity_list : array();

/* $distributor_name_select_array = (isset($distributor_name_list)) ? $distributor_name_list : array(); */
?>
<!--<div class="content-wrapper" style="margin-top: 55px;min-height: 580.08px !important;">-->
<!-- Main content -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
<section class="content">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title">
                    Liquor Quantity Updation </h3>
            </div>
        </div>
        <div class="card-body">

            <!-- For Messages -->
            <?php // $this->load->view('admin/includes/_messages.php')   
            ?>

            <?php
            $redirect_url = ($mode == 'A') ? 'master/Liquor_mapping/addLiquorMappingDetails' : 'master/Liquor_mapping/editLiquorMappingDetails/' . getValue('id', $resultArray);
            echo form_open(base_url($redirect_url), array('class' => 'form-horizontal', 'id' => 'liquor_mapping_details_form'), 'class="form-horizontal"');
            if ($mode !== 'A') {
                $id = getValue('id', $resultArray);
                echo "<input type='hidden' name='edit_id' id='edit_id' value='$id' >";
            }
            ?>
            <div class="row">
                <div class='col-1'></div>
                <div class="col-11">
                    <div class="row">
<!--                        <div class="col-6">
                            <div class="img bg-wrap text-center py-4">
                                <div class="user-logo">
                                    <div class="img" id="holdLiquorImage" style="background-image: url(images/logo.jpg);"></div>
                                </div>
                            </div>
                        </div>-->
                        <div class="col-6">
                            <div class='row'>
                                <div class='col'>
                                    <?php $this->load->view('master/select_field', array("field_id" => "liquor_data", "label" => "liquor_data", "place_holder" => "Select a liquor", "option_record" => $liquor_data, "option_value" => "id", "option_text" => "liquor_data", "selected_value" => '')); ?>
                                </div>
                            </div>
<!--                            <div class='row'>
                                <div class='col'>
                                    <?php // $this->load->view('master/select_field', array("field_id" => "liquor_brand", "label" => "liquor_brand", "place_holder" => "Select liquor brand", "option_record" => $liquor_data_array, "option_value" => "id", "option_text" => "liquor_name", "selected_value" => getValue('liquor_name', $resultArray))); ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col'>
                                    <?php // $this->load->view('master/select_field', array("field_id" => "entity_type", "label" => "entity_type", "place_holder" => "Select an entity type", "option_record" => $entity_type_record, "option_value" => "id", "option_text" => "entity_type", "selected_value" => getValue('entity_type', $resultArray))); ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col'>
                                    <?php // $this->load->view('master/select_field', array("field_id" => "entity", "label" => "entity", "place_holder" => "Select an entity", "option_record" => $liquor_entity_array, "option_value" => "id", "option_text" => "entity_name", "selected_value" => getValue('entity_name', $resultArray))); ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col'>
                                    <?php // $this->load->view('master/select_field', array("field_id" => "select_ml", "label" => "select_ml", "place_holder" => "Select ml", "option_record" => $ml_record, "option_value" => "id", "option_text" => "liquor_ml", "selected_value" => getValue('liquor_ml', $resultArray))); ?>
                                </div>
                            </div>
                            <div>
                            <div class='row'>
                                <div class='col'>
                                    <?php // $this->load->view('master/numeric_field', array("field_id" => "moq", "label" => "moq", "max_length" => "3", "place_holder" => "Minimum Order Quantity", "value" => getValue('liquor_name', $resultArray))); ?>
                                </div>
                            </div>-->
                            <div class='row'>
                                <div class='col-sm-5'>
                                    <?php $this->load->view('master/numeric_field', array("field_id" => "previous_avl_qty", "label" => "previous_avl_qty", "max_length" => "5", "place_holder" => "Previous available quantity", "value" => '')); ?>
                                </div>
                                <div class='col-sm-7'>
                                    <?php $this->load->view('master/numeric_field', array("field_id" => "current_avl_qty", "label" => "current_avl_qty", "max_length" => "5", "place_holder" => "Current available quantity", "value" => '')); ?>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class='col'>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button id="liquor_mapping_details" class="btn btn-primary">Submit</button>
                                            <input type="hidden" name="submit" value="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            </div>
                        </div>
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
<script src="<?= base_url() ?>assets/js/module/liquor/liquorInventoryUpdation.js"></script>