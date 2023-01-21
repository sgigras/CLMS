<table class="table table-hover small-text" id="tb2">
    <thead>
        <tr class="tr-header">
            <th style="width:50px;">Sr.No</th>
            <th style="width:150px;">Liquor Name</th>
            <th style="width:150px;">Selling Price</th>
            <th style="width:150px;">Quantity</th>
            <!-- <th style="width:150px;"></th> -->
            <th style="width:150px;">Total</th>
            <th style="width:150px;"></th>
        </tr>
    </thead>
    <tbody id="liquor_details_body">

    </tbody>
</table>

<script>
    $(function() {
        // console.log(rowCount);
        var rowCounter = 1;
        var table = '';
        var rowCount = '';
        var count = 0;
        $("#liquor_details_body").empty();


        $("#liquor_details_body").append('<tr id="A1">' +
            '<td></td>' +
            '<td style="width:250px;">' +
            '<select name="liquor_name" class="form-control liquor_select" onchange="selectLiquor(this.id)"; id="liquor_name1">' +
            '<option value="" selected="" disabled="">Select Liquor</option>' +
            "<?php
                foreach ($liquor_name_record as $liquor) {
                    echo '<option value=' . $liquor->id . '>' . $liquor->liquor .  '</option>';
                }

                ?>" +
            '</select></td>' +
            '<td style="width:150px">' +
            '<input type="text" name="selling_price" id="selling_price1" class="form-control" disabled>' +
            '</td>' +
            '<td style="width:150px">' +
            '<input type="text" name="quantity" id="quantity1" onkeyup="displayTotalCost(this.id)"; class="form-control" placeholder="Enter Quantity(In bottles)" maxlength="4" onkeypress="return checkValidInputKeyPress(numeric_regex_pattern)">' +
            '</td>' +
            '<td style="width:150px">' +
            '<input type="text" name="total" id="total1"  class="form-control" placeholder="Total" disabled>' +
            '</td>' +
            '<td><a href="javascript:void(0);" style="font-size:18px;width:150px;" id="remove_icon_1" class="addmorerows" title="Add More Content"><span class="fa fa-plus"></span></a></td>' +
            '</tr>');

        table = document.getElementById('tb2');
        console.log(table);
        rowCount = table.rows.length;
        console.log(rowCount)


        $(".liquor_select").change(function() {
            // var liquor_id = $("#liquor_name" + rowCount).val();
            var field_id = $(this).attr('id');
            console.log(field_id);



        });

        $(document).on("click", ".addmorerows", function(event) {

            event.preventDefault();
            table = document.getElementById('tb2');
            console.log(table);
            rowCount = table.rows.length;
            console.log(rowCount)
            console.log("clicked");
            console.log(rowCount);
            // var select=$('')
            rowCounter++;
            var remove_icon_id = rowCounter - 1;
            $(`#remove_icon_${remove_icon_id}`).hide();


            $("#tb2").append('<tr id="A' + rowCounter + '">' +
                '<td>&nbsp;</td>' +
                '<td style="width:200px;"><select class="form-control" onchange="selectLiquor(this.id)"; id="liquor_name' + rowCounter + '">' +
                '<option value="" selected="" disabled="">Select Liquor</option>' +
                "<?php
                    foreach ($liquor_name_record as $liquor) {
                        echo '<option value=' . $liquor->id . '>' . $liquor->liquor .  '</option>';
                    }

                    ?>" +
                '</select></td>' +
                '<td  style="width:100px"><input type="text" name="selling_price" id="selling_price' + rowCounter + '" class="form-control" disabled></td>' +
                // '<td  style="width:100px"><input type="text" name="available_stock" id="available_stock' + rowCount + '" class="form-control" disabled></td>' +
                '<td style="width:100px"><input type="text" name="quantity" maxlength="4"  id="quantity' + rowCounter + '"  onkeyup="displayTotalCost(this.id)"; class="form-control" placeholder="Enter Quantity(In bottles)" onkeypress="return checkValidInputKeyPress(numeric_regex_pattern)"></td>' +
                '<td style="width:100px"><input type="text" name="total" id="total' + rowCounter + '"class="form-control" placeholder="Enter total Stock" disabled> </td>' +
                '<td><a href="javascript:void(0);" class="remove" id="trash_icon_' + rowCounter + '" ><span style="color: red;" class="fa fa-trash"></span></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" style="font-size:18px;" class="addmorerows" id="remove_icon_' + rowCounter + '" title="Add More Content"><span class="fa fa-plus"></span></a></td>' +
                '</tr>'

            );


            $("#liquor_name" + rowCounter).change(function() {
                $("#quantity" + rowCounter).val('');
                $("#total" + rowCounter).val('');
            });

            $("#liquor_name" + rowCounter).select2({
                width: '100%',
                placeholder: 'Select a liquor name'
            });

        });







        $(document).on('click', '.remove', function() {
            var trIndex = $(this).closest("tr").index();
            console.log("INDEX    " + trIndex);
            if (trIndex > 0) {
                $(this).closest("tr").remove();
                var row_id = $("#tb2 tr:last").attr('id');
                var field_id = row_id.replace('A', '');
                $("#remove_icon_" + field_id).show();

            } else {

            }
        });

        $("#liquor_name1").select2({
            width: '100%',
            placeholder: 'Select a liquor name'
        });

        // isEmpty("newStock"+ rowCount);
        $("#liquor_name" + rowCount).on("change", function() {
            console.log("changed" + " " + "liquor_name" + rowCount)
            var liquor_id = $("#liquor_name" + rowCount).val();


            console.log(liquor_id);
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
                    // console.log( res[0].available_quantity);
                    if (res.length > 0) {
                        // document.getElementById("available_stock" + rowCount).value = res[0].available_quantity;
                        // document.getElementById("available_stock" + rowCount).setAttribute("disabled", "disabled");

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

        // $("#selling_price, #quantity" + rowCount).keyup(function() {

        // });
    })


    function displayTotalCost(field_id) {
        var table_row = field_id.replace("quantity", "");
        var total = 0;
        console.log(field_id);

        if ($("#quantity" + table_row).val() !== '' && $("#selling_price" + table_row).val() !== '') {
            var selling_price = parseFloat($("#selling_price" + table_row).val()).toFixed(2);
            var quantity = parseFloat($("#quantity" + table_row).val()).toFixed(2);
            var total = selling_price * quantity;
            total = parseFloat(total).toFixed(2)
            $('#total' + table_row).val(total);
        } else {
            $('#total' + table_row).val(0);
        }
        document.getElementById("total" + table_row).setAttribute("disabled", "disabled");
    }

    function selectLiquor(field_id) {
        var table_row = field_id.replace("liquor_name", "");
        console.log(table_row);

        var liquor_id = $("#" + field_id).val();
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
                if (res.length > 0) {
                    displayTotalCost(`quantity${table_row}`);
                    $("#quantity" + table_row).val(0);
                    $('#total' + table_row).val(0);
                    document.getElementById("selling_price" + table_row).value = res[0].selling_price;
                    document.getElementById("selling_price" + table_row).setAttribute("disabled", "disabled");
                }
            },
            error: function() {
                // alert(DOMAIN + 'custom/Custom/fetchdurations');
                alert("Error!!");

            }
        });
    }
</script>