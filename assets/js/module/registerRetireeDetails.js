// var uploadObject = { uploadSuccess: 0, uploadFailure: 0 };
// $("#personnel_photo_error").hide();
// var pf = $("#personnel_photo_h").val();
var personnel_photo_h = document.getElementById('personnel_photo_h');
var ppo_photo_h = document.getElementById('ppo_photo_h');
var id_card_photo_h = document.getElementById('id_card_photo_h');
var signed_form_photo_h = document.getElementById('signed_form_photo_h');

function checkIt(field, photo) {
    // Check if the number of files
    // is not zero
    if (photo.value.length) {
        // alert('Files Loaded');
        $("#" + field + "_h").val(' ');
        $("#" + field + "_label").text('Choose File');
        $("#" + field + "_img").attr('src', DOMAIN + 'uploads/retiree_details/user_preview.png');
    }
    // if (ppo_photo_h.value.length) {
    //     // alert('Files Loaded');
    //     $("#ppo_photo_h").val(' ');
    //     $("#ppo_photo_label").text('Choose File');
    //     $("#ppo_photo_img").attr('src',DOMAIN + 'uploads/retiree_details/user_preview.png');
    // }
    // if (id_card_photo_h.value.length) {
    //     // alert('Files Loaded');
    //     $("#id_card_photo_h").val(' ');
    //     $("#id_card_photo_label").text('Choose File');
    //     $("#id_card_photo_img").attr('src',DOMAIN + 'uploads/retiree_details/user_preview.png');
    // }
    // if (signed_form_photo_h.value.length) {
    //     // alert('Files Loaded');
    //     $("#signed_form_photo_h").val(' ');
    //     $("#signed_form_photo_label").text('Choose File');
    //     $("#signed_form_photo_img").attr('src',DOMAIN + 'uploads/retiree_details/user_preview.png');
    // }
    document.body.onfocus = null;
    // console.log('checked');
}
$("#personnel_photo").click(function() {
    checkIt('personnel_photo', personnel_photo_h);
});

$("#ppo_photo").click(function() {
    checkIt('ppo_photo', ppo_photo_h);
});
$("#id_card_photo").click(function() {
    checkIt('id_card_photo', id_card_photo_h);
});
$("#signed_form_photo").click(function() {
    checkIt('signed_form_photo', signed_form_photo_h);
});

