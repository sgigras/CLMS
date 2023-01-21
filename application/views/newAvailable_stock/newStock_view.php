<!-- Content Wrapper. Contains page content -->
<?php
// echo "<pre>";
// print_r($liquor_name_record);
// die();
$resultArray = (isset($liquor_data)) ? $liquor_data[0] : new stdClass;
$liquor_select_array = (isset($liquor_list)) ? $liquor_list : array();
// print_r($resultArray );die();
/* $distributor_name_select_array = (isset($distributor_name_list)) ? $distributor_name_list : array(); */
?>
<!--<div class="content-wrapper" style="margin-top: 55px;min-height: 580.08px !important;">-->
<!-- Main content -->
<section class="content">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-plus"></i>
                    <?php echo "Add New Stock" ?>
                </h3>
            </div>
            <!-- <div class="d-inline-block float-right">
                <a href="<?= base_url('master/Alcohol_masterAPI/liquorspecificdetails'); ?>" class="btn btn-secondary"><i class="fa fa-list"></i>  <?= trans('liquor_list') ?></a>
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div> -->
        </div>
    </div>
    <div class="card card-default mt-1">
        <div class="card-body ">

            <!-- For Messages -->
            <?php // $this->load->view('admin/includes/_messages.php')   
            ?>
            <form>

                <div class="row">
                    <!-- <div class='col-1'></div> -->
                    <div class="col-12">
                        <div class="form-group">
                            <label>Invoice No.</label>
                            <input type="text" id="invoice_no" maxlength="16" class="form-control" name="invoice_no" placeholder="Enter Invoice No." style="width: 200px;" required="" data-parsley-required-message="Please Enter Invoice No." data-parsley-trigger="keyup" onkeypress="return checkValidInputKeyPress(numeric_regex_pattern)">
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <div class="panel panel-primary">
                                    <!-- Development and Action Plan Review -->
                                    <div class="panel-heading">
                                        <!-- <label id="agenda_label">Add Stock</label> -->
                                        <span id="agenda_error" style="display: none; color:red;"></span>
                                    </div>
                                    <div class="bs-example">
                                        <div class="Container card-info">
                                            <table class="table table-hover small-text" id="tb2">
                                                <thead>
                                                    <tr class="tr-header">
                                                        <th>Sr.No</th>
                                                        <th style="width:150px;">Liquor Name</th>
                                                        <th style="width:150px;">Selling Price</th>
                                                        <th style="width:150px;">Avaialble Stock</th>
                                                        <th style="width:150px;">New Stock</th>
                                                        <th style="width:150px;">Total</th>
                                                        <th style="width:150px;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr id="A">
                                                        <td></td>
                                                        <td style="width:250px;"><select name="liquor_name" class="form-control" id="liquor_name1">
                                                                <option value="" selected="" disabled="">Select Liquor</option>
                                                                <?php

                                                                foreach ($liquor_name_record as $liquor) {
                                                                    echo '<option value="' . $liquor->id . '">' . $liquor->liquor . '</option>';
                                                                }
                                                                ?>
                                                            </select></td>
                                                        <td style="width:150px">
                                                            <input type="text" name="selling_price" id="selling_price" class="form-control" disabled>
                                                        </td>
                                                        <td style="width:150px">
                                                            <input type="text" name="available_stock" id="available_stock" class="form-control" disabled>
                                                        </td>
                                                        <td style="width:150px">
                                                            <input type="text" name="new_stock" id="new_stock1" class="form-control" placeholder="Enter New Stock" maxlength="4" onkeypress="return checkValidInputKeyPress(numeric_regex_pattern)">
                                                        </td>
                                                        <td style="width:150px">
                                                            <input type="text" name="total" id="total" class="form-control" placeholder="Enter total Stock" disabled>
                                                        </td>
                                                        <td><a href="javascript:void(0);" style="font-size:18px;width:150px;" class="addmorerows" title="Add More Content"><span class="fa fa-plus"></span></a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button id="createmeetingbtn" onclick="loadValues()" class="btn btn-success" type="button">Add Stock</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="myModal" class="modal fade" aria-modal="true" role="dialog">
                    <div class="modal-dialog modal-lg" style="max-width: 60%;">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <!-- <div class="modal-header">
                        <h4 class="modal-title" id="modalHeader"></h4>
                    </div> -->
                            <div class="modal-body p-0" id="confirmStockModal">
                            <div style="height:45px; width:100%;" class="text-center bg-danger"><p class="pt-2 text-white"><b>Stock Details</b></p></div>
                            <h4 class="mt-2 ml-2"><b>Invoice No. : </b><span id="invoice-no" class="bg-success"> </span> </h4>
                                <table class="table table-hover small-text table-striped" id="tb2">
                                    <thead>
                                        <tr class="tr-header">
                                            <th>Sr.No</th>
                                            <th style="width:150px;">Liquor Name</th>
                                            <th style="width:150px;">Selling Price</th>
                                            <th style="width:150px;">Avaialble Stock</th>
                                            <th style="width:150px;">New Stock</th>
                                            <th style="width:150px;">Total</th>
                                            <!-- <th style="width:150px;"></th> -->
                                        </tr>
                                    </thead>
                                    <tbody class="table_body">
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="confirm" class="btn btn-info" onclick="store_data()">Confirm</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<style>
    #tb2 {
        counter-reset: serial-number;
        /* Set the serial number counter to 0 */
    }

    #tb2 td:first-child:before {
        counter-increment: serial-number;
        /* Increment the serial number counter */
        content: counter(serial-number);
        /* Display the counter */
    }
