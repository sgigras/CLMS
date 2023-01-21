var sales_type;

$(document).ready(function () {
   
    $('#select_brewery').select2();

    $('#select_brewery').change(function () {

        var selectedbreweryid = this.value;
        var databaseObject = {csrf_test_name: csrfHash, selectedbreweryid: selectedbreweryid};
        $.ajax({
            url: DOMAIN + 'master/BreweryMaster/fetchLiquorList',
            method: 'POST',
            dataType: 'HTML',
            async: false,
            data: databaseObject,
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