$("#joining_date").ready(function() {
    var today = new Date().toISOString().split('T')[0];
    // console.log(today);
    $("#joining_date")[0].setAttribute('max', today);
    $("#retirement_date")[0].setAttribute('max', today);
});
// $("#personnel_photo").click(function(){
//     $("#personnel_photo_h").val(' ');
// });
$("#personnel_no").on("change", function() {
    // console.log('change');
    validPersonnelNo("personnel_no", "Personnel No.", 3, 30);
    var personnel_no = $("#personnel_no").val();
    // console.log(personnel_no);
    var personnel_no_value = $("#personnel_no option:selected").text().trim().split(' - ');
    // $("#quantity1").val('');
    // $("#total").val('');
    $("#retiree_name").val('').prop('disabled', false);
    $("#mobile_no").val('').prop('disabled', false);
    $("#email_id").val('').prop('disabled', false);
    $("#date_of_birth").val('').prop('disabled', false);
    $("#joining_date").val('').prop('disabled', false);
    $("#retirement_date").val('').prop('disabled', false);
    $("#ppo_no").val('').prop('disabled', false);
    $("#aadhar_card_no").val('').prop('disabled', false);
    $("#address").val('').prop('disabled', false);
    $("#force_type").val('').trigger('change').prop('disabled', false);
    $("#rank").val('').trigger('change').prop('disabled', false);
    $("#posting_unit_type").val('').trigger('change').prop('disabled', false);
    // $("#retiree_name").val('').prop('disabled', false); 
    // $("#retiree_name").val('').prop('disabled', false); 

    // var retireeObj = {};
    // retireeObj.personnel_no = $("#personnel_no").val();
    // retireeObj.csrf_test_name = csrfName;
    // retireeObj.csrfHash = csrfHash;
    // console.log(retireeObj);
    if (personnel_no_value.length == 1) {
        // $("#personnel_no").val('')
        $("#retiree_name").val('');
        $("#mobile_no").val('');
        $("#email_id").val('');
        $("#date_of_birth").val('');
        // $("#force_type").val('').trigger('change').prop('disabled', false);
        $("#joining_date").val('');

        $("#retirement_date").val('');

        $("#ppo_no").val('');



        $("#adhaar_card_no").val('');

        $("#personnel_photo").attr('src', 'uploads/retiree_details/user_preview.png')
        $("#signed_form_photo").attr('src', 'uploads/retiree_details/user_preview.png');
        $("#card_photo").attr('src', 'uploads/retiree_details/user_preview.png');
        $("#ppo_photo").attr('src', 'uploads/retiree_details/user_preview.png');
    } else {
        $.ajax({
            url: DOMAIN + 'user_details/RegisterRetiree/checkRetireeData',
            method: 'POST',
            // data : JSON.stringify(retireeObj),
            data: {
                csrf_test_name: csrfHash,
                csrfName: csrfName,
                perssonel_no: personnel_no
            },
            success: function(response) {
                var data_result = JSON.parse(response);
                // console.log(result);
                var status = data_result.status;
                // console.log(status);
                var message = data_result.message;
                // console.log(message);
                var result = data_result.user_details;



                if (status === 'success') {
                    if (result.length > 0) {
                        // console.log(result);

                        // var date_of_birth = result[0].date_of_birth;
                        $("#date_of_birth").val(result[0].date_of_birth);

                        // var date_of_birth_format = date_of_birth.split('-').reverse().join('/');
                        // $("#date_of_birth").val(date_of_birth_format);

                        // $("#date_of_birth").val(date_of_birth_format);

                        if (result[0].joining_date !== '' && result[0].joining_date !== 'null' && result[0].joining_date !== null) {
                            var date_of_retirement = result[0].retirement_date;
                            var date_of_retirement_display = date_of_retirement;
                            $("#retirement_date").val(date_of_retirement_display);
                        }

                        if (result[0].joining_date !== '' && result[0].joining_date !== 'null' && result[0].joining_date !== null) {
                            var date_of_joining = result[0].joining_date;
                            var date_of_joining_display = date_of_joining;
                            $("#joining_date").val(date_of_joining_display);
                        }

                        $("#retiree_name").val(result[0].fistname);
                        $("#ppo_no").val(result[0].ppo_no);
                        $("#email_id").val(result[0].email);
                        $("#mobile_no").val(result[0].mobile_no);
                        $("#aadhar_card_no").val(result[0].adhaar_card);
                        $("#address").val(result[0].permanent_address);

                        isValueAvailable('retiree_name');
                        // isValueAvailable('mobile_no');
                        // isValueAvailable('email_id');
                        isValueAvailable('date_of_birth');
                        isValueAvailable('force_type');
                        isValueAvailable('posting_unit_type');
                        isValueAvailable('rank');
                        isValueAvailable('ppo_no');
                        isValueAvailable('aadhar_card_no');
                        // isValueAvailable('address');
                        isValueAvailable('joining_date');
                        isValueAvailable('retirement_date');

                        // if($("#retiree_name").val()){
                        //     console.log($("#retiree_name").val());
                        //     $("#retiree_name").prop('disabled', true);
                        // }
                        // else{
                        //     // Kanwar bhan yadav
                        //     console.log($("#retiree_name").val());
                        //     $("#retiree_name").prop('disabled', false);
                        // }

                        // if (result[0].user_photo !== '' && result[0].user_photo !== 'null' && result[0].user_photo !== null) {
                        //     var user_photo = result[0].user_photo;
                        //     var user_photo_path = IMAGE_UPLOAD_PATH + result[0].user_photo;
                        //     $("#personnel_photo").attr('src', user_photo_path);

                        //     $("#personnel_photo").val(user_photo);
                        // } else {
                        //     $("#personnel_photo").val('');
                        // }

                        // if (result[0].ppo_photo !== '' && result[0].ppo_photo !== 'null' && result[0].ppo_photo !== null) {
                        //     var ppo_photo = result[0].ppo_photo;
                        //     var ppo_photo_path = IMAGE_UPLOAD_PATH + result[0].ppo_photo;
                        //     $("#ppo_photo").attr('src', ppo_photo_path);
                        //     $("#ppo_photo").val(ppo_photo);
                        // } else {
                        //     $("#ppo_photo").val('');
                        // }

                        // if (result[0].card_photo !== '' && result[0].card_photo !== 'null' && result[0].card_photo !== null) {
                        //     var card_photo = result[0].card_photo;
                        //     var card_photo_path = IMAGE_UPLOAD_PATH + result[0].card_photo;
                        //     $("#id_card_photo").attr('src', card_photo_path);
                        //     $("#id_card_photo").val(card_photo);
                        // } else {
                        //     $("#id_card_photo").val('');
                        // }

                        // if (result[0].signed_photo !== '' && result[0].signed_photo !== 'null' && result[0].signed_photo !== null) {
                        //     var signed_form_photo = result[0].signed_photo;
                        //     var signed_form_photo_path = IMAGE_UPLOAD_PATH + result[0].signed_photo;
                        //     $("#signed_form_photo").attr('src', signed_form_photo_path);
                        //     $("#signed_form_photo").val(signed_form_photo);
                        // } else {
                        //     $("#signed_form_photo").val('');
                        // }

                        // // $().val(imageUri.substr(imageUri.lastIndexOf('/') + 1))
                        // $("#valid_upto").val(result[0].adhaar_card)
                        if (result[0].capf_force !== '' && result[0].capf_force !== 'null' && result[0].capf_force !== null) {
                            $("#force_type").val(result[0].capf_force).trigger('change').prop('disabled', true);

                        }
                        $("#posting_unit_type").val(result[0].UnitName).trigger('change').prop('disabled', true);
                        $("#rank").val(result[0].user_rank).trigger('change').prop('disabled', true);

                        // if ($("#posting_unit_type").val()) {
                        //     $("#posting_unit_type").trigger('change').prop('disabled', true);

                        // }
                        // else {
                        //     $("#posting_unit_type").trigger('change').prop('disabled', false);
                        // }
                        isValueAvailableSelect('posting_unit_type');
                        isValueAvailableSelect('rank');
                        isValueAvailableSelect('force_type');


                    }
                } else {
                    Swal.fire({
                        title: 'Warning',
                        text: message,
                        icon: 'warning',
                        showConfirmButton: true,
                        toast: true
                    }).then(function() {

                        window.location.reload();

                    });
                }

            },
            error: function() {
                // alert(DOMAIN + 'custom/Custom/fetchdurations');
                alert("Error!!");

            }
        });
    }


});