</style>
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = "<?php echo base_url(); ?>";
</script>
<!--</div>-->
<script src="<?= base_url() ?>assets/js/module/newAvailableStock/newStock.js"></script>
<script>
    var invoice_no ;
    var validation = true;
    var mainArr = [];
    function stock_modal() {
        // let table = document.createElement('table');    
        // table.setAttribute('class','table-bordered');  
        // let thead = document.createElement('thead');
        // let tbody = document.createElement('tbody');

        // table.appendChild(thead);
        // table.appendChild(tbody);
        
        // Adding the entire table to the modal body tag
        

        // let row_1 = document.createElement('tr');
        // // let heading_1 = document.createElement('th');
        // // heading_1.innerHTML = "Sr. No.";
        // let heading_1 = document.createElement('th');
        // heading_1.innerHTML = "Liquor Name";
        // let heading_2 = document.createElement('th');
        // heading_2.innerHTML = "Selling Price";
        // let heading_3 = document.createElement('th');
        // heading_3.innerHTML = "Available Stock";
        // let heading_4 = document.createElement('th');
        // heading_4.innerHTML = "New Stock";
        // let heading_5 = document.createElement('th');
        // heading_5.innerHTML = "Total";

        // row_1.appendChild(heading_1);
        // row_1.appendChild(heading_2);
        // row_1.appendChild(heading_3);
        // row_1.appendChild(heading_4);
        // row_1.appendChild(heading_5);
        // // row_1.appendChild(heading_6);
        // thead.appendChild(row_1);

        invoice_no = $('#invoice_no').val();
        // console.log(invoice_no);

        $("#invoice-no").append(invoice_no);
        var row ;
        var mainTable = $('#tb2');
        var tr = mainTable.find('tbody tr');
        tr.each(function() {
            tmpArr = {};
            tmpArr.liquor_entity_id = $(this).find('td:eq(1) option:selected').val();
            tmpArr.liquor_entity_name = $(this).find('td:eq(1) option:selected').text();
            tmpArr.selling_price = $(this).find('td:eq(2) input:text').val();
            tmpArr.available_stock = $(this).find('td:eq(3) input:text').val();
            tmpArr.new_stock = $(this).find('td:eq(4) input:text ').val();
            tmpArr.total = $(this).find('td:eq(5) input:text').val();
            // console.log(tmpArr);
            var liquor_name = tmpArr.liquor_entity_name.split('--');
            let row_2 = document.createElement('tr');
            let row_2_data_1 = document.createElement('td');

            let row_2_data_2 = document.createElement('td');
            row_2_data_2.innerHTML = liquor_name[0];
            

            let row_2_data_3 = document.createElement('td');
            row_2_data_3.innerHTML = tmpArr.selling_price;

            let row_2_data_4 = document.createElement('td');
            row_2_data_4.innerHTML = tmpArr.available_stock;

            let row_2_data_5 = document.createElement('td');
            row_2_data_5.innerHTML = tmpArr.new_stock; 

            let row_2_data_6 = document.createElement('td');
            row_2_data_6.innerHTML = tmpArr.total;
            row_2.appendChild(row_2_data_1);
            row_2.appendChild(row_2_data_2);
            row_2.appendChild(row_2_data_3);
            row_2.appendChild(row_2_data_4);
            row_2.appendChild(row_2_data_5);
            row_2.appendChild(row_2_data_6);
            
            row = row_2;   
            $(".table_body").append(row);
            // console.log(row); 
        });
        // console.log(row_2);
        // document.getElementById('confirmStockModal').appendChild(table);
        
            $("#myModal").modal('show');
            
        // $(".table_body").html('');

        $(".modal").on("hidden.bs.modal", function(){
            $(".table_body").html("");
            $("#invoice-no").html("");
        });
        
        
    }

    
    $(function() {
        // $("#liquor_name").change(function() {
        //     $("#new_stock1").val('');
        //     $("#total").val('');
        // });
        $(document).on("click", ".addmorerows", function() {
            var table = document.getElementById('tb2');
            var rowCount = table.rows.length;

            $("#tb2").append('<tr id="A' + rowCount + '">' +
                '<td>&nbsp;</td>' +
                '<td style="width:200px;"><select class="form-control" id="liquor_name' + rowCount + '">' +
                '<option value="" selected="" disabled="">Select Liquor</option>' +
                "<?php
                    foreach ($liquor_name_record as $liquor) {
                        echo '<option value=' . $liquor->id . '>' . $liquor->liquor .  '</option>';
                    }
                    ?>" +
                '</select></td>' +
                '<td  style="width:100px"><input type="text" name="selling_price" id="selling_price' + rowCount + '" class="form-control" disabled></td>' +
                '<td  style="width:100px"><input type="text" name="available_stock" id="available_stock' + rowCount + '" class="form-control" disabled></td>' +
                '<td style="width:100px"><input type="text" name="current_stock" maxlength="4" id="new_stock' + rowCount + '" class="form-control" placeholder="Enter New Stock"></td>' +
                '<td style="width:100px"><input type="text" name="total" id="total' + rowCount + '"class="form-control" placeholder="Enter total Stock" disabled> </td>' +
                '<td><a href="javascript:void(0);" class="remove"><span style="color: red;" class="fa fa-trash"></span></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" style="font-size:18px;" class="addmorerows" title="Add More Content"><span class="fa fa-plus"></span></a></td>' +
                '</tr>'

            );
            $(document).on('click', '.remove', function() {
                var trIndex = $(this).closest("tr").index();
                if (trIndex > 0) {
                    $(this).closest("tr").remove();
                } else {

                }
            });

            $("#liquor_name" + rowCount).change(function() {
                $("#new_stock" + rowCount).val('');
                $("#total" + rowCount).val('');
            });

            $("#liquor_name" + rowCount).select2({
                width: '250px',
                placeholder: 'Select a liquor name'
            });

            // function isEmpty(field ) {
            //     if ($("#" + field + rowCount).val() == '') {
            //         $("#" + field + rowCount).addClass('is-invalid');
            //         validation = false;
            //         return false;
            //     } else {
            //         if ($("#" + field + rowCount).hasClass("is-invalid")) {
            //             $("#" + field + rowCount).removeClass("is-invalid");
            //             validation = true;
            //         }
            //     }
            // }

            $(document).ready(function() {
                $("#available_stock, #new_stock" + rowCount).keyup(function() {
                    var total = 0;
                    var available_stock = Number($("#available_stock" + rowCount).val());
                    var new_stock = Number($("#new_stock" + rowCount).val());
                    var total = available_stock + new_stock;
                    $('#total' + rowCount).val(total);
                    document.getElementById("total" + rowCount).setAttribute("disabled", "disabled");
                });


            });
            // isEmpty("newStock"+ rowCount);
            $("#liquor_name" + rowCount).on("change", function() {
                var liquor_id = $("#liquor_name" + rowCount).val();


                // console.log(liquor_id);
                // console.log(select1);

                // console.log(GetValue);
                // $("#available_stock").val("d");

                $.ajax({
                    url: DOMAIN + 'newStock/NewStock_master/fetchAvailableStock',
                    method: 'POST',
                    data: {
                        csrf_test_name: csrfHash,
                        csrfName: csrfName,
                        liquor_id: liquor_id
                    },
                    success: function(response) {
                        var res = JSON.parse(response);
                        // console.log(res[0].available_quantity);
                        if (res.length > 0) {
                            document.getElementById("available_stock" + rowCount).value = res[0].available_quantity;
                            document.getElementById("available_stock" + rowCount).setAttribute("disabled", "disabled");

                            document.getElementById("selling_price" + rowCount).value = res[0].selling_price;
                            document.getElementById("selling_price" + rowCount).setAttribute("disabled", "disabled");
                        }
                    },
                    error: function() {
                        // alert(DOMAIN + 'custom/Custom/fetchdurations');
                        alert("Error!!");

                    }
                });


            });



        });
        $("#liquor_name1").select2({
            width: '250px',
            placeholder: 'Select a liquor name'
        });

        // $(document).on('change', '#liquor_name', function() {
        //     var liquor_id = document.getElementById('liquor_name').value;
        //     // console.log(liquor_id);

        // });
        $(document).ready(function() {
            $("#available_stock, #new_stock1").keyup(function() {
                var total = 0;
                var available_stock = Number($("#available_stock").val());
                var new_stock = Number($("#new_stock1").val());
                var total = available_stock + new_stock;
                $('#total').val(total);
                document.getElementById("total").setAttribute("disabled", "disabled");
            });
        });

        $("#liquor_name1").on("change", function() {
            var liquor_id = $("#liquor_name1").val();
            $("#new_stock1").val('');
            $("#total").val('');

            // console.log(GetValue);

            $.ajax({
                url: DOMAIN + 'newStock/NewStock_master/fetchAvailableStock',
                method: 'POST',
                data: {
                    csrf_test_name: csrfHash,
                    csrfName: csrfName,
                    liquor_id: liquor_id
                },
                success: function(response) {
                    var res = JSON.parse(response);
                    // console.log(res);
                    if (res.length > 0) {
                        document.getElementById("available_stock").value = res[0].available_quantity;
                        document.getElementById("available_stock").setAttribute("disabled", "disabled");

                        document.getElementById("selling_price").value = res[0].selling_price;
                        document.getElementById("selling_price").setAttribute("disabled", "disabled");
                    }
                },
                error: function() {
                    // alert(DOMAIN + 'custom/Custom/fetchdurations');
                    alert("Error!!");

                }
            });

        });




    })

    function isEmpty(field) { //validation function
        // console.log(field);
        // console.log($("#" + field).val());
        if ($("#" + field).val() == '' || $("#" + field).val() == null) {
            $("#" + field).addClass('is-invalid');
            validation = false;
            return false;
        } else {
            if ($("#" + field).hasClass("is-invalid")) {
                $("#" + field).removeClass("is-invalid");
                // validation = true;
            }
        }
    }

    // function getSelectedValue(selected1){
    //             if(selected1!=''){
    //                 $('#liquor_name' + rowCount + ' option[value='+selected1+']').hide();
    //             }
    //         }

    function loadValues() {
        validation = true;
        invoice_no = $('#invoice_no').val();
        // console.log(invoice_no);
        if (invoice_no == "") {
            swal("Please Enter Invoice No.");
            return false;
        }
        mainArr = [];
        var tmpArr = [];
        var mainTable = $('#tb2');
        var tr = mainTable.find('tbody tr');
        var i = 1;
        var liquor_entity_id_array = [];
        var unique_liquor_entity_id_array = [];
        // console.log(tr);
        tr.each(function() {
            tmpArr = {};
            tmpArr.liquor_entity_id = $(this).find('td:eq(1) option:selected').val();
            tmpArr.selling_price = $(this).find('td:eq(2) input:text').val();
            tmpArr.available_stock = $(this).find('td:eq(3) input:text').val();
            tmpArr.new_stock = $(this).find('td:eq(4) input:text ').val();
            tmpArr.total = $(this).find('td:eq(5) input:text').val();
            
            var new_stock = `new_stock${i}`;
            // console.log(new_stock);
            isEmpty(new_stock);

            var liquor_entity_id = `liquor_name${i}`;
            isEmpty(liquor_entity_id);
            i++;


            liquor_entity_id_array.push(tmpArr.liquor_entity_id);

            // console.log(liquor_entity_id_array_length);



            // console.log(unique_liquor_entity_id_array_size);






            // console.log(tmpArr.liquor_entity_id);




            // console.log(tmpArr);
            // has to clean on every found for take every td values into array
            // $(this).find('td').each(function() {
            //     var values = $(this).find('select,input').val();
            //     // console.log(values);
            //     if (typeof values !== 'undefined' && values != null && values.length > 0) {
            //         tmpArr.push(values);
            //     }

            // });
            mainArr.push(tmpArr);
        });
        if (validation == true) {
            unique_liquor_entity_id_array = new Set(liquor_entity_id_array);
            let liquor_entity_id_array_length = liquor_entity_id_array.length;
            let unique_liquor_entity_id_array_size = unique_liquor_entity_id_array.size;
            if (liquor_entity_id_array_length != unique_liquor_entity_id_array_size) {
                swal("You can't select Same Liquor");
                
                return false;

            }
            stock_modal();
            
        }

        // console.log(mainArr);
        // var liquor_array = [];
        // var available_stock_array = [];
        // var new_stock_array = [];
        // var total_array = [];
        // var arraylength = mainArr.length - 1;
        // // var agendavalidation = "";

        // for (i = 1; i <= arraylength; i++) {
        //     var liquor = mainArr[i][0];
        //     var available_stock = mainArr[i][1];
        //     var new_stock = mainArr[i][2];
        //     var total = mainArr[i][3];

        //     liquor_array.push(liquor);
        //     available_stock_array.push(available_stock);
        //     new_stock_array.push(new_stock);
        //     total_array.push(total);

        // }


        // $('#createmeetingbtn').attr("disabled", true);


        if (validation == true) {
            
        } else {
            swal("All Fields are Mandatory");
            return false;
        }

    }

    function store_data()
    {
        // console.log(mainArr);
        // console.log(csrfHash);
        // console.log(csrfName);
        // console.log(invoice_no);
        document.getElementById("confirm").disabled = true;
        $.ajax({
                url: DOMAIN + 'newStock/NewStock_master/createNewStock',
                method: 'POST',
                data: {
                    csrf_test_name: csrfHash,
                    csrfName: csrfName,
                    invoice_no: invoice_no,
                    mainArr: mainArr
                },
                success: function(response) {

                    if (response) {
                        
                        swal({
                            title: "Success!",
                            text: "New Stock Added Successfully!",
                            type: "success"
                        }).then(function() {

                            window.location.reload();

                        });

                    }
                },
                error: function() {
                    // alert(DOMAIN + 'custom/Custom/createplanning');
                    alert("Error!!");

                }
                
            });
    }
</script>