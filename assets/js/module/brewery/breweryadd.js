$(function () {
    //Initialize Select2 Elements
    $('.form-select2').select2()
    $('#brewerystate,#select_brewerystate').select2({
        width: '100%',
        placeholder: 'Select States'
    });
    $('#breweryentity,#select_breweryentity').select2({
        width: '100%',
        placeholder: 'Select Entities'
    });
})
$("#breweryname").change(function () {
    checkInputEmpty("breweryname", "Kindly Enter Brewery Name");
});
$("#breweryaddress").change(function () {
    checkInputEmpty("breweryaddress", "Kindly Enter Brewery Address");
});
$("#contactperson").change(function () {
    checkInputEmpty("contactperson", "Kindly Enter Contact Person Name");
});
$("#mobilenumber").change(function () {
    checkInputEmpty("mobilenumber", "Kindly Enter 10 Digit Mobile Number");
});
$("#emailaddress").change(function () {
    checkInputEmpty("emailaddress", "Kindly Enter Contact Email Address");
});
$("#brewerystate").change(function () {
    var states = $('#brewerystate').val();
    if (states.length > 0 && states[0] !== "") {
        $('#brewerystate_error').html("");
    } else {
        submit_data_error_check = false;
        $('#brewerystate_error').html("Please Select A State");
    }
});
$("#breweryentity").change(function () {
    var breweryentity = $('#breweryentity').val();
    if (breweryentity.length > 0 && breweryentity[0] !== "") {
        $('#breweryentity_error').html("");
    } else {
        submit_data_error_check = false;
        $('#breweryentity_error').html("Please Select An Entity");
    }
});
$('#breweryregistrationfrm').submit(function (e) {
    e.preventDefault();
    submit_data_error_check = true;
    var states = $('#brewerystate').val();
    if (states.length > 0 && states[0] !== "") {
        $('#brewerystate_error').html("");
    } else {
        submit_data_error_check = false;
        $('#brewerystate_error').html("Please Select A State");
    }
    var breweryentity = $('#breweryentity').val();
    console.log(breweryentity);
    if (breweryentity.length > 0 && breweryentity[0] !== "") {
        $('#breweryentity_error').html("");
    } else {
        submit_data_error_check = false;
        $('#breweryentity_error').html("Please Select An Entity");
    }
    checkInputEmpty("breweryname", "Kindly Enter Brewery Name");
    checkInputEmpty("breweryaddress", "Kindly Enter Brewery Address");
    checkInputEmpty("contactperson", "Kindly Enter Contact Person Name");
    checkInputEmpty("mobilenumber", "Kindly Enter 10 Digit Mobile Number");
    checkInputEmpty("emailaddress", "Kindly Enter Contact Email Address");
    var me = $(this);
    if (submit_data_error_check) {
        $.ajax({
            url: me.attr('action'),
            type: 'post',
            data: me.serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success == true) {
                    Swal.fire({
                        title: 'Registered!',
                        text: "The Brewery Has Been Registered Successfully!",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    })
                } else {
                    $.each(response.messages, function (key, value) {
                        console.log(key);
                        console.log(value);
                        var element = $('#' + key);
                        element.closest('input,select')
                            .removeClass('is-invalid')
                            .addClass(value.length > 0 ? 'is-invalid' : 'is-valid').find('.text-danger').remove();
                        element.next('p').remove();
                        if (element.prop('type') == 'select-multiple') {
                            if (key == "brewerystate") {
                                var spanelement = $('#' + key + '_error');
                                spanelement.next('p').remove();
                                spanelement.after(value);
                            }
                            if (key == "breweryentity") {
                                var spanelement = $('#' + key + '_error');
                                spanelement.next('p').remove();
                                spanelement.after(value);
                            }
                        } else {
                            element.after(value);
                        }
                    })
                }
            }
        });
    } else {
        Swal.fire({
            title: 'Kindly check all fields',
            text: "",
            icon: 'info',
            timer: 1500,
            showConfirmButton: false
        });
    }
});
