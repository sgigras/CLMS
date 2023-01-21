<!-- Author:Hriday Mourya
Subject:Request For Quotation  Viewpage
Date:08-09-21 -->

<?php 

$form_data=$this->session->flashdata('form_data'); 

?>
<link  href="<?= base_url()?>assets/plugins/select2/select2.css" rel="stylesheet">
<script src="<?= base_url()?>assets/plugins/select2/select2.js" defer></script>
<style >
.mandatory{
  color: red;
}



</style>

<!-- <div class="content-wrapper"> -->
  <section class="content">
    <div class="card card-default color-palette-bo">
      <div class="card-header">
        <div class="d-inline-block">
          <h3 class="card-title"> <i class="fa fa-plus"></i>
            <?= trans('add_rfq') ?></h3>
          </div>
          <div class="d-inline-block float-right">
            <a href="<?= base_url('rfq/RequestquotationAPI'); ?>" class="btn btn-secondary"><i class="fa fa-list"></i> <?= trans('rfq_list') ?></a>
          </div>
        </div>


        <!-- /.card-header -->
        <!-- form start -->
        <?php $this->load->view('admin/includes/_messages.php') ?>
        <?php echo form_open_multipart(base_url('rfq/RequestquotationAPI/add'), 'class="form-horizontal"');  ?> 
        <div class="card-body">
          <div class="row">

            <div class="col-sm-6">
              <!-- text input -->
              <div class="form-group">
                <label><?= trans('order_no') ?><sup class="mandatory">*</sup></label>
                <input type="text" name="order_no" id="order_no" maxlength="50" onkeypress="return /[A-Z0-9 ]/i.test(event.key)"  value="<?= (isset($form_data['order_no'])? $form_data['order_no']:"");?>" class="form-control" placeholder="<?= trans('e_order_no') ?>">
              </div>
            </div>



            <div class="col-sm-6">
              <!-- text input -->
              <div class="form-group">
                <label><?= trans('mode') ?><sup class="mandatory">*</sup></label>
                <input type="text" class="form-control" id="mode" name="mode" value="FTL" readonly>
              </div>
            </div>

            <div class="col-sm-6">
              <!-- text input -->
              <div class="form-group">
                <label><?= trans('shipper') ?><sup class="mandatory">*</sup></label>
                <select class="form-control select2" id="shipper" name="shipper" style="width: 100%;">
                  <option value="0" disabled selected><?= trans('select_shipper') ?></option>
                  <?php foreach($transporter_plants as $plants): ?>
                    <option value="<?= $plants['id']; ?>"<?= ((isset($form_data['shipper']) AND $plants['id']==$form_data['shipper'])? "selected":""); ?> ><?= $plants['plant_name_for_gocomet']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label><?= trans('picup_date') ?><sup class="mandatory">*</sup></label>
                <input type="date" name="picup_date" id="picup_date" value="<?= (isset($form_data['picup_date'])? $form_data['picup_date']:"");?>"   max="9999-12-31" class="form-control from-datepicker" placeholder="yyyy-mm-dd">
              </div>
            </div>
          </div>

          <div  class="row">
            <div class="col-sm-6">
              <!-- text input -->
              <div class="form-group">
                <label><?= trans('origin_add') ?><sup class="mandatory">*</sup></label>
                <input type="text" name="origin_add" maxlength="200" onkeypress="return /[A-Z0-9 ,./-]/i.test(event.key)"  value="<?= (isset($form_data['origin_add'])? $form_data['origin_add']:"");?>" class="form-control" placeholder="<?= trans('e_origin_add') ?>">
              </div>
            </div>

            <div class="col-sm-6">
              <!-- text input -->
              <div class="form-group">
                <label><?= trans('origin_zip_code') ?><sup class="mandatory">*</sup></label>
                <input type="text" name="origin_zip_code" maxlength="6" onkeypress="return /[0-9]/i.test(event.key)"  value="<?= (isset($form_data['origin_zip_code'])? $form_data['origin_zip_code']:"");?>" class="form-control" placeholder="<?= trans('e_origin_zip_code') ?>">
              </div>
            </div>
          </div><br>

          <div class="row">
           <div class="col-md-12">
            <table class="table" id="q5">
              <thead>
                <tr>
                  <th><?= trans('number_of_trucks') ?><sup class="mandatory">*</sup></th>
                  <th><?= trans('truck_type') ?><sup class="mandatory">*</sup></th>
                </tr>
              </thead>
              <tbody>
                <tr id="addRow">
                  <td class="col-sm-4">
                    <input type="text" name="no_of_trucks[]" maxlength="1" onkeypress="return /[0-9]/i.test(event.key)"    class="form-control addMain" placeholder="<?= trans('e_number_of_trucks') ?>">
                  </td>
                  <td class="col-sm-4">
                    <select class="form-control addPrefer" id="select1"   name="truck_type[]" style="width: 100%;">
                      <option value="0" disabled selected><?= trans('truck_type') ?></option>
                      <?php foreach($truck_type as $truck): ?>
                        <option value="<?= $truck['truck_type']; ?>" <?= ((isset($form_data['truck_type[]']) AND $truck['truck_type']==$form_data['truck_type[]'])? "selected":""); ?>><?= $truck['truck_type']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </td>
                  <td class="col-sm-2 text-center">
                    <span class="addBtn">
                      <i class="fa fa-plus"></i>
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="row">
         <div class="col-md-12">
          <table class="table">
            <thead>
              <tr>
                <th><?= trans('des_city') ?><sup class="mandatory">*</sup></th>
                <th><?= trans('destination_add') ?><sup class="mandatory">*</sup></th>
                <th><?= trans('destination_zip_code') ?><sup class="mandatory">*</sup></th>
                
              </tr>
            </thead>
            <tbody>
              <tr id="add_destination">
                <td class="col-sm-3">
                  <div id="tooltest0" class="tooltest0">
                    <select class="form-control select2 addcity" id="city" name="city[]" style="width: 100%;">
                    </select>
                  </div>
                </td>
                <td class="col-sm-3">
                 <input type="text" name="destination_add[]" maxlength="200" onkeypress="return /[A-Z0-9 ,./-]/i.test(event.key)"  value="<?= (isset($form_data['destination_add[]'])? $form_data['destination_add[]']:"");?>" class="form-control adddes" placeholder="<?= trans('e_destination_add') ?>">
               </td>
               <td class="col-sm-3">
                 <input type="text" name="destination_zip_code[]" maxlength="6" onkeypress="return /[0-9]/i.test(event.key)"   value="<?= (isset($form_data['destination_zip_code[]'])? $form_data['destination_zip_code[]']:"");?>" class="form-control addzip" placeholder="<?= trans('e_destination_zip_code') ?>">
               </td>
               
               <td class="col-sm-3 text-center">
                <span class="add">
                  <i class="fa fa-plus"></i>
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group">
        <div class="card-footer mt-4 card-footer bg-transparent p-0 d-flex justify-content-end">
          <button class="btn btn-warning mr-2"><i class="fa fa-eraser mr-2"></i><?= trans('reset') ?></button>
          <button type="submit" name="submit" value="<?= trans('add_rfq') ?>" id="add_rfq" class="btn btn-success"><i class="fa fa-save mr-2"></i><?= trans('add_rfq') ?></button>
        </div>
      </div>
    </div>
  </div>
  <?php echo form_close(); ?>
