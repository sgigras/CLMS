var sales_type;

$(document).ready(function () {
    $("#hold_liquor_table").hide();
    var sales_type = 'user';
    $('.sales_type').click(function () {
        sales_type = $("input[type=radio][name=sales_type]:checked").val();
        $("input[type=radio]").prop("disabled", true);
        if (sales_type == "user") {
            document.getElementById("title").innerHTML = "Select Regimental/IRLA : ";
            $("#select_type").val('').trigger("change");
            $(".hold_purpose").hide();
            $("#hold_liquor_table").hide();
            $("#purpose").val("issue beer");
        }
        else {
            $("#purpose").val("");
            $(".hold_purpose").show();
            $("#select_type").val('').trigger("change");
            document.getElementById("title").innerHTML = "Select Mess : ";
            $("#hold_liquor_table").hide();
        }

        $.ajax({
            url: DOMAIN + 'additional_sheets/AdditionalSheetsController/fetchLiquorList',
            method: 'POST',
            dataType: 'HTML',
            async: false,
            data: { sales_type: sales_type, csrf_test_name: csrfHash },
            success: function (response) {
                $("#hold_liquor_table").show();
                $("#hold_liquor_details").html(response)
            },
            error: function (error) {
                console.log(error);
                Swal.fire({
                    title: 'Warning',
                    text: 'Something went wrong',
                    icon: 'warning'
                });
            }
        });

    });


    $('#select_type').select2({
        placeholder: "Select",
        createTag: function (term, data) {

            // sales_type = $("input[type=radio][name=sales_type]:checked").val();
            // console.log(sales_type);
            var value = term.term;
            return {
                id: value,
                text: value
            };
        },

        ajax: {
            url: DOMAIN + "/additional_sheets/AdditionalSheetsController/getSalesTypeData",
            dataType: 'json',
            delay: 250,
            method: 'POST',
            data: function (params) {
                var query = {
                    search: params.term,
                    //transporterid: transporterid,
                    // ats_sign: ats_sign
                    sales_type: sales_type,
                    csrf_test_name: csrfHash
                }
                // Query parameters will be ?search=[term]&type=public
                return query;
            },
            processResults: function (data) {
                // console.log(data);
                return {

                    results: data

                };
            },
            cache: true
        },
        minimumInputLength: 4
    });


});


$(document).on('keyup', '#select_type', function () {
    // $("input[type=radio]").prop("disabled", false);
    // sales_type = $("input[type=radio][name=sales_type]:checked").val();
    // $("input[type=radio]").prop("disabled", true);
    // alert(sales_type);
});

// var value = $("input[type=radio][name=contact]:checked").val();
// alert(value);


// var strUser = report_type.value;
// console.log(report_type);
// $.ajax({
//     url: DOMAIN + "/canteen_report/Canteen_report_master/getCanteenData",
//     dataType: 'html',
//     data: {
//         report_type: report_type,
//         csrf_test_name: csrfHash,
//         csrfName: csrfName
//     },
//     method: 'POST',
//     success: function (response) {
//         // console.log(response);
//         $('#tableView').html(response);
//     },
//     error: function (error) {
//         console.log(error);
//     }
// });