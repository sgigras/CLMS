<!-- Author: SUJIT N. MISHRA
Created on:23/10/2021
Scope: Tax master view
Source:
-->
<?php

$resultArray = (isset($tax_name)) ? $tax_name : new stdClass;

?>
<section class="content">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-plus"></i>
                    <?= $title ?> </h3>
            </div>
            <div class="d-inline-block float-right">
                <a href="<?= base_url('master/Tax_masterAPI'); ?>" class="btn btn-secondary"><i class="fa fa-list"></i> <?= trans('tax_list') ?></a>
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
        </div>
        <div class="card-body">

            <!-- For Messages -->
            <?php if($this->session->flashdata('form_data')){
                ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> <?php echo $this->session->flashdata('form_data'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    </div>
                <?php
            } ?>

            <?php $this->load->view('admin/includes/_messages.php');   ?>
            <?php
            $redirect_url = ($mode == 'A') ? 'master/Tax_masterAPI/addTaxNames' : 'master/Tax_masterAPI/editTaxNames/' . getValue('id', $resultArray);
            echo form_open(base_url($redirect_url), 'class="form-horizontal"');
            ?>
            <div class="row">

                <div class="col-6">
                    <?php $this->load->view('master/alpha_numeric_space_field', array("field_id" => "tax_name", "label" => "tax_name", "max_length" => "50", "place_holder" => "Enter tax name", "name" => "tax_name", "value" => getValue('tax_name', $resultArray))); ?>
                </div>
                <div class="col-6">
                    <?php $this->load->view('master/select_field', array("field_id" => "entity_type", "label" => "select_entity_type", "place_holder" => "Select Entity Type", "option_record" => $outlet_type_select_option_array, "option_value" => "id", "option_text" => "entity_type", "selected_value" => getValue('entity_type', $resultArray))); ?>
                </div>
                <div class="col-6">
                    <?php $this->load->view('master/select_field', array("field_id" => "tax_category", "label" => "select_tax_category", "place_holder" => "Select Tax Category", "option_record" => $outlet_type_select_option_array1, "option_value" => "id", "option_text" => "tax_category", "selected_value" => getValue('tax_category', $resultArray))); ?>
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
<script>
    $("#entity_type").select2({
        placeholder: "Select Entity Type"
    });
    $("#tax_category").select2({
        placeholder: "Select Tax Category"
    });
</script>
<script src="<?= base_url() ?>assets/js/module/common/validation.js"></script>
<script src="<?= base_url() ?>assets/js/module/common/citymaster.js"></script>