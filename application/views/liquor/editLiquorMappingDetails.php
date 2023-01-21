<div class="card mb-0" style="margin-bottom:0px !important">
    <div class="card-body mb-0">
        <div class="row px-0">
            <div class="col-12">
                <div class="row">
                    <div class="col-5">
                        <img src="<?= base_url() . $liquor_data[0]->liquor_image; ?>" width="350">
                    </div>
                    <div class="col-7">
                        <div class='row'>
                            <div class='col'>
                                <?php $this->load->view('master/display_data_label_field', array("field_id" => "liquor_type", "label" => "liquor_type", "max_length" => "", "place_holder" => "", "value" => $liquor_data[0]->liquor)); ?>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col'>
                                <?php $this->load->view('master/display_data_label_field', array("field_id" => "select_ml", "label" => "select_ml", "max_length" => "", "place_holder" => "", "value" => $liquor_data[0]->liquor_ml)); ?>
                            </div>
                        </div>
                        <div>
                            <div class='row'>
                                <div class='col'>
                                    <?php $this->load->view('master/numeric_field', array("field_id" => "moq", "label" => "moq", "max_length" => "3", "place_holder" => "Minimum Order Quantity", "value" => getValue('minimum_order_quantity', $liquor_data[0]))); ?>
                                </div>
                            </div>
                            <div class='row'>
                            <div class='col-md-4'>
                                    <?php $this->load->view('master/display_data_label_field', array("field_id" => "base_price_display", "label" => "base_price", "max_length" => "5", "place_holder" => "Base Price", "value" => getValue('base_price', $liquor_data[0]))); ?>
                                </div>
                                <div class='col-md-4'>
                                    <?php $this->load->view('master/display_data_label_field', array("field_id" => "purchase_price_display", "label" => "purchase_price", "max_length" => "5", "place_holder" => "Sell Price", "value" => getValue('purchase_price', $liquor_data[0]))); ?>
                                </div>
                                <div class='col-md-4'>
                                    <input type="hidden" name="sell_price" id="sell_price" value="<?= $liquor_data[0]->selling_price ?>">
                                    <?php $this->load->view('master/display_data_label_field', array("field_id" => "sell_price_display", "label" => "sell_price", "max_length" => "5", "place_holder" => "Sell Price", "value" => getValue('selling_price', $liquor_data[0]))); ?>
                                </div>
                            </div>
                            <div class="row">
                                <?php
                                if ($page_mode == "LIQUOR_STOCK_MAPPING_LIST") { ?>
                                    <div class='col'>
                                        <?php $this->load->view('master/numeric_field', array("field_id" => "physical_quantity_display", "label" => "current_physical_stock", "max_length" => "5", "place_holder" => "Physical Quantity", "value" => getValue('actual_available_quantity', $liquor_data[0]))); ?>
                                    </div>
                                    <div class='col'>
                                        <?php $this->load->view('master/numeric_field', array("field_id" => "new_stock", "label" => "new_stock", "max_length" => "5", "place_holder" => "Physical Quantity", "value" => 0)); ?>
                                    </div>
                                    <div class='col'>
                                        <?php $this->load->view('master/numeric_field', array("field_id" => "physical_quantity", "label" => "total_Stock", "max_length" => "5", "place_holder" => "Total Stock", "value" => getValue('actual_available_quantity', $liquor_data[0]))); ?>
                                    </div>
                                <?php  } else { ?>
                                    <div class='col'>
                                        <?php
                                        $this->load->view('master/numeric_field', array("field_id" => "available_quantity", "label" => "available_quantity", "max_length" => "5", "place_holder" => "Available Quantity", "value" => getValue('available_quantity', $liquor_data[0])));
                                        ?>
                                    </div>
                                    <div class='col'>
                                        <?php $this->load->view('master/numeric_field', array("field_id" => "reorder_level", "label" => "reorder_level", "max_length" => "5", "place_holder" => "Reorder Level", "value" => getValue('reorder_level', $liquor_data[0]))); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>