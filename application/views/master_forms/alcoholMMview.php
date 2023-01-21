
<!-- Author: SUJIT N. MISHRA
Created on:25/10/2021
Scope: Alcohol MM master view
Source:
-->
<?php

$resultArray = (isset($alcohol_quantity)) ? $alcohol_quantity : new stdClass;

?>
<section class="content">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-plus"></i>
                   <?= $title ?> </h3>
               </div>
               <div class="d-inline-block float-right">
                <a href="<?= base_url('master/Alcohol_masterAPI/liquorcapacitymaster'); ?>" class="btn btn-secondary"><i class="fa fa-list"></i>  <?= trans('alcohol_quantity_list') ?></a>
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
        </div>
        <div class="card-body">

            <!-- For Messages -->
            <?php $this->load->view('admin/includes/_messages.php');   ?>
            <?php
            $redirect_url = ($mode == 'A') ? 'master/Alcohol_masterAPI/addAlcoholQuantity' : 'master/Tax_masterAPI/editTaxNames/' . getValue('id', $resultArray); 
            echo form_open(base_url($redirect_url), 'class="form-horizontal"');  
            ?>
            <div class="row">

                <div class="col-6">
                    <?php $this->load->view('master/numeric_field', array("field_id" => "alcohol_quantity", "name"=>"alcohol_quantity", "label" => "alcohol_quantity", "max_length" => "11", "place_holder" => "Enter alcohol quantity","name"=> "alcohol_quantity", "value" => getValue('alcohol_quantity', $resultArray))); ?>
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
