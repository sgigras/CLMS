$("#target :input").prop("disabled", true);
var hrms_id = '';
var id_value = '';
$(".verification_check").click(function () {
    var id = $(this).attr('id').substr('');
    id_value = id.substr(18);
    console.log(id);
    console.log(id_value);
    var hrms_id_field = `hrms_id_${id_value}`;
    hrms_id = $("#" + hrms_id_field).val();
    console.log(hrms_id);
    // return false;
    $.ajax({
        url: DOMAIN + 'user_details/VerifyRetireeDetails/fetchRetireeDetails',
        method: 'POST',
        dataType: 'html',
        data: { csrf_test_name: csrfHash, hrms_id: hrms_id },
        success: function (response) {
            console.log(response)
            // var result = JSON.parse(response);
            // console.log(result)



            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#myModal').modal('show');
            $("#retiree_details_body").html(response);
            $("#target :input").prop("disabled", true);

            $("#approve_retiree").click(function () {
                approveDenyRetiree(1);
            });


            $("#deny_retiree").click(function () {
                approveDenyRetiree(0)
            });

        },
        error: function (error) { }
    });
})

function approveDenyRetiree(action) {

    console.log(id_value);
    console.log(hrms_id);
    $.ajax({
        url: DOMAIN + 'user_details/VerifyRetireeDetails/verifyRetiree',
        method: 'POST',
        data: { csrf_test_name: csrfHash, id: id_value, hrms_id: hrms_id, action: action },
        success: function (response) {
            $('#myModal').modal('hide');
            var result = JSON.parse(response);
            Swal.fire({
                title: result[0].V_SWAL_TITLE,
                text: result[0].V_SWAL_TEXT,
                icon: result[0].V_SWAL_TYPE,
                showConfirmButton: true,
                // toast: true
            }).then(function () {
                if (result[0].V_SWAL_TYPE === 'success') {
                    window.location.reload();
                }
            });

        },
        error: function (error) {

        }
    })
}

