$(document).ready(function () {
    // $("#select_canteen").select2({ width: "100%", placeholder: "Kindly select a canteen" });
    $("#status_req").select2({ width: "100%", placeholder: "Kindly select a verfication status" });


    // $("#hold_table").hide();
    fetchDetails(0);

    $("#fetchDetails").click(function () {
        // var entity_id = $("#select_canteen").val();

        var report_type = $("#status_req").val();
        fetchDetails(report_type);



    })
})

function fetchDetails(report_type) {
    var title = $("#select_canteen option:selected").text();
    if (title == '') {
        title = 'Verfication Pending';
    }

    $.ajax({
        url: DOMAIN + 'user_details/RegistrationReport/getRetireeDetails',
        method: 'POST',
        async: false,
        data: { csrf_test_name: csrfHash, report_type: report_type },
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
                        "data": "rank"
                    }, {
                        "data": "irla"
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
                    },
                    {
                        "data": "retiree_status"
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
}
