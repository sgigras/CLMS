$(document).ready(function () {
    // $("#posting_unit").select2({ placeholder: "Please select a posting unit", width: "100%" });
    $("#posting_unit").select2({ width: '100%', placeholder: 'Select a posting unit' });

    $("#status_req").select2({ width: '100%', placeholder: 'Select a registration status' });

    $("#personnel_type").select2({ width: '100%', placeholder: 'select a personnel_type' });

    $("#hold_table").hide();

    $("#personnel_type").change(function () {
        checkInputEmpty("personnel_type", "Kindly select a personnel type");
    })

    $("#status_req").change(function () {
        checkInputEmpty("status_req", "Kindly select a registration type");
    })

    $("#posting_unit").change(function () {
        checkInputEmpty("posting_unit", "Kindly select a posting unit");
    })

    $("#fetchDetails").click(function () {
        var posting_unit = $("#posting_unit").val();
        var mode = $("#status_req").val();
        var personnel_type = $("#personnel_type").val();
        submit_data_error_check = true;

        checkInputEmpty("posting_unit", "Kindly select a posting unit");
        checkInputEmpty("status_req", "Kindly select a registration type");
        checkInputEmpty("personnel_type", "Kindly select a personnel type");
        console.log(submit_data_error_check);
        if (submit_data_error_check) {
            $("#hold_table").hide();
            $.ajax({
                url: DOMAIN + 'user_details/User_details/GetPostingWise',
                method: 'POST',
                async: false,
                data: {
                    mode: mode,
                    posting_unit: posting_unit,
                    personnel_type: personnel_type,
                    csrf_test_name: csrfHash,
                    csrfHash: csrfHash
                },
                success: function (response) {
                    console.log(response)
                    var result = JSON.parse(response);
                    var table_html = '';
                    $("#hold_table").show();
                    var title = posting_unit + " " + mode;
                    $('#master_table').DataTable({
                        destroy: true,
                        data: result,
                        dom: 'Bfrtip',
                        buttons: [{
                            extend: 'excel',
                            className: 'btn btn-info',
                            title: title,
                        }],
                        columnDefs: [{
                            "orderable": true,
                            "targets": [0]
                        }],
                        "columns": [
                            {
                                "data": "rowNumber"
                            }, {
                                "data": "irla"
                            },
                            {
                                "data": "rank"
                            },
                            {
                                "data": "name"
                            },
                            {
                                "data": "mobile_no"
                            },
                            {
                                "data": "email_id"
                            },
                            {
                                "data": "posting_unit"
                            },
                            {
                                "data": "status"
                            }

                        ],
                        "order": [
                            [0, 'asc']
                        ]

                    });

                },
                error: function (error) {
                    console.log(error)
                }
            })
        } else {
            Swal.fire({
                'title': '',
                'text': 'Kindly select all fields',
                'icon': 'warning'
            });
        }

    });



});