function isValueAvailable(field) { //validation function
    // console.log(field);
    // console.log($("#" + field).val());
    // $("#retiree_name").val()
    if ($("#" + field).val()) {
        $("#" + field).prop('disabled', true);
    } else {
        $("#" + field).prop('disabled', false);
    }
}

function isValueAvailableSelect(field) {
    if ($("#" + field).val()) {
        $("#" + field).trigger('change').prop('disabled', true);

    } else {
        $("#" + field).trigger('change').prop('disabled', false);
    }
}

$(function() {

    $.ajax({
        url: DOMAIN + 'user_details/RegisterRetiree/fetchRetireeInitialFormDetails',
        method: 'POST',
        data: {
            csrf_test_name: csrfHash
        },
        success: function(response) {
            // console.log(response);
            var result = JSON.parse(response);
            // console.log(result);
            var posting_unit = result['posting_unit_select_option_array'];
            var force_select = result['force_select_option_array'];
            var rank_select = result['rank_select_option_array'];

            var posting_unit_html = '<option></option>';
            var force_select_html = '<option></option>';
            var rank_select_html = '<option></option>';
            for (var i = 0; i < posting_unit.length; i++) {
                posting_unit_html += `<option value="${posting_unit[i].posting_unit}">${posting_unit[i].posting_unit}</option>`;
            }

            for (var i = 0; i < force_select.length; i++) {
                force_select_html += `<option value="${force_select[i].force_code}">${force_select[i].force_details}</option>`;
            }

            for (var i = 0; i < rank_select.length; i++) {
                rank_select_html += `<option value="${rank_select[i].rank}">${rank_select[i].rank}</option>`;
            }

            $("#force_type").html(force_select_html);
            $("#posting_unit_type").html(posting_unit_html);
            $("#rank").html(rank_select_html);
        },
        error: function(error) {
            // console.log(error);

            Swal.fire({
                title: "Warning",
                text: "Can't reach to the server",
                icon: "warning",
                showConfirmButton: true,
                toast: true
            });
        }

    });


    $("#date_of_birth").change(function() {
        validateDateOfBirth();
    });
    $("#force_type").select2({ width: '100%', placeholder: 'Select a force' });
    $("#posting_unit_type").select2({ width: '100%', placeholder: 'Select a posting unit' });
    $("#rank").select2({ width: '100%', placeholder: 'Select a rank' });

    // $("#irla_no").change(function () {
    //     var irla_no = $(this).val();
    //     console.log(irla_no);
    // });

    $("#personnel_no").select2({
        //var personnel_no_numeric = checkValidInputKeyPress(numeric_regex_pattern),
        width: '100%',
        placeholder: "Enter Personnel No",
        minimumInputLength: 5,
        allowClear: true,
        ajax: {
            url: DOMAIN + 'user_details/RegisterRetiree/fetchRetireeDetails',
            dataType: 'json',
            delay: 250,
            method: 'POST',
            data: function(params) {
                var query = {
                        search: params.term,
                        //                    transporterid: transporterid,
                        // ats_sign: ats_sign
                        csrf_test_name: csrfHash
                    }
                    // Query parameters will be ?search=[term]&type=public
                return query;
            },
            processResults: function(data) {
                // console.log(data);
                return {

                    results: data

                };
            },
            cache: true
        }
    });

    // $("#personnel_no").change(function(){
    //     validPersonnelNo("personnel_no","Personnel No.",3,12);
    // });




    // $("#retiree_details").submit(function (event) {
    $("#register_retiree").click(function(event) {
        // var retiree_form_data = $(this);
        // document.getElementById("register_retiree").disabled = true;
        validPersonnelNo("personnel_no", "Personnel No.", 3, 30);
        event.preventDefault();
        // alert('clicked');
        // return false;
        submit_data_error_check = true;
        // var canteenDetailsObj = {};
        var personnel_no_valiadtion = checkInputEmpty("personnel_no", "Kindly select a personnel no.");
        var ppo_no_valiadtion = checkInputEmpty("ppo_no", "Kindly enter PPO No.");
        var aadhar_card_no_valiadtion = checkInputEmpty("aadhar_card_no", "Kindly enter Aadhar Card No.");
        var retiree_name_valiadtion = checkInputEmpty("retiree_name", "Kindly enter a retiree name");
        var mobile_no_valiadtion = checkInputEmpty("mobile_no", "Kindly enter a mobile no");
        var email_id_valiadtion = checkInputEmpty("email_id", "Kindly enter a email id");
        var date_of_birth_valiadtion = checkInputEmpty("date_of_birth", "Kindly enter a date of birth");
        var posting_unit_type_valiadtion = checkInputEmpty("posting_unit_type", "Kindly select a posting unit");
        var rank_valiadtion = checkInputEmpty("rank", "Kindly select a rank");
        var force_type_valiadtion = checkInputEmpty("force_type", "Kindly select a force");
        var retirement_date_valiadtion = checkInputEmpty("retirement_date", "Kindly select Retirement Date");
        var joining_date_valiadtion = checkInputEmpty("joining_date", "Kindly select Joining Date");
        var address_valiadtion = checkInputEmpty("address", "Kindly select Address");
        var personnel_photo_valiadtion = checkInputEmpty("personnel_photo", "Kindly select personnel photo");
        console.log($("#personnel_photo").val());
        var ppo_photo_valiadtion = checkInputEmpty("ppo_photo", "Kindly select PPO photo");
        // console.log('ppo_photo' + $("#ppo_photo").val());
        var id_card_photo_valiadtion = checkInputEmpty("id_card_photo", "Kindly select ID card photo");
        var signed_form_photo_valiadtion = checkInputEmpty("signed_form_photo", "Kindly select signed form photo");
        var mobile_no_len_validation = checkExactLengthValidation("mobile_no", "Kindly enter valid Mobile No.", 10);
        var aadhar_card_no_valiation = checkExactLengthValidation("aadhar_card_no", "Kindly enter valid aadharcard no.", 12);
        var email_id_valiation = checkValidInput("email_id", "Kindly enter valid email id", email_regex_pattern);
        var address_valiation = checkValidInput("address", "Kindly enter valid address", address_regex_pattern);
        var address = $("#address").val();

        // checkDistinctPersonnel();
        nameValidation();
        // emailidValidation();
        // addressValidation();
        //        alert('called');


        var date_of_birth_validation_func = validateDateOfBirth();

        // console.log(personnel_no_valiadtion);
        // console.log(ppo_no_valiadtion);
        // console.log(aadhar_card_no_valiadtion);
        // console.log(retiree_name_valiadtion);
        // console.log(mobile_no_valiadtion);
        // console.log(email_id_valiadtion);
        // console.log(date_of_birth_valiadtion);
        // console.log(posting_unit_type_valiadtion);
        // console.log(rank_valiadtion);
        // console.log(force_type_valiadtion);
        // console.log(retirement_date_valiadtion);
        // console.log(joining_date_valiadtion);
        // console.log(address_valiadtion);
        // console.log(personnel_photo_valiadtion);
        // console.log(ppo_photo_valiadtion);
        // console.log(id_card_photo_valiadtion);
        // console.log(signed_form_photo_valiadtion);
        // console.log(date_of_birth_validation_func);
        if (personnel_no_valiadtion || ppo_no_valiadtion || aadhar_card_no_valiadtion || retiree_name_valiadtion && mobile_no_valiadtion && email_id_valiadtion && date_of_birth_valiadtion ||
            posting_unit_type_valiadtion || rank_valiadtion || force_type_valiadtion ||
            retirement_date_valiadtion || joining_date_valiadtion || address_valiadtion) {

            Swal.fire({
                title: 'Kindly check all fields',
                text: "",
                icon: 'info',
                showConfirmButton: true
            });

            submit_data_error_check = false;
        } else {

            var today = new Date();
            // console.log(today);
            var date_of_birth = new Date($("#date_of_birth").val());
            // console.log(date_of_birth);
            var date_of_joinig = new Date($("#joining_date").val());
            // console.log(date_of_joinig);
            var date_of_retirement = new Date($("#retirement_date").val());
            // console.log(date_of_retirement);
            // console.log(date_of_birth);
            // console.log(date_of_joinig);
            // console.log(date_of_retirement);
            // if (mobile_no_valiation) {
            //     console.log("kindly enter valid mobile no");
            // }
            if ((date_of_birth >= date_of_joinig) || (date_of_birth >= date_of_retirement)) {
                // console.log('date of birth is greater than date of joining');
                Swal.fire("Warning", "Kindly check all date fields,Date of birth,joining, retirement ", "error");
                submit_data_error_check = false;
            }
            if ((date_of_joinig >= today) || (date_of_retirement >= today)) {
                // console.log('date of birth is greater than date of joining');
                Swal.fire("Warning", "Date of joining and date of retirement should not be greater than today's date", "error");
                submit_data_error_check = false;
            }
            // if ((date_of_birth == date_of_joinig) || (date_of_birth == date_of_retirement) || (date_of_joinig == date_of_retirement)) {
            //     console.log('date of birth is greater than date of joining');
            //     Swal.fire("Warning", "Kindly check all date fields,Date of birth,joining, retirement ", "error");
            //     submit_data_error_check = false;
            // }
            if (date_of_retirement <= date_of_joinig) {
                // console.log('date of retirement is less than date of joining');
                Swal.fire("Warning", "Date of joining cannot be greater than or same as date of retirement ", "error");
                submit_data_error_check = false;

            }

            if (mobile_no_len_validation == false) {
                Swal.fire({
                    title: 'Kindly ',
                    text: "Enter valid Mobile No.",
                    icon: 'info',
                    showConfirmButton: true
                });
            }
            if (aadhar_card_no_valiation == false) {
                Swal.fire({
                    title: 'Kindly ',
                    text: "Enter valid Aadhar Card No.",
                    icon: 'info',
                    showConfirmButton: true
                });
            }
            if (email_id_valiation == false) {
                Swal.fire({
                    title: 'Kindly ',
                    text: "Enter valid email id.",
                    icon: 'info',
                    showConfirmButton: true
                });
            }
            if (address_valiation == false || address.length < 20) {
                Swal.fire({
                    title: 'Kindly ',
                    text: "Enter valid address and address Should have minimum 20 charcters.",
                    icon: 'info',
                    showConfirmButton: true
                });
                if (!address_valiadtion) {
                    var lblError = document.getElementById("adderror");
                    lblError.innerHTML = "Address should have minimum 20 charcters";
                    return false;
                }

            }

            // console.log("address" + address.length);
            // if () {
            //     Swal.fire({
            //         title: 'Kindly',
            //         text: "Address Should have minimum 20 charcters.",
            //         icon: 'info',
            //         showConfirmButton: true
            //     });
            //     return false;
            // }

        }

        // console.log("Mob"+mobile_no_len_validation);



        console.log(submit_data_error_check);
        if (submit_data_error_check) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to submit data",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Submit'
            }).then((result) => {
                if (result.isConfirmed) {

                    // showLoader();
                    if ($('#retiree_name').prop('disabled')) {
                        // console.log("update data")
                        updateDatabase();
                    } else {
                        // console.log('loadData');
                        loadData();
                    }
                    // uploadImages();
                    // updateDatabase()
                }
            })

        }
    });
});
// function loadData() {
//     var validation = true;
//     var personnel_no = $("#personnel_no").val();
//     var personnel_no_value = $("#personnel_no option:selected").text().split(' - ');
//     // console.log(personnel_no_value);
//     var after_personnel_no = personnel_no_value[0];
//     // console.log(personnel_no);
//     // console.log(after_personnel_no);
//     // console.log(personnel_no);
//     var retiree_name = $("#retiree_name").val();
//     var mobile_no = $("#mobile_no").val();
//     var email_id = $("#email_id").val();
//     var date_of_birth = $("#date_of_birth").val();
//     var posting_unit_type = $("#posting_unit_type option:selected").text();
//     var rank = $("#rank option:selected").text();
//     var force_type = $("#force_type").val();
//     var ppo_no = $("#ppo_no").val();
//     var aadhar_card_no = $("#aadhar_card_no").val();
//     var address = $("#address").val();
//     var joining_date = $("#joining_date").val();
//     var retirement_date = $("#retirement_date").val();
//     var personnel_photo = $("#personnel_photo").val();
//     // console.log(personnel_photo);
//     // console.log("after split");
//     var personnel_photo_name = personnel_photo.substring(personnel_photo.lastIndexOf("\\") + 1, personnel_photo.length);
//     // console.log(personnel_photo_path);
//     // var personnel_photo_path = personnel_photo.split('\\');
//     var ppo_photo = $("#ppo_photo").val();
//     var ppo_photo_name = ppo_photo.substring(ppo_photo.lastIndexOf("\\") + 1, p      po_photo.length);
//     var id_card_photo = $("#id_card_photo").val();
//     var id_card_photo_name = id_card_photo.substring(id_card_photo.lastIndexOf("\\") + 1, id_card_photo.length);
//     var signed_form_photo = $("#signed_form_photo").val();
//     var signed_form_photo_name = signed_form_photo.substring(signed_form_photo.lastIndexOf("\\") + 1, signed_form_photo.length);

