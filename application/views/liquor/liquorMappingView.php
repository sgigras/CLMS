<!-- Content Wrapper. Contains page content -->
<?php
$resultArray = (isset($liquor_data)) ? $liquor_data[0] : new stdClass;
$liquor_data_array = (isset($liquor_data_list)) ? $liquor_data_list : array();
$liquor_entity_array = (isset($liquor_entity_list)) ? $liquor_entity_list : array();

/* $distributor_name_select_array = (isset($distributor_name_list)) ? $distributor_name_list : array(); */
?>
<!--<div class="content-wrapper" style="margin-top: 55px;min-height: 580.08px !important;">-->
<!-- Main content -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
<section class="content">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-plus"></i>
                    Liquor Mapping </h3>
            </div>
            <div class="d-inline-block float-right">
                <a href="<?= base_url('master/Liquor_mapping'); ?>" class="btn btn-secondary"><i class="fa fa-list"></i> <?= trans('liquor_list') ?></a>
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
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
                <div class="col-10">
                    <div class="row">
                        <div class="col-6">
                            <div class="img bg-wrap text-center py-4">
                                <div class="user-logo">
                                    <div class="img" id="holdLiquorImage" style="background-image: url(images/logo.jpg);"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class='row'>
                                <div class='col'>
                                    <?php $this->load->view('master/select_field', array("field_id" => "liquor_type", "label" => "liquor_type", "place_holder" => "Select a liquor type", "option_record" => $alcohol_type_record, "option_value" => "id", "option_text" => "liquor_type", "selected_value" => getValue('liquor_type', $resultArray))); ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col'>
                                    <?php $this->load->view('master/select_field', array("field_id" => "liquor_brand", "label" => "liquor_brand", "place_holder" => "Select liquor brand", "option_record" => $liquor_data_array, "option_value" => "id", "option_text" => "liquor_name", "selected_value" => getValue('liquor_name', $resultArray))); ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col'>
                                    <?php $entity_type_id = $entity_type_record[0]->entity_type ?>
                                    <input type="hidden" name="entity_type" id="entity_type" value="<?= $entity_type_id ?>">
                                    <?php // $this->load->view('master/select_field', array("field_id" => "entity_type", "label" => "entity_type", "place_holder" => "Select an entity type", "option_record" => $entity_type_record, "option_value" => "id", "option_text" => "entity_type", "selected_value" => getValue('entity_type', $resultArray))); 
                                    ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col'>
                                    <?php $entity_id = $this->session->userdata('entity_id') ?>
                                    <input type="hidden" name="entity" id="entity" value="<?= $entity_id ?>">
                                    <?php // $this->load->view('master/select_field', array("field_id" => "entity", "label" => "entity", "place_holder" => "Select an entity", "option_record" => $liquor_entity_array, "option_value" => "id", "option_text" => "entity_name", "selected_value" => getValue('entity_name', $resultArray))); 
                                    ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col'>
                                    <?php $this->load->view('master/select_field', array("field_id" => "select_ml", "label" => "select_ml", "place_holder" => "Select ml", "option_record" => $ml_record, "option_value" => "id", "option_text" => "liquor_ml", "selected_value" => getValue('liquor_ml', $resultArray))); ?>
                                </div>
                            </div>
                            <div>
                                <div class='row'>
                                    <div class='col'>
                                        <?php $this->load->view('master/numeric_field', array("field_id" => "moq", "label" => "moq", "max_length" => "3", "place_holder" => "Minimum Order Quantity", "value" => getValue('liquor_name', $resultArray))); ?>
                                    </div>
                                </div>
                                <div class='row'>
                                    <!-- <div class='col-sm-4' id="base_price_div">
                                         <input type="text" name="price[]" class="form-control calcEvent price" id="price" placeholder="" required> 
                                        <?php // $this->load->view('master/price_field', array("field_id" => "base_price", "label" => "base_price", "max_length" => "8", "place_holder" => "Base Price", "value" => ""));
                                        ?>
                                    </div> -->
                                    <div class='col-sm-4'>
                                        <!-- <input type="hidden" name="sell_price" id="sell_price" value=""> -->

                                        <?php $this->load->view('master/price_field', array("field_id" => "purchase_price", "label" => "purchase_price", "max_length" => "8", "place_holder" => "Purchase Price", "value" => "")) ?>
                                        <!-- <input type="hidden" name="hid_purchase_price" id="hid_purchase_price"> -->
                                        <?php // $this->load->view('master/display_data_label_field', array("field_id" => "sell_price_display", "label" => "sell_price", "max_length" => "5", "place_holder" => "Sell Price", "value" => "0")); 
                                        ?>
                                    </div>
                                    <div class='col-sm-4'>
                                        <?php $this->load->view('master/price_field', array("field_id" => "sell_price", "label" => "sell_price", "max_length" => "8", "place_holder" => "Sell Price", "value" => "")); ?>
                                        <input type="hidden" name="hid_sell_price" id="hid_sell_price">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class='col'>
                                        <?php $this->load->view('master/numeric_field', array("field_id" => "available_quantity", "label" => "available_quantity", "max_length" => "5", "place_holder" => "Available Quantity", "value" => getValue('liquor_name', $resultArray))); ?>
                                    </div>
                                    <div class='col'>
                                        <?php $this->load->view('master/numeric_field', array("field_id" => "reorder_level", "label" => "reorder_level", "max_length" => "5", "place_holder" => "Reorder Level", "value" => getValue('liquor_name', $resultArray))); ?>
                                    </div>
                                    <div><input type="hidden" id="tax_category"></div>
                                    <div><input type="hidden" id="tax_category1"></div>
                                    <div><input type="hidden" id="tax_category2"></div>
                                    <div><input type="hidden" id="tax_category3"></div>
                                    <div><input type="hidden" id="tax_category4"></div>
                                    <div><input type="hidden" id="tax_category5"></div>
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
                <div class='col-1'></div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <div id="myModal" class="modal fade" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-lg" style="max-width: 60%;">

                <!-- Modal content-->
                <div class="modal-content">
                    <!-- <div class="modal-header">
                        <h4 class="modal-title" id="modalHeader"></h4>
                    </div> -->
                    <div class="modal-body p-0">
                        <table id="liquortable" class="table table-hover table-bordered mb-0" style="border-collapse: collapse !important; border: none !important;" width="100%">
                            <thead style="background-color: darkorange;">
                                <tr>
                                    <th colspan=3>
                                        Liquor Details
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td rowspan="10"><img id="liquor_display_image" height="400px" width="300px"></td>
                                    <!-- <td id="liquor_display_type"></td> -->
                                </tr>
                                <tr>
                                    <td>Liquor Type</td>
                                    <td id="liquor_display_type"></td>
                                </tr>
                                <tr>
                                    <td>Liquor Brand</td>
                                    <td id="liquor_display_brand"></td>
                                </tr>
                                <tr>
                                    <td>ML</td>
                                    <td id="liquor_display_ml"></td>
                                </tr>
                                <tr>
                                    <td>Lot Size</td>
                                    <td id="liquor_display_lot_size"></td>
                                </tr>
                                <tr id="baseprice_tr">
                                    <td>Base Price</td>
                                    <td id="liquor_display_base_price"></td>
                                </tr>
                                <tr>
                                    <td>Purchase Price</td>
                                    <td id="liquor_display_purchase"></td>
                                </tr>
                                <tr>
                                    <td>Sell Price</td>
                                    <td id="liquor_display_sell"></td>
                                </tr>
                                <tr>
                                    <td>Available Quantity</td>
                                    <td id="liquor_display_available_quantity"></td>
                                </tr>
                                <tr>
                                    <td>Re-Order Level</td>
                                    <td id="liquor_display_reorder_level"></td>
                                </tr>
                                <tr>
                                    <!-- <td>Re-Order Level</td> -->
                                    <!-- <td colspan="3"></td> -->
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" onclick="submitDetails()">Confirm</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
</section>
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = "<?php echo base_url(); ?>";
</script>
<!--</div>-->
<script src="<?= base_url() ?>assets/js/module/liquor/liquorMapping.js"></script>