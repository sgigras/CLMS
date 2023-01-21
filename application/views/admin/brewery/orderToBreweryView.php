<!-- Content Wrapper. Contains page content -->
<?php

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
                    Order to Brewery
                </h3>
            </div>
        </div>
        <form>
            <div class="card-body">
                <div class="row">
                </div>
                <br>
                <div class="row">
                    <div class="col-md-2">
                        <label> <b><span id="title">Select Brewery : </span></b></label>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control select2" name="select_brewery" id="select_brewery" data-placeholder="Select Brewery">
                            <option></option>
                            <?php foreach ($brewerylist as $brewerylist) : ?>
                                <option value="<?php echo $brewerylist['id'] ?>"><?php echo $brewerylist['brewery_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
    </div>
    <div class="card card-default mt-1" id="hold_liquor_table">
        <div class="card-body ">

            <!-- For Messages -->
            <?php // $this->load->view('admin/includes/_messages.php')   
            ?>

            <div class="row">
                <!-- <div class='col-1'></div> -->

                <div class="col-12">
                    <div class="form-group row pt-4">
                        <div class="col-12">
                            <div class="panel panel-primary">
                                <!-- Development and Action Plan Review -->
                                <div class="panel-heading">
                                    <!-- <label id="agenda_label">Add Stock</label> -->
                                    <span id="agenda_error" style="display: block; color:red;"></span>
                                </div>
                                <div class="bs-example">
                                    <div class="Container card-info" id="hold_liquor_details">

                                    </div>
                                </div>
                                <div class="text-center">
                                    <!-- <button style="font-size:18px;" class="addmorerows" title="Add More Content"><span class="fa fa-plus"></span> Add Liquor</button> -->
                                    <!-- <br> -->
                                    <button id="refresh_btn" onclick="window.location.reload()" class="btn btn-danger" type="button">Refresh</button>
                                    <button id="createmeetingbtn" onclick="loadValues()" class="btn btn-success" type="button">Place Order</button>
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
                            <div style="height:45px; width:100%;" class="text-center bg-danger">
                                <p class="pt-2 text-white"><b>Order Details:- </b></p>
                                <div id="brewery_name"></div>
                            </div>
                            <table class="table table-hover small-text table-striped" id="tb2">
                                <thead>
                                    <tr class="tr-header">
                                        <th>Sr.No</th>
                                        <th>Liquor Name</th>
                                        <!-- <th style="width:150px;">Liquor Type</th> -->
                                        <th style="width:150px;">Base Price</th>
                                        <!-- <th style="width:150px;">Avaialble Stock</th> -->
                                        <th style="width:150px;">Quantity</th>
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
<script src="<?= base_url() ?>assets/js/module/brewery/orderToBrewery.js"></script>
<script>
    $('input[type=radio][name=type]').change(function() {

    });

    // var invoice_no;
    var sales_type;
    var select_type;
    // var purpose;
    var validation = true;
    var mainArr = [];

    function stock_modal() {
        var row;
        var mainTable = $('#tb2');
        var tr = mainTable.find('tbody tr');

        tr.each(function() {
            tmpArr = {};
            console.log(tmpArr);
            tmpArr.liquor_entity_id = $(this).find('td:eq(1) option:selected').val();
            tmpArr.liquor_entity_name = $(this).find('td:eq(1) option:selected').text();
            tmpArr.selling_price = $(this).find('td:eq(2) input:text').val();
            // tmpArr.available_stock = $(this).find('td:eq(3) input:text').val();
            tmpArr.quantity = $(this).find('td:eq(3) input:text ').val();
            tmpArr.total = $(this).find('td:eq(4) input:text').val();
            // console.log(tmpArr);
            var liquor_name = tmpArr.liquor_entity_name.split('--');
            let row_2 = document.createElement('tr');
            let row_2_data_1 = document.createElement('td');

            let row_2_data_2 = document.createElement('td');
            row_2_data_2.innerHTML = liquor_name[0];


            let row_2_data_3 = document.createElement('td');
            row_2_data_3.innerHTML = tmpArr.selling_price;

            // let row_2_data_4 = document.createElement('td');
            // row_2_data_4.innerHTML = tmpArr.available_stock;

            let row_2_data_4 = document.createElement('td');
            row_2_data_4.innerHTML = tmpArr.quantity;

            let row_2_data_6 = document.createElement('td');
            row_2_data_6.innerHTML = tmpArr.total;

            row_2.appendChild(row_2_data_1);
            row_2.appendChild(row_2_data_2);
            row_2.appendChild(row_2_data_3);
            row_2.appendChild(row_2_data_4);
            row_2.appendChild(row_2_data_6);

            row = row_2;
            $(".table_body").append(row);
            // console.log(row); 
        });
        // console.log(row_2);
        // document.getElementById('confirmStockModal').appendChild(table);

        $("#myModal").modal('show');

        // $(".table_body").html('');

        $(".modal").on("hidden.bs.modal", function() {
            $(".table_body").html("");
            // $("#purpose_modal").html("");
            // $("#sales_type_modal").html("");
            // $("#select_type_modal").html("");

        });


    }


    $(function() {
        $(document).ready(function() {
            $("#selling_price, #quantity1").keyup(function() {
                var total = 0;
                var selling_price = parseFloat($("#selling_price").val()).toFixed(2);
                // console.log(selling_price);
                var quantity = parseFloat($("#quantity1").val()).toFixed(2);
                // console.log(quantity);
                var total = selling_price * quantity;
                total = parseFloat(total).toFixed(2);

                $('#total').val(total);
                document.getElementById("total").setAttribute("disabled", "disabled");
            });
        });

        $("#liquor_name1").on("change", function() {
            var liquor_id = $("#liquor_name1").val();
            $("#quantity1").val('');
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
                        // document.getElementById("available_stock").value = res[0].available_quantity;
                        // document.getElementById("available_stock").setAttribute("disabled", "disabled");

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
            // console.log("checks" + validation + " " + field);
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
            var liquor_entity_element = $(this).find('td:eq(1)').children();
            console.log(liquor_entity_element);

            var liquor_entity_field_id = liquor_entity_element[0].id;
            console.log(liquor_entity_field_id);


            // console.log(liquor_entity_field_id);
            tmpArr.selling_price = $(this).find('td:eq(2) input:text').val();
            // tmpArr.available_stock = $(this).find('td:eq(3) input:text').val();
            tmpArr.quantity = $(this).find('td:eq(3) input:text ').val();
            var quantity_element = $(this).find('td:eq(3)').children();
            var quantity_field_id = quantity_element[0].id;
            // console.log(new_stock_field_id);
            // console.log(liquor_entity_field_id);
            tmpArr.total = $(this).find('td:eq(4) input:text').val();
            // console.log(" i loop" + i);
            // console.log(liquor_entity_element[0].id);
            // console.log(tmpArr.liquor_entity_id.id);

            // var new_stock = `new_stock${i}`;
            isEmpty(quantity_field_id);

            // var liquor_entity_element = `liquor_name${i}`;
            isEmpty(liquor_entity_field_id);
            // isEmpty("purpose");
            // isEmpty("select_type");
            i++;

            liquor_entity_id_array.push(tmpArr.liquor_entity_id);
            mainArr.push(tmpArr);

        });

        if (mainArr.length == 0) {
            swal("Kindly select a brewery");
            return false;
        }

        if (validation == true) {
            unique_liquor_entity_id_array = new Set(liquor_entity_id_array);
            let liquor_entity_id_array_length = liquor_entity_id_array.length;
            let unique_liquor_entity_id_array_size = unique_liquor_entity_id_array.size;
            if (liquor_entity_id_array_length != unique_liquor_entity_id_array_size) {
                swal("You can't select Same Liquor");
                return false;
            }
            stock_modal();
        } else {
            swal("Kindly select a liquor to place the order");
        }
    }

    function store_data() {
        var brewery_id = $("#select_brewery").val();
        document.getElementById("confirm").disabled = true;
        $.ajax({
            url: DOMAIN + 'master/BreweryMaster/insertIntoBreweryOrderLiquorDetails',
            method: 'POST',
            data: {
                csrf_test_name: csrfHash,
                csrfName: csrfName,
                mainArr: mainArr,
                brewery_id: brewery_id
            },
            success: function(response) {

                // alert(response);
                var result = JSON.parse(response);


                // if (response) {

                swal({
                    title: result[0].V_SWAL_TITLE,
                    text: result[0].V_SWAL_TEXT,
                    icon: result[0].V_SWAL_TYPE,

                }).then(function() {
                    if (result[0].V_SWAL_TYPE === 'success') {
                        window.location = DOMAIN + "order/OrderDetails/printReceipt";
                    } else {
                        document.getElementById("confirm").disabled = false;
                    }
                });

                // }
            },
            error: function() {
                alert("Error!!");
            }
        });
    }
</script>