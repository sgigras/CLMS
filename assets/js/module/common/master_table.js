var field_id = '';
var edit_page = '';
function fetchDetails(page_mode, id) {
    edit_page = page_mode;
    if (page_mode == 'LIQUOR_MAPPING_LIST' || page_mode == 'LIQUOR_STOCK_MAPPING_LIST') {
        field_id = id;
        if (page_mode == 'LIQUOR_MAPPING_LIST') {
            var current_time = new Date();
            var current_hours = current_time.getHours();
            if (current_hours >= 8 && current_hours <= 17) {

                Swal.fire({ title: "Warning", text: "Can edit quantity before 09:00 OR after 18:00 ", icon: "warning" })
                return false;
            }
        }
        fetchLiquorMappingDetails(id);
    } else if (page_mode == 'LIQUOR_RANK_QUOTA_LIST') {
        field_id = id;
        fetchRankQuotaDetails(id);
    }
}


function fetchLiquorMappingDetails(id) {
    $.ajax({
        url: DOMAIN + 'master/Liquor_mapping/fetchLiquorMappingDetails',
        method: 'POST',
        data: { id: id, csrf_test_name: csrfHash, page_mode: edit_page },
        dataType: 'HTML',
        success: function (response) {
            // id = id;
            console.log(response)
            $("#modal_data_body").html(response)
            $("#myModal").modal('show');
            if (edit_page == "LIQUOR_STOCK_MAPPING_LIST") {
                $("#physical_quantity").attr('readonly', 'readonly');
                $("#physical_quantity_display").attr('readonly', 'readonly');
                $("#new_stock").change(function () {
                    // alert("called");
                    var new_stock = $(this).val().trim();
                    var current_stock = $("#physical_quantity_display").val().trim();
                    if (new_stock == '' || new_stock < 0) {
                        Swal.fire({ title: "warning", text: "Kindly enter a valid new stock", icon: "warning" });
                        $("#physical_quantity").val(current_stock);
                        $("#new_stock").val(0);
                    } else {
                        var total_stock = parseInt(new_stock) + parseInt(current_stock);
                        $("#physical_quantity").val(total_stock);
                    }
                });
            }
        },
        error: function (error) {
            console.log(error)
        }
    });
}

function fetchRankQuotaDetails(id) {
    console.log(id);

    var element_table_display = $(`#display_details_${id}`).parentsUntil('tr').last()
    console.log(element_table_display);
    var single_unit_element = element_table_display.siblings();
    var rank_element = single_unit_element[1];
    var quota_element = single_unit_element[2];
    var rank = rank_element.textContent;
    var quota = quota_element.textContent;
    var table_html = `<div  class="row p-5">`
        + `<div  class="col-6">`
        + `<div class="form-group">`
        + `<label>Rank:</label>`
        + `<label  class="form-control">${rank}</label>`
        + `</div> `
        + `</div> `
        + `<div  class="col-6" > `
        + `<div class="form-group"> `
        + `<label> Quota:</label> `
        + `<input type = "text" maxlength=2 id="check_quota" onkeypress = "return checkValidInputKeyPress(numeric_regex_pattern)" onchange="checkQuota(this.value)" class="numeric form-control" value = "${quota}" > `
        + `<span class="text-danger" id="check_quota_error"></span>`
        + `</div > `
        + `</div > `
        + `</div > `;
    // var table_html = ` `

    // console.log(rank_element.textContent);
    // console.log(quota_element.textContent);
    // console.log(single_unit_element[1]);


    $("#modal_data_body").html(table_html)
    $("#myModal").modal('show');
    // var total_quantity_element = single_unit_element.next().children() // total_quantity text
    // single_unit_value = single_unit_element.text()

}

