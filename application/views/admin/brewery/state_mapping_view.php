<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/select2/select2.min.css">
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
<style>
    .pretty.p-svg .state .svg {
        position: absolute;
        font-size: 1em;
        width: calc(1em + 2px);
        height: calc(1em + 2px);
        left: 0;
        z-index: 1;
        text-align: center;
        line-height: normal;
        top: calc((13% - (95% - 1em)) - 8%) !important;
        border: 1px solid transparent;
        opacity: 0;
    }
    .green {
       color: #000066; 
    }
</style>
<!-- <div class="content-wrapper"> -->
    <!-- Main content -->
    <section class="content">
        <div class="card card-default color-palette-bo">
            <div class="card-header">
                <div class="d-inline-block">
                    <h1 class="card-title"> <i class="fa fa-university"></i>
                        &nbsp; Brand Mapping With Depot</h1>
                </div>
            </div>
            <div class="card-body">
                <table style="width:100%">
                <tr>
                <th for="brewery" style="text-align:center">Depot Name</th>
                       <td style="width: 25%;"> <select name="breweryname" id="breweryname" class="form-control select2" data-placeholder="Select Depot">
                            <option></option>
                            <?php foreach ($depotlist as $depotlist) : ?>
                                <option value="<?php echo $depotlist['id'] ?>"><?php echo $depotlist['entity_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <!-- <small style="color:red;" id="breweryname_error"></small></td>

                        <th for="brewery" style="text-align:center">Brewery Name</th>
                       <th style="width: 25%;"> 
                        <select name="selectbrewery" id="selectbrewery" class="form-control select2" data-placeholder="Select Brewery">
                            <option></option>
                            <?php // foreach ($selectbrewery as $selectbrewery) : ?>
                                <option value="<?php //echo $selectbrewery['id'] ?>"><?php //echo $selectbrewery['brewery_name'] ?></option>
                            <?php //endforeach; ?>
                        </select> -->
                        <small style="color:red;" id="breweryname_error"></small></th>

                       <th for="liquor" style="text-align:left">&nbsp; Search Liquor</th>
                       <th><input class="form-control" id="searchliquor" type="text" placeholder="Search."></th>
                         <?php $this->load->view('admin/includes/_messages.php') ?>
                             <?php echo form_open(base_url('admin/brewery/Brewery/stateMapping'), 'class="form-horizontal"') ?>
                            
                             </tr>
                        </table>
                    
                <hr>
                <div class="form-group">
                   <b> <label for="state" class="col-lg-2 control-label">Select Brand</label></b>
                </div>
                <div id="liquorbranddiv">
                </div>
                <br></br>
                <center>
                    <div class="d-inline-block">
                        <button type="button" class="btn submitbtnstyle" id="submitbranddetails">Submit</button> &nbsp; &nbsp;
                        <button class="btn backbtnstyle" id="back">Reset</button>
                    </div>
                </center>
                <div>
                    <?php echo form_close(); ?>
                </div>
              </tr>
            </div>
    </section>
</div>
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = "<?php echo base_url(); ?>";
</script>
<script src="<?= base_url() ?>assets/plugins/select2/select2.full.min.js"></script>
<script src="<?= base_url() ?>assets/js/module/brewery/brewerystatemapping.js"></script>