    <!-- Main Sidebar Container -->
    <?php $form_data=$this->session->flashdata('form_data');
    
      if(isset($form_data)){
        $classname="fas fa-minus";
        $displaystyle="display: block;";
      }else{
        $classname="fas fa-plus";
        $displaystyle="display: none;";
      }
    ?>

    <style>
    .mandatory{
      color: red;
    }
    .NEWCLASS {
    font-family: FontAwesome;
    }
    </style>
    <script>
      var DOMAIN="<?= base_url();?>";
    </script>
    <!-- Content Wrapper. Contains page content -->
    <!-- <div class="content-wrapper"> -->
      <!-- Content Header (Page header) -->
    <link  href="<?= base_url()?>assets/plugins/select2/select2.css" rel="stylesheet">
    <script src="<?= base_url()?>assets/plugins/select2/select2.js" defer></script>
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="card collapsed-card">

                <!-- /.card-header -->
                <!-- form start -->
                <?php $this->load->view('admin/includes/_messages.php') ?>

                 
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                 <h3 class="card-title"> <i class="fa fa-plus"></i> Request Vehicle</h3>

                  <div class="card-tools">

                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="<?= $classname ?>" id="openReqForm"></i>
                    </button>

                  </div>
                </div>
                <div class="card-body" id="cardbody" style="<?= $displaystyle ?>">
                <form>
                    <div class="row">
                      <div class="col-sm-6">
                        <!-- text input -->
                        <div class="form-group">
                          <label>Box Count<sup class="mandatory">*</sup></label>
                          <input type="text" maxlength="3" onkeypress="return /[0-9]/i.test(event.key)" id="box_count" name="box_count" class="form-control" placeholder="Enter Box Count" value="<?= (isset($form_data['box_count'])? $form_data['box_count']:""); ?>">
                          <span id="span_boxcount" style="color:red"></span>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>Shipped By Date<sup class="mandatory">*</sup></label>
                          <input type="text" name="ship_date" id="ship_date" placeholder="Select Shipping Date" class="form-control" format="yyyy-mm-dd" autocomplete="off" value="<?= (isset($form_data['ship_date'])? $form_data['ship_date']:""); ?>">
                          <span id="span_shipdate" style="color:red"></span>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <!-- text input -->
                        <div class="form-group">
                          <label>Destination Category<sup class="mandatory">*</sup></label>
                          <select class="form-control" id="category" placeholder="Select Destination Category" name="category" style="width: 100%;">
                            <option></option>
                            <option value="1">Rest of India</option>
                            <option value="2">North East</option>
                            <option value="3">Bangladesh</option>
                            <option value="4">Nepal</option>
                          </select>
                          <span id="span_category" style="color:red"></span>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <!-- text input -->
                        <div class="form-group">
                          <label>Destination & Route Code<sup class="mandatory">*</sup></label>
                          <select class="form-control" placeholder="Select Destination & Route code" id="destination" name="destination" style="width: 100%;">
                          </select>
                          <span id="span_destination" style="color:red"></span>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label>City<sup class="mandatory">*</sup></label>
                          <select class="form-control select2" placeholder="Select Destination City" id="city" name="city" style="width: 100%;">
                          </select>
                          <span id="span_city" style="color:red"></span>
                        </div>
                      </div>
                      

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer mt-4 card-footer bg-transparent p-0 d-flex justify-content-end">
                      <!-- <button class="btn btn-light mr-2"><i class="fas fa-backspace mr-2"></i>Back</button> -->
                      <button class="btn btn-warning mr-2"><i class="fas fa-eraser mr-2"></i>Reset</button>
                      <input type="button" name="submit" id="submit" value=" &#xf1d8; Request" class=" NEWCLASS btn btn-success">
                    </div>

                </form>

                  <table class="table table-hover text-nowrap mt-4 ">
                    <tbody>
                      <tr>
                        <td><b>Box Count Range</b></td>
                        <td>0-44</td>
                        <td>45-64</td>
                        <td>65-95</td>
                        <td>96-120</td>
                        <td>121-169</td>
                        <td>170+</td>
                      </tr>
                      <tr>
                        <td><b>No of Vehicle Available</b></td>
                        <td id="count_A"></td>
                        <td id="count_B"></td>
                        <td id="count_C"></td>
                        <td id="count_D"></td>
                        <td id="count_E"></td>
                        <td id="count_F"></td>
                      </tr>
                    </tbody>

                  </table>
                </div>
              </div>

              <div class="card">
                <div class="card-body table-responsive">
                  <table class="table table-hover table-bordered table-striped" id="req_table" width="100%">
                    <thead>
                      <tr>
                        <th>Sl No</th>
                        <th>Box Count</th>
                        <th>Route Code</th>
                        <th>City</th>
                        <th>Expected Time</th>
                        <th>Vehicle</th>
                        <th>Vehicle Capacity</th>
                        <th>Transporter</th>
                        <th>Status</th>
                        <th>Cancel Request</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php $i=0; ?>
                      <?php foreach($request as $row): ?>
                        <tr>
                          <td>
                              <?= ++$i;?>
                          </td>
                          <td>
                              <?=$row['box_count']?>
                          </td>
                          <td>
                              <?=$row['route_code']?>
                          </td> 
                          <td>
                              <?=$row['city']?>
                          </td>
                          <td>
                            <?=$row['shipping_date']?>
                          </td>
                          <td >
                            <?=$row['vehiclenum'] ?>
                          </td> 
                          <td>
                            <?=$row['box_capacity'] ?>
                          </td>
                          <td>
                            <?=$row['transporter']?>
                          </td>
                          <td class="text-center">
                            <?php $icon= (($row['status'] == 0)? './assets/plugins/icons/Request_pending.png': (($row['status'] == 1)? './assets/plugins/icons/Request_pending.png': (($row['status'] == 2)? './assets/plugins/icons/Request_Cancelled.png': (($row['status'] == 3)? './assets/plugins/icons/Request_Rejected.png': 'None'))));?>
                            <img src="<?=base_url($icon)?>" class="" alt="" width="50px">
                          </td>
                          <td>
                            <?php 
                              $anchor='<a id="can_req_'.$row['id'].'" onclick="cancel_request('.$row['id'].','.$row['vehicle_log_id'].','.$row['vehiclenum'].')" title="Cancel Request" class="btn btn-danger btn-xs mr5" >
                              <i class="fa fa-times"> Cancel Request</i>
                              </a>';
                              $p_tag="<p>".$row['reason_cancellation']."</p>";
                              $td_html= (($row['status'] == 0)? $anchor: (($row['status'] == 1)? $anchor: (($row['status'] == 2)? $p_tag: (($row['status'] == 3)? $anchor: 'None'))));
                              echo $td_html;
                            ?>
                          </td>
                      </tr>
                      <?php endforeach;?>                      
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->

            </div>
            <!--/.col (left) -->
            <!-- right column -->

            <!--/.col (right) -->
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>






    <!-- /.content-wrapper -->
  </div>
