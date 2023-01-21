 <style>
 .mandatory{
  color: red;
}
#bold{
  font-weight: bold;
}
</style>

<!-- <div class="content-wrapper"> -->
  <link  href="<?= base_url()?>assets/plugins/select2/select2.css" rel="stylesheet">
  <script src="<?= base_url()?>assets/plugins/select2/select2.js" ></script>
  <!-- Main content -->
  <section class="content">
    <div class="card card-default color-palette-bo">
      <div class="card-header">
        <div class="d-inline-block">
          <h3 class="card-title"> <i class="fa fa-plus"></i>
            <?= trans('vehicle_verification') ?></h3>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <!-- form start -->
                <div class="box-body">
                  <!-- For Messages -->
                  <?php $this->load->view('admin/includes/_messages.php') ?>
                  <?php echo form_open(base_url('verification/Vehicle_verificationAPI/getvehicles'), 'class="form-horizontal"');  ?> 
                  <div class="form-group">
                    <div class="row">
                     <div class="col-sm-4">
                     </div>
                     <div class="col-sm-4">
                      <!-- text input -->
                      <div class="form-group">
                        <label><?= trans('select_vehicle') ?><sup class="mandatory">*</sup></label>
                        <select  class="form-control" id="vehicle" name="vehicle" style="width: 100%;">
                        </select>
                        <span id="vehicle" style="color:red"></span>
                      </div>
                    </div>
                    
                   
                    <div class="col-sm-4">
                     <div class="form-group">
                      <div class="card-footer mt-4 card-footer bg-transparent p-0 d-flex ">
                        <button type="submit" name="submit" id="verify" value="<?= trans('save') ?>" id="vehicle_add" class="btn btn-success"><i class="fa-info-square"></i><?= trans('view') ?></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
               <?php if (isset($info)) { ?>
                <div class="card-body" id="vehicle">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="box">
                        <!-- form start -->
                        <div class="box-body">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-sm-3">
                              </div>
                              <div class="col-sm-6">
                                <div class="card">
                                  <div class="card-body table-responsive">
                                    <table id="na_datatable" class="table datatable datatable-bordered table-bordered datatable-striped" width="100%">
                                      <thead class="m0 mb5" style="background-color:  #E8E8E8;">
                                        <?php foreach($info  as $row): ?>
                                          <tr>
                                            <th><?= trans('vehicle_no') ?>:<?=$row['vehicleno']?></th>
                                            <th><?= trans('details') ?></th>
                                          </tr>
                                          <input type="hidden" name="value" value="<?=$row['vehicleid']?>">
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td id="bold"><?= trans('transporter_name') ?></td>
                                            <td> <?=$row['transporter_name']?></td>

                                          </tr>
                                          <tr>
                                            <td id="bold"><?= trans('vehicle_type') ?></td>
                                            <td> <?= ($row['vehicle_type'] == 1)? 'Reefer': (($row['vehicle_type'] == 2)? 'Ambient': 'PTL');?></td>

                                          </tr>
                                          <tr>
                                            <td id="bold"><?= trans('capacity') ?></td>
                                            <td> <?=$row['capacity']?></td>

                                          </tr>
                                          <tr>
                                            <td id="bold"><?= trans('box_counts') ?></td>
                                            <td > <?=($row['box_count'])?></td>

                                          </tr>
                                          <tr>
                                            <td id="bold"><?= trans('puc_expiry_date') ?></td>
                                            <td id="pucdate"> <?=$row['expiry_puc']?></td>

                                          </tr>
                                          <tr>
                                            <td id="bold"><?= trans('insurance_expiry_date') ?></td>
                                            <td id="date"> <?=$row['expiry_insurance']?></td>

                                          </tr>
                                          <tr>
                                            <td id="bold"><?= trans('rc_ex_date') ?></td>
                                            <td id="date"> <?=$row['expiry_rto']?></td>

                                          </tr>
                                        <?php endforeach;?>
                                      </tbody>
                                    </table>
                                    <div class="card-footer mt-4 card-footer bg-transparent p-0 d-flex justify-content-end">
                                      <a href="<?= base_url('verification/Vehicle_verificationAPI/') ?>" class="btn btn-light mr-2" role="button" aria-pressed="true"><i class="fas fa-backspace mr-2"></i><?= trans('back') ?></a>
                                      <button type="submit" value="1" id="reject" name="reject" value="<?= trans('reject') ?>" class="btn btn-warning mr-2"><i class="fas fa-eject mr-2"></i><?= trans('reject') ?></button>
                                      <button type="submit" id="approve" name="approve" value="1" class="btn btn-success"><i class="fas fa-vote-yea mr-2"></i><?= trans('approve') ?></button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-3">
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- /.box-body -->
                      </div>
                    </div>
                  </div>  
                </div>
              <?php } ?>
            </div>
            <?php echo form_close(); ?>
          </div>
          <!-- /.box-body -->
        </div>
      </div>
    </div>  
  </div>
  
</div>
</section> 
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $("#vehicle").hide();
    $("#vehicle").select2({
      placeholder:"Select Vehicle No",
      createTag: function(term, data) {
        var value = term.term;
        return {
          id: value,
          text: value
        };                
      },
            // tags: true,
            ajax: {
              url:"<?= base_url('verification/Vehicle_verificationAPI/fetchvehicle') ?>" ,
              dataType: 'json',
              delay: 250,
              data: function(params) {
                return {
                        q: params.term // search term
                      };
                    },
                    processResults: function(data, params) {

                      var resData = [];
                      data.forEach(function(value) {
                        if (value.vehicleno.toUpperCase().indexOf(params.term.toUpperCase()) != -1)
                          resData.push(value)
                      })
                      return {
                        results: $.map(resData, function(item) {
                          return {
                            text: item.vehicleno,
                            id: item.vehicleid
                          }
                        })
                      };
                    },
                    cache: true
                  },
                  minimumInputLength: 1

                });
  });
  $('#verify').click(function(){
   $("#vehicle").show();
   
 });

  
  

</script>