//     // var action_url = $("#retiree_details").attr('action');
//     // console.log(action_url);
//     // if(){
//     //     Swal.fire({
//     //         title: 'Kindly enter valid Email Id',
//     //         text: "",
//     //         icon: 'info',
//     //         showConfirmButton: true
//     //     });
//     //     validation = false;
//     // }
//     // if (joining_date > retirement_date || joining_date == retirement_date) {
//     //     Swal.fire({
//     //         title: 'Kindly check joining date and retirement date',
//     //         text: "",
//     //         icon: 'info',
//     //         showConfirmButton: true
//     //     });
//     //     validation = false;
//     // }
//     // if (joining_date < date_of_birth || joining_date == date_of_birth) {
//     //     Swal.fire({
//     //         title: 'Kindly check joining date and date of birth',
//     //         text: "",
//     //         icon: 'info',
//     //         showConfirmButton: true
//     //     });
//     //     validation = false;
//     // }
//     // if (validation == true) {
//     $.ajax({
//         url: DOMAIN + 'user_details/RegisterRetiree/addRetiree',
//         method: 'POST',
//         data: {
//             csrf_test_name: csrfHash,
//             personnel_no: after_personnel_no,
//             retiree_name: retiree_name,
//             mobile_no: mobile_no,
//             email_id: email_id,
//             date_of_birth: date_of_birth,
//             posting_unit_type: posting_unit_type,
//             rank: rank,
//             force_type: force_type,
//             ppo_no: ppo_no,
//             aadhar_card_no: aadhar_card_no,
//             address: address,
//             joining_date: joining_date,
//             retirement_date: retirement_date,
//             personnel_photo_name: personnel_photo_name,
//             ppo_photo_name: ppo_photo_name,
//             id_card_photo_name: id_card_photo_name,
//             signed_form_photo_name: signed_form_photo_name
//         },
//         dataType: 'JSON',
//         beforeSend: function () {
//             $("#addEntity").attr('disabled', 'disabled');
//         },
//         success: function (response) {
//             // console.log(response);
//             // var result = JSON.parse(response);
//             //                alert('here');
//             $("#addEntity").removeAttr('disabled');
//             //                console.log(response);
//             // if (!response.success) {