</div><!-- /.container-fluid -->
<!-- /.container-fluid -->
</section>
<link  href="<?= base_url()?>assets/plugins/timepicker/jquery.datetimepicker.min.css" rel="stylesheet">
<script src="<?= base_url()?>assets/plugins/timepicker/jquery.datetimepicker.full.min.js" defer></script>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">

  $(document).ready(function(){


    create_ordernumber();

    $(".addcity").select2({
      placeholder:"Select Destination City",
      createTag: function(term, data) {
        var value = term.term;
        return {
          id: value,
          text: value
        };                
      },
            // tags: true,
            ajax: {
              url:"<?= base_url('rfq/RequestquotationAPI/fetchCity') ?>" ,
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
                        if (value.city.toUpperCase().indexOf(params.term.toUpperCase()) != -1)
                          resData.push(value)
                      })
                      return {
                        results: $.map(resData, function(item) {
                          return {
                            text: item.city,
                            id: item.city
                          }
                        })
                      };
                    },
                    cache: true
                  },
                  minimumInputLength: 1

                });

    $("#shipper").select2({

    });


    $('#bid_close_time').datetimepicker({
      format:'y-m-d H:m:s',
      minDate: 'today'
    });



  });

  function formatRows(main, prefer) {
    return '<tr > <td class="col-sm-4"><input type="text" maxlength="1"  name="no_of_trucks[]" onkeypress="return /[0-9]/i.test(event.key)"   class="form-control addMain" placeholder="<?= trans('e_number_of_trucks') ?>"</td>' +
    '<td class="col-sm-4"><select class="form-control addPrefer" id="select1"   name="truck_type[]" style="width: 100%;"><option value="0" disabled selected><?= trans('truck_type') ?></option> <?php foreach($truck_type as $truck): ?>
    <option value="<?= $truck['truck_type']; ?>" <?= ((isset($form_data['truck_type']) AND $truck['truck_type']==$form_data['truck_type'])? "selected":""); ?>><?= $truck['truck_type']; ?></option> <?php endforeach; ?>
    </select> </td>' +
    '<td class="col-xs-1 text-center"><a href="#" onClick="deleteRow(this)">' +
    '<i class="fa fa-trash-o" aria-hidden="true"></a></td></tr>';
  };

  function deleteRow(trash) {
    $(trash).closest('tr').remove();
  };

  function addRow() {
    var main = $('.addMain').val();
    var preferred = $('.addPrefer').val();
    // console.log(preferred);
    $(formatRows(main,preferred)).insertAfter('#addRow');

  }

  $('.addBtn').click(function()  {
    addRow();
  });

  function format(city,des, zip) {

    return '<tr><td class="col-sm-3"><select class="form-control select2 addcity" id="city" name="city[]" style="width: 100%;"></select></td> <td class="col-sm-4"><input type="text"  maxlength="200"  name="destination_add[]" onkeypress="return /[A-Z0-9 ,./-]/i.test(event.key)"   class="form-control adddes" placeholder="<?= trans('e_destination_add') ?>"></td>' +
    ' <td class="col-sm-4"><input type="text"  maxlength="6"  name="destination_zip_code[]" onkeypress="return /[0-9]/i.test(event.key)"   class="form-control addzip" placeholder="<?= trans('e_destination_zip_code') ?>"></td>' +
    '<td class="col-xs-1 text-center"><a href="#" onClick="deletedes(this)">' +
    '<i class="fa fa-trash-o" aria-hidden="true"></a></td></tr>';
  };

  function deletedes(trash) {
    $(trash).closest('tr').remove();
  };

  

  $('.add').click(function()  {

   $(".addcity").select2({
    placeholder:"Select Destination City",
    allowClear: true,
    createTag: function(term, data) {
      var value = term.term;
      return {
        id: value,
        text: value
      };                
    },
            // tags: true,
            ajax: {
              url:"<?= base_url('rfq/RequestquotationAPI/fetchCity') ?>" ,
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
                        if (value.city.toUpperCase().indexOf(params.term.toUpperCase()) != -1)
                          resData.push(value)
                      })
                      return {
                        results: $.map(resData, function(item) {
                          return {
                            text: item.city,
                            id: item.city
                          }
                        })
                      };
                    },
                    cache: true
                  },
                  minimumInputLength: 1

                });


   var destination = $('.adddes').val();
   var zip = $('.addzip').val();
   var city = $('.addcity').val();
   $(format(city,destination,zip)).insertAfter('#add_destination');
 });

  $(function(){
    var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
      month = '0' + month.toString();
    if(day < 10)
      day = '0' + day.toString();
    var maxDate = year + '-' + month + '-' + day;
    $('#picup_date').attr('min', maxDate);
    
  });


  function create_ordernumber(){
    var d = new Date();
    d = new Date(d.getTime());
    var date_format_str = d.getFullYear().toString()+((d.getMonth()+1).toString().length==2?(d.getMonth()+1).toString():"0"+(d.getMonth()+1).toString())+(d.getDate().toString().length==2?d.getDate().toString():"0"+d.getDate().toString())+(d.getHours().toString().length==2?d.getHours().toString():"0"+d.getHours().toString())+((parseInt(d.getMinutes()/5)*5).toString().length==2?(parseInt(d.getMinutes()/5)*5).toString():"0"+(parseInt(d.getMinutes()/5)*5).toString())+d.getSeconds().toString();


 var short_code_name="<?php  echo $code_name; ?>";


$("#order_no").val(short_code_name+date_format_str);
  }




</script>