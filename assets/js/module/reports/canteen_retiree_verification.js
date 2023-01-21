$(document).ready(function () {
    $("#select_canteen").select2({ width: "100%", placeholder: "Kindly select a canteen" });
    $("#status_req").select2({ width: "100%", placeholder: "Kindly select a verfication status" });


    $("#hold_table").hide();


    $("#fetchDetails").click(function () {
        var entity_id = $("#select_canteen").val();

        var mode = $("#status_req").val();
        var title = $("#select_canteen option:selected").text() + " - " + $("#status_req option:selected").text();

        $.ajax({
            url: DOMAIN + 'user_details/User_details/fetchVerificationDetails',
            method: 'POST',
            async: false,
            data: { csrf_test_name: csrfHash, mode: mode, entity_id: entity_id },
            success: function (response) {
                console.log(response)
                var result = JSON.parse(response);
                $("#hold_table").show();
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
                            "data": "retiree_irla"
                        },
                        {
                            "data": "retiree_name"
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
                            "data": "retirement_date"
                        },
                        {
                            "data": "approval_from"
                        },
                        {
                            "data": "requested_by"
                        },
                        {
                            "data": "request_time"
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

        });
    })
})