//             //     $.each(response.messages, function (key, value) {
//             //         //                        console.log(key);
//             //         //                        console.log(value);
//             //         var element = $('#' + key);
//             //         element.closest('input,select')
//             //             .removeClass('is-invalid')
//             //             .addClass(value.length > 0 ? 'is-invalid' : 'is-valid').find('.text-danger').remove();
//             //         //                        element.next('p').remove();
//             //         element = $('#' + key + '_error');

//             //         element.html(value);

//             //     });
//             //     //                }
//             // } else {
//             //     if (response.model_response[0].V_SWAL_TYPE === 'success') {
//             //         window.location.href = DOMAIN + 'user_details/RegisterRetiree/';
//             //     } else {
//             Swal.fire({
//                 title: response[0].V_SWAL_TITLE,
//                 text: "",
//                 icon: response[0].V_SWAL_TYPE,
//                 showConfirmButton: true
//                 //toast: true
//             }).then(function() {

//                 window.location.reload();

//             });
//             // }
//             // }

//         },
//         error: function (xhr, status, error) {
//             $("#addEntity").removeAttr('disabled');
//         }

//     });
// }


// function uploadImages() {
//     var personnel_img = $("#personnel_photo").attr('src');
//     var picuploadUrl = DOMAIN + 'user_details/RegisterRetiree/uploadPics';
//     // console.log(profile_image_img);
//     // console.log(picuploadUrl);
//     uploadImage(personnel_img, picuploadUrl, "personnel_pic");
//     var imageInterval = setInterval(function () {
//         if (uploadObject.uploadSuccess > 0) {
//             clearInterval(imageInterval);
//             // updateDatabase();
//         } else if (uploadObject.uploadFailure != 0) {
//             // $("#fa1").hide();
//             uploadObject.uploadSuccess = 0;
//             uploadObject.uploadFailure = 0;
//             $("#update_profile").prop('disabled', false);
//             Swal.fire("Image upload failed", "", "error");
//             clearInterval(imageInterval);
//         }
//     }, 3000);
// }

