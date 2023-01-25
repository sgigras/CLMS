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
$('#breweryregistrationfrm').submit(function (e) {
    e.preventDefault();
    submit_data_error_check = true;
    checkInputEmpty("brewery_name", "Kindly Enter Brewery Name");
    checkInputEmpty("breweryaddress", "Kindly Enter Brewery Address");
    checkInputEmpty("contactperson", "Kindly Enter Contact Person Name");
    checkInputEmpty("mobilenumber", "Kindly Enter 10 Digit Mobile Number");
    checkInputEmpty("emailaddress", "Kindly Enter Contact Email Address");
    
    var me = $(this);
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
                            window.location='../../BreweryMaster';
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
                                window.location='../../BreweryMaster';
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