function editDetails() {
    // $()

    if (edit_page == 'LIQUOR_RANK_QUOTA_LIST') {
        var value = $(`#check_quota`).val();
        if (checkQuota(value)) {
            $.ajax({
                url: DOMAIN + 'user_details/User_details/editRankQuotaDetails',
                method: 'POST',
                data: { id: field_id, mode: 'U', csrf_test_name: csrfHash, quota: value },
                dataType: 'HTML',
                success: function (response) {
                    // id = id;
                    console.log(response)
                    var result = JSON.parse(response);
                    $("#myModal").modal('hide');
                    Swal.fire({
                        title: result[0].V_SWAL_TITLE,
                        text: result[0].V_SWAL_MESSAGE,
                        icon: result[0].V_SWAL_TYPE
                    }).then(function () {
                        if (result[0].V_SWAL_TYPE == 'success') {
                            window.location.reload();
                        }
                    })
                    // $("#modal_data_body").html(response)

                },
                error: function (error) {
                    console.log(error)
                    Swal.fire({
                        title: 'Warning',
                        text: "Can't reach to the server",
                        icon: 'warning'
                    });
                }
            });

        } else {
            Swal.fire({
                title: 'Warning',
                text: 'Kindly enter a quota',
                icon: 'warning'
            });
        }
    } else if (edit_page == "LIQUOR_STOCK_MAPPING_LIST") {

        var physical_quantity = $("#physical_quantity").val().trim();
        var current_stock = $("#physical_quantity_display").val().trim();
        var new_stock = $("#new_stock").val().trim();
        // var reorder_level = $("#reorder_level").val().trim();
        // console.log(physical_quantity);
        // console.log(reorder_level);

        // return false;
        // console.log(physical_quantity);
        if (physical_quantity !== current_stock) {

            $.ajax({
                url: DOMAIN + 'master/Liquor_mapping/addStock',
                method: 'POST',
                data: { id: field_id, mode: 'U', csrf_test_name: csrfHash, physical_quantity: physical_quantity, new_stock: new_stock },
                // dataType: 'HTML',
                success: function (response) {
                    // id = id;
                    console.log(response)
                    var result = JSON.parse(response);
                    $("#myModal").modal('hide');
                    Swal.fire({
                        title: result[0].V_SWAL_TITLE,
                        text: result[0].V_SWAL_MESSAGE,
                        icon: result[0].V_SWAL_TYPE
                    }).then(function () {
                        if (result[0].V_SWAL_TYPE == 'success') {
                            window.location.reload();
                        }
                    })
                    // $("#modal_data_body").html(response)

                },
                error: function (error) {
                    console.log(error)
                }
            });
        } else {
            $("#myModal").modal('hide');
        }


    } else {



        var available_quantity = $("#available_quantity").val().trim();
        var reorder_level = $("#reorder_level").val().trim();
        if (available_quantity !== '' && reorder_level !== '') {

            $.ajax({
                url: DOMAIN + 'master/Liquor_mapping/editLiquorMappingDetails',
                method: 'POST',
                data: { id: field_id, mode: 'U', csrf_test_name: csrfHash, available_quantity: available_quantity, reorder_level: reorder_level },
                dataType: 'HTML',
                success: function (response) {
                    // id = id;
                    console.log(response)
                    var result = JSON.parse(response);
                    $("#myModal").modal('hide');
                    Swal.fire({
                        title: result[0].V_SWAL_TITLE,
                        text: result[0].V_SWAL_MESSAGE,
                        icon: result[0].V_SWAL_TYPE
                    }).then(function () {
                        if (result[0].V_SWAL_TYPE == 'success') {
                            window.location.reload();
                        }
                    })
                    // $("#modal_data_body").html(response)

                },
                error: function (error) {
                    console.log(error)
                }
            });
            // }
        } else {
            Swal.fire({
                title: 'Warning',
                text: 'Kindly enter available quantity and reorder level',
                icon: 'warning'
            });
        }
        // }
    }

}

function checkQuota(value) {
    console.log(value);
    var quota = value.trim();
    // if(value)
    // checkInputEmpty()
    if (quota !== '') {
        $(`#check_quota_error`).html('');
        return true;
    } else {
        $(`#check_quota_error`).html('Kindly enter a quota');
        return false;
    }
}