// // UPLOADING IMAGES
// function uploadImage(fileURL, pic_upload_url, mode) {
//     // console.log(pic_upload_url)
//     var options = new FileUploadOptions();
//     options.fileKey = "file";
//     options.fileName = fileURL.substr(fileURL.lastIndexOf('/') + 1);
//     options.mimeType = "text/plain";

//     var params = {};
//     params.path = options.fileName;
//     params.img_mode = mode;
//     options.params = params;
//     options.chunkedMode = false;
//     var ft = new FileTransfer();
//     ft.upload(fileURL, encodeURI(pic_upload_url), win, fail, options);
// }

// var win = function (r) {
//     // console.log(r);
//     uploadObject.uploadSuccess++;
//     // console.log('success');
// }

// var fail = function (error) {
//     // console.log(error);
//     uploadObject.uploadFailure++;
//     // console.log('failure');
// }

function loadData() {

    $("#register_retiree").prop('disabled', true);
    // var personnel_no = $("#personnel_no").val();
    // var personnel_no = $("#personnel_no").val();
    // var personnel_no_value = $("#personnel_no option:selected").text().split(' - ');
    // event.preventDefault();
    var form = $('form'); // You need to use standard javascript object here
    // console.log(form.serialize());
    var form_content = form.serialize();
    var formData = new FormData();
    // var form_content = formData()
    // console.log(form_content);

    var personnel_photo = $("#personnel_photo")[0].files[0];
    console.log('Inside Load Data');
    console.log(personnel_photo);
    var ppo_photo = $("#ppo_photo")[0].files[0];
    var id_card_photo = $("#id_card_photo")[0].files[0];
    var signed_form_photo = $("#signed_form_photo")[0].files[0];

    formData.append('form_content', form_content);
    formData.append('personnel_photo', personnel_photo);
    formData.append('ppo_photo', ppo_photo);
    formData.append('id_card_photo', id_card_photo);
    formData.append('signed_form_photo', signed_form_photo);
    formData.append('csrf_test_name', csrfHash);
    // formData.append('personnel_no', personnel_no_value);
    // formData.append('retiree_name', retiree_name);
    // formData.append('mobile_no', mobile_no);
    // formData.append('email_id', email_id);
    // formData.append('date_of_birth', date_of_birth);
    // formData.append('force_type', force_type);
    // formData.append('posting_unit_type', posting_unit_type);
    // formData.append('rank', rank);
    // formData.append('ppo_no', ppo_no);
    // formData.append('aadhar_card_no', aadhar_card_no);
    // formData.append('address', address);
    // formData.append('joining_date', joining_date);
    // formData.append('retirement_date', retirement_date);
    // formData.append('personnel_photo', personnel_photo);
    // formData.append('ppo_photo', ppo_photo);
    // formData.append('id_card_photo', id_card_photo);
    // formData.append('signed_form_photo', signed_form_photo);
    // console.log(formData);
    // for (var pair of formData.entries()) {
    //     console.log(pair[0]+ ', ' + pair[1]); 
    // }
    $.ajax({
        url: DOMAIN + 'user_details/RegisterRetiree/addRetiree',
        method: 'POST',
        data: formData,
        // dataType: 'JSON',
        contentType: false,
        processData: false,
        // beforeSend: function () {
        //     $("#addEntity").attr('disabled', 'disabled');
        // },
        success: function(response) {
            // console.log(response);
            var result = JSON.parse(response);
            //                alert('here');
            // $("#addEntity").removeAttr('disabled');
            //                console.log(response);
            // if (!response.success) {

            //     $.each(response.messages, function (key, value) {
            //         //                        console.log(key);
            //         //                        console.log(value);
            //         var element = $('#' + key);
            //         element.closest('input,select')
            //             .removeClass('is-invalid')
            //             .addClass(value.length > 0 ? 'is-invalid' : 'is-valid').find('.text-danger').remove();
            //         //                        element.next('p').remove();
            //         element = $('#' + key + '_error');

            //         element.html(value);

            //     });
            //     //                }
            // } else {
            //     if (response.model_response[0].V_SWAL_TYPE === 'success') {
            //         window.location.href = DOMAIN + 'user_details/RegisterRetiree/';
            //     } else {

            Swal.fire({
                title: result[0].V_SWAL_TITLE,
                text: "",
                icon: result[0].V_SWAL_TYPE,
                showConfirmButton: true
                    //toast: true
            }).then(function() {

                window.location.reload();

            });
            // }
            // }

        },
        error: function(xhr, status, error) {
            $("#addEntity").removeAttr('disabled');
        }

    });
}

