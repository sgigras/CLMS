<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/select2/select2.min.css">
<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper" style="margin-top: 55px;"> -->
    <!-- Main content -->
    <section class="content">
        <div class="card card-default color-palette-bo">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"> <i class="fa fa-plus"></i>
                        &nbsp; <?= $title ?> </h3>
                </div>
                <div class="d-inline-block float-right">
                    <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-secondary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <!-- form start -->
                            <div class="box-body">
                                <!-- For Messages -->
                                <?php $this->load->view('admin/includes/_messages.php')?>
                                <div class="row">
                                    <div class="col-md-10 mx-auto">
                                        <?php echo form_open(base_url('admin/brewery/Brewery/add'),  array("id" => "breweryregistrationfrm", "class" => "form-horizontal"));?>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="breweryname">Brewery name</label>
                                                <input type="text" class="form-control" id="breweryname" name="breweryname" placeholder="Brewery Name">
                                                <small style="color:red;" id="breweryname_error"></small>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="breweryaddress">Brewery Address</label>
                                                <input type="text" class="form-control" id="breweryaddress" name="breweryaddress" placeholder="Brewery Address">
                                                <small style="color:red;" id="breweryaddress_error"></small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="contactperson">Contact Person Name</label>
                                                <input type="text" class="form-control" id="contactperson" name="contactperson" placeholder="Contact Person Name">
                                                <small style="color:red;" id="contactperson_error"></small>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="mobilenumber">Mobile Number</label>
                                                <input type="number" class="form-control" id="mobilenumber" name="mobilenumber" placeholder="Mobile Number">
                                                <small style="color:red;" id="mobilenumber_error"></small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="emailaddress">Email Address</label>
                                                <input type="text" class="form-control" id="emailaddress" name="emailaddress" placeholder="Email Address">
                                                <small style="color:red;" id="emailaddress_error"></small>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="selectstate">State</label>
                                                <!-- <input type="text" class="form-control" id="inputState" placeholder="State"> -->
                                                <select name="brewerystate[]" id="brewerystate" class="form-control select2" multiple="multiple" data-placeholder="Select a State" style="width: 100%;">
                                                    <!-- <option selected value=""></option> -->
                                                    <?php foreach ($stateslist as $stateslist) :?>
                                                        <option value="<?php echo $stateslist['id']?>"><?php echo $stateslist['state']?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                <small style="color:red;" id="brewerystate_error"></small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="servingentity">Serving Entity(Club/Canteen/Stockist)</label>
                                                <select name="breweryentity[]" id="breweryentity" class="form-control select2" multiple="multiple" data-placeholder="Select An Entity" style="width: 100%;">
                                                    <!-- <option selected value=""></option> -->
                                                    <?php foreach ($entities as $entities) :?>
                                                        <option></option>
                                                        <option value="<?php echo $entities['id']?>"><?php echo $entities['entity_name']?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                <small style="color:red;" id="breweryentity_error"></small>
                                            </div>
                                        </div>
                                        <div class="form-group row">

                                        <!-- <div class="box-footer"> -->
                                   
                                            <div class="col-sm-12 text-center">
                                                <input type="hidden" name="submit" value="submit" />
                                                <button type="submit" Style="width: 100px;" class="btn btn-primary btn-md center-block"><?= trans('submit')?></button>
                                                <button id="btnClear" class="btn btn-danger btn-md center-block" Style="width: 100px;" OnClick="btnClear_Click">Reset</button>
                                            </div>
                                        <!-- </div> -->
                                                    </div>
                                        <?php echo form_close();?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    </section>
</div>
<!-- Select2 -->
<script src="<?= base_url()?>assets/plugins/select2/select2.full.min.js"></script>
<script src="<?= base_url()?>assets/js/module/brewery/breweryadd.js"></script>
