<!-- Author: SUJIT N. MISHRA
Created on:23/10/2021
Scope: Alcohol master view
Source:
-->
<?php
$resultArray = (isset($alcohol_type)) ? $alcohol_type : new stdClass;
?>
<section class="content">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-plus"></i>
                 <?= trans('add_alcohol_type')?> </h3>
             </div>
             <div class="d-inline-block float-right">
               <a href="#" class="btn btn-secondary addBtn"><i class="fa fa-plus"></i><?= trans('add_alcohol_type') ?></a>
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
            
            </div> -->
        </div>
        <div class="card-body">

            
            <?php
            echo form_open(base_url('master/Alcohol_masterAPI/addalcholType'),  array("id" => "submit_liquor", "class" => "form-horizontal"));
           
            ?>
           
<!-- 
            table -->

             <table class="table" id="q5">
              <thead>
                <tr>
                  <th><?= trans('liquor_type')?><sup class="mandatory">*</sup></th>
                 
                
                </tr>
              </thead>
              <tbody>
                <tr id="addRow">



                  
                </tr>
              </tbody>
            </table>
 <!-- <div class="form-group">
                <div class="col-md-12">
                   
                </div> <input type="submit" name="submit" value="<?= $title ?>" class="btn btn-primary pull-right">
            </div> -->
            <?php echo form_close(); ?>
        </div>
        
    </div>
</section>
<script>

var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
var baseurl = "<?php echo base_url(); ?>";
</script>

<script src="<?= base_url() ?>assets/js/module/common/validation.js"></script>
<script src="<?= base_url() ?>assets/js/module/master/liquor_master.js"></script>

    


    





      



   