function validPersonnelNo(field, message_text, min_digits, max_digits) {
    // console.log("hello");
    if (checkValidInput(field, `${message_text} should be only numeric`, numeric_regex_pattern)) {
        if (checkMaxMinLengthValidation(field, `${message_text} should be ${min_digits} to ${max_digits} digits`, min_digits, max_digits)) {
            // submit_data_error_check  = false;
            return true;

        } else {
            return false;
        }
    } else {
        return false;
    }

}

function validateDateOfBirth() {
    var lblError = document.getElementById("date_of_birth_error");

    //Get the date from the TextBox.
    var dateString = document.getElementById("date_of_birth").value;
    // var regex = /^([0-2][0-9]|(3)[0-1])(-)(((0)[0-9])|((1)[0-2]))(-)\d{4}$/;

    if (dateString) {
        var parts = dateString.split("-");
        var dtDOB = new Date(parts[0] + "-" + parts[1] + "-" + parts[2]);
        // dtDOB.getFullYear();
        var dtCurrent = new Date();
        lblError.innerHTML = "Eligibility 18 years or more than 18 years ONLY.";
        if (dtCurrent.getFullYear() - dtDOB.getFullYear() < 18) {
            submit_data_error_check = false;
            console.log(submit_data_error_check);
            return false;

        }
        if (dtCurrent.getFullYear() - dtDOB.getFullYear() > 150) {
            submit_data_error_check = false;
            console.log(submit_data_error_check);
            return false;

        }
        if (dtCurrent.getFullYear() - dtDOB.getFullYear() == 18) {
            //CD: 11/06/2018 and DB: 15/07/2000. Will turned 18 on 15/07/2018.
            if (dtCurrent.getMonth() < dtDOB.getMonth()) {
                return false;
            }
            if (dtCurrent.getMonth() == dtDOB.getMonth()) {
                //CD: 11/06/2018 and DB: 15/06/2000. Will turned 18 on 15/06/2018.
                if (dtCurrent.getDate() < dtDOB.getDate()) {
                    return false;
                }
            }
        }
        lblError.innerHTML = "";
        return true;
    } else {
        lblError.innerHTML = "Enter date in dd-MM-yyyy format ONLY.";
        return false;
    }
}

