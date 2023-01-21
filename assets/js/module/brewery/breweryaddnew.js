$(function () {
    //Initialize Select2 Elements
    // $('.select2').select2()
    $('#brewerystate,#select_brewerystate').select2({
        width: '100%',
        placeholder: 'Select States'
    });

    // $('#breweryentity,#select_breweryentity').select2({
    //     width: '100%',
    //     placeholder: 'Select Entities'
    // });


})



// $('#brewerystate').on('select2:select', function (e) {
//     var data = e.params.data;
//     console.log(data);
//     $("#brewerystate option:first").removeAttr("selected");
// });



$("#brewery_name").change(function () {
    checkInputEmpty("brewery_name", "Kindly Enter Brewery Name");
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
$("#select_state").change(function () {
    var states = $('#select_state').val();
    if (states.length > 0 && states[0] !== "") {
        // alert('selected');
        $('#select_brewerystate_error').html("");
    } else {
        // alert('not selected');
        submit_data_error_check = false;
        $('#select_brewerystate_error').html("Please Select A State");
    }
});
// $("#select_breweryentity").change(function () {
//     var breweryentity = $('#select_breweryentity').val();
//     if (breweryentity.length > 0 && breweryentity[0] !== "") {
//         // alert('selected');
//         $('#select_breweryentity_error').html("");
//     } else {
//         // alert('not selected');
//         submit_data_error_check = false;
//         $('#select_breweryentity_error').html("Please Select An Entity");
//     }
// });
$('#breweryregistrationfrm').submit(function (e) {
    e.preventDefault();
    submit_data_error_check = true;
    var states = $('#select_state').val();
    console.log(states);

    if (states.length > 0 && states[0] !== "") {
        // alert('selected');
        $('#select_brewerystate_error').html("");
    } else {
        // alert('not selected');
        submit_data_error_check = false;
        $('#select_brewerystate_error').html("Please Select A State");
    }


    // var breweryentity = $('#select_breweryentity').val();
    // console.log(breweryentity);
    // if (breweryentity.length > 0 && breweryentity[0] !== "") {
    //     // alert('selected');
    //     $('#select_breweryentity_error').html("");
    // } else {
    //     submit_data_error_check = false;
    //     // alert('not selected');
    //     $('#select_breweryentity_error').html("Please Select An Entity");
    // }
    checkInputEmpty("brewery_name", "Kindly Enter Brewery Name");
    checkInputEmpty("breweryaddress", "Kindly Enter Brewery Address");
    checkInputEmpty("contactperson", "Kindly Enter Contact Person Name");
    checkInputEmpty("mobilenumber", "Kindly Enter 10 Digit Mobile Number");
    checkInputEmpty("emailaddress", "Kindly Enter Contact Email Address");

    

    var me = $(this);
    // alert("submit");
    console.log("validation check " + submit_data_error_check);
    if (submit_data_error_check) {
        $.ajax({
            url: me.attr('action'),
            type: 'post',
            data: me.serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success == true && response.mode !== "edit") {


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
                } else if (response.model_response) {
                    if (response.model_response) {

                        Swal.fire({
                            title: 'Edited!',
                            text: "Brewery Details Has Been Updated Successfully!",
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
                        Swal.fire({
                            title: 'Internal Server Error!',
                            text: "Sorry! There appears to be problem with server",
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                    }
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

$("#emailaddress").keypress(function(e) {
    var key = e.keyCode || e.which;
    if (!((key >= 64 && key <= 90) || (key >= 97 && key <= 122) ||(key>=48 && key <= 57 )|| key == 32 || key == 45 || key == 46 || key == 95)) {
     
        $("#emailErr").html("Enter valid Email address");

        return false;
    } else {
        $("#emailaddress").css({
            "border": "1px solid grey"
        });
        $("#emailErr").text("");
        return true;
    }
    return isValid;
});

