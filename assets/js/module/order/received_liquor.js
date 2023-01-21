var order_corde = '';
var damage_quantity_flag = 0;
$(function () {
    $("#hold_liquor_button").hide();
    $("#searchCartDetails").click(function () {
        order_corde = $("#order_code").val().trim();
        console.log(order_corde)
        if (order_corde !== "") {
            $.ajax({
                url: DOMAIN + 'order/ReceivedLiquorAPI/fetchReceivedLiquor',
                method: 'POST',
                data: { order_code: order_corde, csrf_test_name: csrfHash },
                async: false,
                dataType: 'HTML',
                success: function (response) {
                    console.log(response);


                    $("#hold_details").html(response)

                    if ($("receive_liquor_table").is(":visible")) {
                        $("#hold_liquor_button").show();
                        $("#searchCartDetails").prop("disabled", true);
                        $("#order_code").prop("disabled", true)

                    } else {
                        $("#hold_liquor_button").hide();
                        $("#searchCartDetails").prop("disabled", false);
                        $("#order_code").prop("disabled", false)
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        } else {
            Swal.fire({
                text: 'Kindly enter a order code',
                title: '',
                icon: 'warning'
            })
        }

    });
});

function checkQuantity(id) {
    console.log(id);
    var damage_quantity = 0;
    var vaild_quantity_flag = false;
    var table_row_id = id.substr(id.lastIndexOf('_') + 1);

    // var recevied_quantity = $(`#received_total_bottles_${table_row_id}`).val().trim();
    var damage_quantity = $(`#damage_quantity_${table_row_id}`).val().trim();
    // console.log(recevied_quantity);
    var receive_total_cost = 0.00;
    var dispatch_total_quantity = parseInt($(`#dispatch_total_quantity_${table_row_id}`).text());

    var recevied_quantity = dispatch_total_quantity;
    var sell_price = parseFloat($(`#unit_sell_price_${table_row_id}`).text()).toFixed(2);

    if (damage_quantity !== '' && damage_quantity !== 0) {
        damage_quantity = parseInt(damage_quantity);
        if (damage_quantity <= dispatch_total_quantity) {
            recevied_quantity = dispatch_total_quantity - damage_quantity;
            if ($("#" + id).hasClass("is-invalid")) {
                $("#" + id).removeClass("is-invalid");
            }
            vaild_quantity_flag = true;

            if (recevied_quantity < dispatch_total_quantity) {
                damage_quantity_flag = 1;
            }
        }
        else {

            $("#" + id).addClass("is-invalid");
            damage_quantity = 0;
        }

        receive_total_cost = parseFloat(dispatch_total_quantity).toFixed(2) * sell_price;
        receive_total_cost = receive_total_cost.toFixed(2);
        // console.log("damage_quantity " + damage_quantity);
        // console.log("receive_total_cost " + receive_total_cost);
        // console.log(recevied_quantity);
        $(`#received_total_bottles_${table_row_id}`).val(recevied_quantity);
        $(`#received_total_bottles_display_${table_row_id}`).text(recevied_quantity);

        $(`#total_recevied_cost_${table_row_id}`).text(receive_total_cost);
        $(`#damage_quantity_${table_row_id}`).val(damage_quantity);
    } else {
        $("#" + id).addClass("is-invalid");
    }

    return vaild_quantity_flag;
}

$("#receive_liquor").click(function () {
    $(this).prop('disabled', true);
    var count = 1;
    var received_liquor = [];
    var submit_data_flag = true;
    damage_quantity_flag = 0;


    $("#receive_liquor_table").find('tbody').find('tr').each(function () {
        // console.log(count++);
        console.log(count);
        var liquor_details = {};

        if (count > 4) {
            var field_sequence = count - 4;
            var validation_check = checkQuantity(`received_total_bottles_${field_sequence}`);
            if (!validation_check) {
                submit_data_flag = false;
            }
            liquor_details.order_details_id = $(`#row_id_${field_sequence}`).val();
            liquor_details.lem_id = $(`#lem_id_${field_sequence}`).val();
            liquor_details.unit_sell_price = $(`#unit_sell_price_${field_sequence}`).val();
            liquor_details.receive_total_quantity = $(`#received_total_bottles_${field_sequence}`).val();
            liquor_details.total_recevied_cost = $(`#total_recevied_cost_${field_sequence}`).text();
            liquor_details.dispatch_total_quantity = $(`#dispatch_total_quantity_${field_sequence}`).text();
            liquor_details.damage_quantity = $(`#damage_quantity_${field_sequence}`).val();
            received_liquor.push(liquor_details);
        }
        count++;

    })

    console.log(received_liquor);
    console.log(submit_data_flag);


    if (received_liquor.length !== 0) {
        if (submit_data_flag) {
            // if(received_liquor.)

            Swal.fire({
                title: 'Are you sure?',
                text: "Receive the delivery",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Submit'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitData(received_liquor);
                } else {
                    $(this).prop('disabled', false);

                }
            })
        } else {
            $(this).prop('disabled', false);
            Swal.fire({
                title: 'Failed',
                text: 'Kindly check all fields',
                icon: 'warning',
            });
        }
    } else {
        $(this).prop('disabled', false);

        Swal.fire({
            title: 'No liquors found',
            text: 'Kindly check Order Code',
            icon: 'warning',
        });
    }
})

function submitData(received_liquor) {
    console.log(received_liquor);
    var order_code = $("#order_code").val();
    $.ajax({
        url: DOMAIN + 'order/ReceivedLiquorAPI/received_liquor',
        method: 'POST',
        async: false,
        data: { received_liquor: received_liquor, csrf_test_name: csrfHash, order_code: order_code, damage_quantity_flag: damage_quantity_flag },
        success: function (response) {
            console.log(response);
            var result = JSON.parse(response);
            // if(result)
            Swal.fire({
                title: result[0].V_SWAL_TITLE,
                text: result[0].V_SWAL_TEXT,
                icon: result[0].V_SWAL_TYPE,
            }).then(function () {
                if (result[0].V_SWAL_TYPE == 'success') {
                    $("#receive_liquor").prop('disabled', true);

                    window.location.reload();
                }
            });

        },
        error: function (error) {
            Swal.fire({
                title: "Warning",
                text: "Can't reach to the server",
                icon: "warning",
            });
            $("#receive_liquor").prop('disabled', true);
            console.log(error)
        }
    });
}