function nameValidation() {
    if (!checkInputEmpty("retiree_name", "Kindly enter a  name")) {

        if (checkValidInput("retiree_name", "Only alphanumeric characters are allowed", alphanumeric_space_regex_pattern)) {
            // console.log(alphanumeric_space_regex_pattern);
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function updateDatabase() {
    $("#register_retiree").prop('disabled', true);
    var personnel_no = $("#personnel_no").val();
    var personnel_no_value = $("#personnel_no option:selected").text().split(' - ');
    // console.log(personnel_no_value);
    var after_personnel_no = personnel_no_value[0];
    // console.log(after_personnel_no);
    // var retiree_name = $("#retiree_name").val();
    var retiree_name = personnel_no_value[1].split('. ');
    // console.log(retiree_name[1]);
    var name = retiree_name[1];
    var mobile_no = $("#mobile_no").val();
    var email_id = $("#email_id").val();
    var date_of_birth = $("#date_of_birth").val();
    var posting_unit_type = $("#posting_unit_type option:selected").text();
    // console.log(posting_unit_type);
    var rank = $("#rank option:selected").text();
    var force_type = $("#force_type").val();
    var ppo_no = $("#ppo_no").val();
    var aadhar_card_no = $("#aadhar_card_no").val();
    var address = $("#address").val();
    var joining_date = $("#joining_date").val();
    var retirement_date = $("#retirement_date").val();
    var formData = new FormData();
    var personnel_photo = $("#personnel_photo")[0].files[0];
    var ppo_photo = $("#ppo_photo")[0].files[0];
    var id_card_photo = $("#id_card_photo")[0].files[0];
    var signed_form_photo = $("#signed_form_photo")[0].files[0];

    // formData.append('form_content', form_content);
    formData.append('personnel_no', after_personnel_no);
    formData.append('ppo_photo', ppo_photo);
    formData.append('id_card_photo', id_card_photo);
    formData.append('signed_form_photo', signed_form_photo);
    formData.append('csrf_test_name', csrfHash);
    // formData.append('personnel_no', personnel_no_value);
    formData.append('retiree_name', name);
    formData.append('mobile_no', mobile_no);
    formData.append('email_id', email_id);
    formData.append('date_of_birth', date_of_birth);
    formData.append('force_type', force_type);
    formData.append('posting_unit_type', posting_unit_type);
    formData.append('rank', rank);
    formData.append('ppo_no', ppo_no);
    formData.append('aadhar_card_no', aadhar_card_no);
    formData.append('address', address);
    formData.append('joining_date', joining_date);
    formData.append('retirement_date', retirement_date);
    formData.append('personnel_photo', personnel_photo);
    formData.append('ppo_photo', ppo_photo);
    formData.append('id_card_photo', id_card_photo);
    formData.append('signed_form_photo', signed_form_photo);
    // console.log(formData);

    $.ajax({
        url: DOMAIN + 'user_details/RegisterRetiree/updateRetiree',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            // hideLoader();
            var result = JSON.parse(response);
            // console.log(result);
            Swal.fire({
                title: result[0].V_SWAL_TITLE,
                text: result[0].V_SWAL_TEXT,
                icon: result[0].V_SWAL_TYPE,
            }).then(function() {
                if (result[0].V_SWAL_TYPE === 'success') {
                    window.location.reload();
                }
            });

        },
        error: function(error) {
            // hideLoader();
            // console.log(error)
            Swal.fire({
                title: "Warning",
                text: "Can't reach to the server",
                toast: true,
                icon: "warning",
                showConfirmButton: true,
            });
        }
    })

}