<!-- ./wrapper -->

        <div class="modal fade" id="acceptmodal"  role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-body">
                  <table border="1">
                    <thead>
                      <tr>
                        <th>Box Count</th>
                        <th>Shipping Date & Time</th>
                        <th>Destination</th>
                        <th>Destination Category</th>
                        <th>Route Code</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="consolidation_table">
                    </tbody>  
                  </table>
                </div>
              </div>
          </div>
        </div>

    <link  href="<?= base_url()?>assets/plugins/timepicker/jquery.datetimepicker.min.css" rel="stylesheet">
    <script src="<?= base_url()?>assets/plugins/timepicker/jquery.datetimepicker.full.min.js" defer></script>
    <!-- DataTables -->
    <script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
    <script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>



  <script>
    $(document).ready(function(){

      $('#req_table').DataTable();
      fetchboxcount();
      $("#openReqForm").click(function(){
        var class_name=$("#openReqForm").attr("class");
        if(class_name=="fas fa-plus"){
          $("#cardbody").attr("style","display: block");
          $("#openReqForm").attr("class","fas fa-minus");
        }else{
          $("#cardbody").attr("style","display: none");
          $("#openReqForm").attr("class","fas fa-plus");
        }
      });
      $('#ship_date').datetimepicker({
          format:'Y-m-d H:m:s',
          minDate: 'today'
      });

      $('#submit').click(function(){
        var boxcount=$('#box_count').val();
        var category=$('#category').val();
        var ship_date=$('#ship_date').val();
        var dest=$("#destination").val();
        var city=$("#city").val();
        var error=false;

        if(boxcount==""){
          $("#span_boxcount").html("Please Enter Box Count");
          error=false;
        }else{
          $("#span_boxcount").html("");
          error=true;
        }
        if(category==""){
          $("#span_category").html("Please Select Destination Category");
          error=false;
        }else{
          $("#span_category").html("");
          error=true;
        }
        if(ship_date==""){
          $("#span_shipdate").html("Please Select Shipping Date");
          error=false;
        }else{
          $("#span_shipdate").html("");
          error=true;
        }
        if(dest== null){
          $("#span_destination").html("Please Select Destination & Route Code");
          error=false;
        }else{
          $("#span_destination").html("");
          error=true;
        }
        if(city== null){
          $("#span_city").html("Please Select City"); 
          error=false;
        }else{
          $("#span_city").html("");
          error=true;
        }

        if(error){
          var currentdate = new Date();
          var now = currentdate.getFullYear()+"-"+(((currentdate.getMonth() + 1) < 10 ? '0' : '') +(currentdate.getMonth()+1))+"-"+((currentdate.getDate() < 10 ? '0' : '') +currentdate.getDate());
          var nowtime= currentdate.getHours() + ":" + currentdate.getMinutes() + ":" + currentdate.getSeconds();
          var crontime= '11:00:00';
          // console.log(nowtime<crontime);
          if(ship_date.split(' ')[0] == now && nowtime<crontime){
            $.ajax({
              url: DOMAIN + "requestVehicle/RequestVehicleAPI/existing_request", // call the controller to fetch the data
              method: "POST",
              success: function (response) {
                var resObj = JSON.parse(response);
                  if(resObj.length > 0){
                    swal({
                        title: "Do You Want to Consolidate?",
                        text: "You can consolidate this Shipment With upcoming shipments!",
                        icon: "warning",
                        buttons: [
                          'No!',
                          'Yes!'
                        ],
                        dangerMode: true,
                      }).then(function(isConfirm) {
                        if (isConfirm) {
                          for (var i = 0; i < resObj.length; i++) {
                            $("#acceptmodal").modal('show');
                            var div = document.getElementById('consolidation_table');
                            while (div.firstChild) {
                                div.removeChild(div.firstChild);
                            }
                            $('#consolidation_table').append("<tr>"+
                                                              "<td>"+resObj[i].box_count+"</td>"+
                                                              "<td>"+resObj[i].shipping_date+"</td>"+
                                                              "<td>"+resObj[i].destination.split('#')[0]+"</td>"+
                                                              "<td>"+resObj[i].category+"</td>"+
                                                              "<td>"+resObj[i].destination.split('#')[1]+"</td>"+
                                                              "<td><button type='button' class='btn btn-success' id='"+resObj[i].id+"' style='background-color:#228b22;color:white;width:60px' onclick='updateBoxcount("+resObj[i].id+","+boxcount+","+resObj[i].box_count+")' ><i class='fa fa-check'  aria-hidden='true' style='font-size:17px' ></i></button></td>"+
                                                            "</tr>");
                          }
                        } else {
                          swal("Please Give Remarks For Not Consolidating And Making it An Urgent Shipment!", {
                              content: "input",
                              icon: "warning"
                            })
                            .then((value) => {
                              if(value!= ""){
                              $.ajax({
                                  url: DOMAIN + "requestVehicle/RequestVehicleAPI/urgentShipment", // call the control to fetch the data
                                  method: "POST",
                                  data: {boxcount:boxcount,ship_date:ship_date,dest:dest,city:city,submit:true,value:value,category:category},
                                  success: function (response) {
                                    var resObj = JSON.parse(response);
                                      if (resObj)
                                      {
                                          swal({
                                              icon: 'success',
                                              title: 'Vehicle Request Sent Successfully!'
                                            }).then(function () {
                                                location.reload();
                                            })

                                      } 
                                    }
                                });
                              }
                            }); 
                        }
                      })

                  }
                }
            });
          }else if(ship_date.split(' ')[0] == now){
            swal("Please Give Remarks For Urgent Shipment!", {
                    content: "input",
                    icon: "warning"
                  })
                  .then((value) => {
                    if(value!= ""){
                    $.ajax({
                        url: DOMAIN + "requestVehicle/RequestVehicleAPI/urgentShipment", // call the control to fetch the data
                        method: "POST",
                        data: {boxcount:boxcount,ship_date:ship_date,dest:dest,city:city,submit:true,value:value,category:category},
                        success: function (response) {
                          var resObj = JSON.parse(response);
                            if (resObj)
                            {
                                swal({
                                    icon: 'success',
                                    title: 'Vehicle Request Sent Successfully!'
                                  }).then(function () {
                                      location.reload();
                                  })

                            } 
                          }
                      });
                    }
                  });
          }else{

            $.ajax({
                  url: DOMAIN + "requestVehicle/RequestVehicleAPI/request", // call the control to fetch the data
                  method: "POST",
                  data: {boxcount:boxcount,ship_date:ship_date,dest:dest,city:city,submit:true,category:category},
                  success: function (response) {
                      var resObj = JSON.parse(response);
                      if (resObj)
                      {
                          swal({
                              icon: 'success',
                              title: 'Vehicle Request Sent Successfully!'
                          }).then(function () {
                              location.reload();
                          })

                      } 
                    }
                  });
          }
          
        }else{
          alert(error);
        }
      });

      $("#category").select2({
        placeholder: "Select Destination Category"
      });
      $("#destination").select2({
            createTag: function(term, data) {
                var value = term.term;
                    return {
                        id: value,
                        text: value
                    };                
            },
            placeholder: "Select Destination & Route Code",
            ajax: {
                url:"<?= base_url('requestVehicle/RequestVehicleAPI/fetchDestination') ?>" ,
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
                        if (value.destination.toUpperCase().indexOf(params.term.toUpperCase()) != -1)
                            resData.push(value)
                    })
                    return {
                        results: $.map(resData, function(item) {
                            return {
                                text: item.destination,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 1
            
        });

        $("#city").select2({
            createTag: function(term, data) {
                var value = term.term;
                    return {
                        id: value,
                        text: value
                    };                
            },
            placeholder: "Select Destination City",
            ajax: {
                url:"<?= base_url('requestVehicle/RequestVehicleAPI/fetchCity') ?>" ,
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
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 1
            
        });
          setInterval(function(){
            fetchboxcount();
          }, 30000);
    });


      function updateBoxcount(id,new_boxcount,prev_boxcount){
        // console.log(id,new_boxcount,prev_boxcount);
        var total_BoxCount=new_boxcount+prev_boxcount;
        $.ajax({
            url: DOMAIN + "requestVehicle/RequestVehicleAPI/updateBoxcount", // call the control to fetch the data
            method: "POST",
            data: {id:id,total_BoxCount:total_BoxCount},
            success: function (response) {
              var resObj = JSON.parse(response);
                if (resObj)
                {
                    swal({
                        icon: 'success',
                        title: 'Shipment Consolidated Successfully!'
                      }).then(function () {
                          location.reload();
                      })

                } 
              }
        });
      }

      function cancel_request(req_id,veh_log_id,vehicle_num){
        swal("Please Give Remarks For Cancellation of Shipment!", {
                    content: "input",
                    icon: "warning"
                  })
                  .then((value) => {
                    if(value!= ""){
                    $.ajax({
                        url: DOMAIN + "requestVehicle/RequestVehicleAPI/cancel_request", // call the control to fetch the data
                        method: "POST",
                        data: {value:value,req_id:req_id,veh_log_id:veh_log_id,vehicle_num:vehicle_num},
                        success: function (response) {
                          var resObj = JSON.parse(response);
                            if (resObj)
                            {
                                swal({
                                    icon: 'success',
                                    title: 'Vehicle Request Cancelled Successfully!'
                                  }).then(function () {
                                      location.reload();
                                  })

                            }else{
                              swal({
                                    icon: 'error',
                                    title: 'Something went wrong!!'
                                  })
                            } 
                          }
                      });
                    }
                  });
      }

      function fetchboxcount(){
        $.ajax({
            url: DOMAIN + "requestVehicle/RequestVehicleAPI/fetchboxcount", // call the control to fetch the data
            method: "POST",
            success: function (response) {
              var resObj = JSON.parse(response);
                if (resObj)
                {
                  $("#count_A").html(resObj['count_A']);
                  $("#count_B").html(resObj['count_B']);
                  $("#count_C").html(resObj['count_C']);
                  $("#count_D").html(resObj['count_D']);
                  $("#count_E").html(resObj['count_E']);
                  $("#count_F").html(resObj['count_F']);
                } 
              }
        });
      }
  </script>


