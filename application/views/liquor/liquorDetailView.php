<!-- Content Wrapper. Contains page content -->
<?php
$resultArray = (isset($liquor_data)) ? $liquor_data[0] : new stdClass;
$liquor_select_array = (isset($liquor_list)) ? $liquor_list : array();
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
                <a href="<?= base_url('master/Alcohol_masterAPI/liquorspecificdetails'); ?>" class="btn btn-secondary"><i class="fa fa-list"></i>  <?= trans('liquor_list') ?></a>
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
        </div>
        <div class="card-body">
            <?php
            $redirect_url = ($mode == 'A') ? 'master/Alcohol_masterAPI/addLiquorDetails' : 'master/Alcohol_masterAPI/editLiquorDetails/' . getValue('id', $resultArray);
            echo form_open_multipart(base_url($redirect_url), array('class' => 'form-horizontal', 'id' => 'liquor_details_form'));
            if ($mode !== 'A') {
                $id = getValue('id', $resultArray);
                echo "<input type='hidden' name='edit_id' id='edit_id' value='$id' >";
            }

            if (null!=($this->session->flashdata('success'))) {
                $this->load->view('admin/includes/_messages.php');
              }
            ?>
            <div class="row">
                <div class='col-1'></div>
                <div class="col-11">
                    <div class="row">
                        <div class="col-4" style='padding-left:10px !important; border-right: 1px solid #34343434;'>
                            <?php $this->load->view('master/upload_image_block_field', array("field_id" => "liquor_image", "name"=>"liquor_image","css_class" => "photostyle", "width" => "300px", "height" => "400px", "image_title" => getValue('liquor_image', $resultArray))) ?>
                        </div>
                        <div class="col-6">
                            <div class='row'>
                                <div class='col'>
                                    <?php $this->load->view('master/select_field', array("field_id" => "brewery_name", "label" => "brewery_name", "place_holder" => "Select a Brewery", "option_record" => $brewery_data, "option_value" => "id", "option_text" => "brewery_name", "selected_value" => getValue('brewery_id', $resultArray))); ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col'>
                                    <?php $this->load->view('master/select_field', array("field_id" => "liquor_type", "label" => "liquor_type", "place_holder" => "Select a liquor type", "option_record" => $alcohol_type_record, "option_value" => "id", "option_text" => "alcohol_type", "selected_value" => getValue('liquor_type', $resultArray))); ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col'>
                                   <?php 
                                    $this->load->view('master/select_field', array("field_id" => "liquor_brand", "label" => "liquor_brand", "place_holder" => "Select a liquor brand", "option_record" => $alcohol_brand_record, "option_value" => "id", "option_text" => "liquor_brand", "selected_value" => getValue('liquor_brand', $resultArray))); ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col'>
                                    <?php $this->load->view('master/alphabet_space_field', array("field_id" => "liquor_description", "label" => "liquor_description", "max_length" => "70", "place_holder" => "Enter a liquor description, ex-mild", "value" => getValue('liquor_description', $resultArray))); ?>
                                </div>
                            </div>

                            <div class='row'>
                                <div class='col'>

                                    <?php 

                                    $this->load->view('master/select_field', array("field_id" => "bottle_size", "label" => "bottle_size", "place_holder" => "Select a bottle size", "option_record" => $bottle_size_record, "option_value" => "id", "option_text" => "bottle_size", "selected_value" => getValue('bottle_size', $resultArray))); ?>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col'>
                                    <?php 
                                    $this->load->view('master/select_field', array("field_id" => "bottle_vol", "label" => "bottle_vol", "place_holder" => "Select a bottle volume", "option_record" => $bottle_volume_record, "option_value" => "id", "option_text" => "bottle_volume", "selected_value" => getValue('bottle_volume', $resultArray))); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class='col'>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            &nbsp;&nbsp;<button name="sub" type="submit" value="submit" id="liquor_details" class="btn btn-primary" ><?= $title ?></button>
                                            <input type="hidden"  name="submit" value="true" />
                                            &nbsp;<p style="color:red;">Note:- Kindly add the liquor image before adding/editing.</p>
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
<script src="<?= base_url() ?>assets/js/module/liquor/liquorDetail.js"></script>