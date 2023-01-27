$("#joining_date").ready(function() {
    var today = new Date().toISOString().split('T')[0];
    $("#joining_date")[0].setAttribute('max', today);
    $("#retirement_date")[0].setAttribute('max', today);
});
$("#personnel_no").on("change", function() {
    validPersonnelNo("personnel_no", "Personnel No.", 3, 30);
    var personnel_no = $("#personnel_no").val();
    var personnel_no_value = $("#personnel_no option:selected").text().trim().split(' - ');
    if (personnel_no_value.length == 1) {
        $("#retiree_name").val('');
        $("#mobile_no").val('');
        $("#email_id").val('');
        $("#date_of_birth").val('');
        $("#joining_date").val('');
        $("#retirement_date").val('');
        $("#ppo_no").val('');
        $("#adhaar_card_no").val('');
        $("#personnel_photo").attr('src', 'uploads/retiree_details/user_preview.png')
        $("#card_photo").attr('src', 'uploads/retiree_details/user_preview.png');
        $("#ppo_photo").attr('src', 'uploads/retiree_details/user_preview.png');
    } else {
        $.ajax({
            url: DOMAIN + 'user_details/RegisterRetiree/checkRetireeData',
            method: 'POST',
            data: {
                csrf_test_name: csrfHash,
                csrfName: csrfName,
                perssonel_no: personnel_no
            },
            success: function(response) {
                var data_result = JSON.parse(response);
                var status = data_result.status;
                var message = data_result.message;
                var result = data_result.user_details;
                if (status === 'success') {
                    if (result.length > 0) {
                        $("#date_of_birth").val(result[0].date_of_birth);
                        $("#retirement_date").val(result[0].retirement_date);
                        $("#joining_date").val(result[0].joining_date);
                        $("#retiree_name").val(result[0].firstname);
                        $("#ppo_no").val(result[0].ppo_no);
                        $("#email_id").val(result[0].email);
                        $("#mobile_no").val(result[0].mobile_no);
                        $("#aadhar_card_no").val(result[0].adhaar_card);
                        $("#address").val(result[0].permanent_address);
                        $("#posting_unit_type").val(result[0].UnitName);
                        $("#force_type").val(result[0].capf_force);
                        $("#rank").val(result[0].user_rank);
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
                alert("Error!!");
            }
        });
    }
});

function isValueAvailable(field) {
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
            var result = JSON.parse(response);
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
    $("#personnel_no").select2({
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
                    csrf_test_name: csrfHash
                }
                return query;
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
    $("#register_retiree").click(function(event) {
        validPersonnelNo("personnel_no", "Personnel No.", 3, 30);
        event.preventDefault();
        submit_data_error_check = true;
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
        var id_card_photo_valiadtion = checkInputEmpty("id_card_photo", "Kindly select ID card photo");
        var mobile_no_len_validation = checkExactLengthValidation("mobile_no", "Kindly enter valid Mobile No.", 10);
        var aadhar_card_no_valiation = checkExactLengthValidation("aadhar_card_no", "Kindly enter valid aadharcard no.", 12);
        var email_id_valiation = checkValidInput("email_id", "Kindly enter valid email id", email_regex_pattern);
        var address_valiation = checkValidInput("address", "Kindly enter valid address", address_regex_pattern);
        var address = $("#address").val();
        nameValidation();
        var date_of_birth_validation_func = validateDateOfBirth();
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
            var date_of_birth = new Date($("#date_of_birth").val());
            var date_of_joinig = new Date($("#joining_date").val());
            var date_of_retirement = new Date($("#retirement_date").val());
            if ((date_of_birth >= date_of_joinig) || (date_of_birth >= date_of_retirement)) {
                Swal.fire("Warning", "Kindly check all date fields,Date of birth,joining, retirement ", "error");
                submit_data_error_check = false;
            }
            if ((date_of_joinig >= today) || (date_of_retirement >= today)) {
                Swal.fire("Warning", "Date of joining and date of retirement should not be greater than today's date", "error");
                submit_data_error_check = false;
            }
            if (date_of_retirement <= date_of_joinig) {
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
        }
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
                    if ($('#retiree_name').prop('disabled')) {
                        updateDatabase();
                    } else {
                        loadData();
                    }
                }
            })
        }
    });
});

function loadData() {
    $("#register_retiree").prop('disabled', true);
    var form = $('form');
    var form_content = form.serialize();
    var formData = new FormData();
    var personnel_photo = $("#personnel_photo")[0].files[0];
    console.log('Inside Load Data');
    console.log(personnel_photo);
    var ppo_photo = $("#ppo_photo")[0].files[0];
    var id_card_photo = $("#id_card_photo")[0].files[0];
    formData.append('form_content', form_content);
    formData.append('personnel_photo', personnel_photo);
    formData.append('ppo_photo', ppo_photo);
    formData.append('id_card_photo', id_card_photo);
    formData.append('csrf_test_name', csrfHash);
    $.ajax({
        url: DOMAIN + 'user_details/RegisterRetiree/addRetiree',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            var result = JSON.parse(response);
            Swal.fire({
                title: result[0].V_SWAL_TITLE,
                text: "",
                icon: result[0].V_SWAL_TYPE,
                showConfirmButton: true
            }).then(function() {
                window.location.reload();
            });
        },
        error: function(xhr, status, error) {
            $("#addEntity").removeAttr('disabled');
        }
    });
}

function validPersonnelNo(field, message_text, min_digits, max_digits) {
    if (checkValidInput(field, `${message_text} should be only numeric`, numeric_regex_pattern)) {
        if (checkMaxMinLengthValidation(field, `${message_text} should be ${min_digits} to ${max_digits} digits`, min_digits, max_digits)) {
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
    var dateString = document.getElementById("date_of_birth").value;
    if (dateString) {
        var parts = dateString.split("-");
        var dtDOB = new Date(parts[0] + "-" + parts[1] + "-" + parts[2]);
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
            if (dtCurrent.getMonth() < dtDOB.getMonth()) {
                return false;
            }
            if (dtCurrent.getMonth() == dtDOB.getMonth()) {
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
    var after_personnel_no = personnel_no_value[0];
    var retiree_name = personnel_no_value[1].split('. ');
    var name = retiree_name[1];
    var mobile_no = $("#mobile_no").val();
    var email_id = $("#email_id").val();
    var date_of_birth = $("#date_of_birth").val();
    var posting_unit_type = $("#posting_unit_type option:selected").text();
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
    formData.append('personnel_no', after_personnel_no);
    formData.append('ppo_photo', ppo_photo);
    formData.append('id_card_photo', id_card_photo);
    formData.append('csrf_test_name', csrfHash);
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
    $.ajax({
        url: DOMAIN + 'user_details/RegisterRetiree/updateRetiree',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            var result = JSON.parse(response);
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

//Photos Upload
var personnel_photo_h = document.getElementById('personnel_photo_h');
var ppo_photo_h = document.getElementById('ppo_photo_h');
var id_card_photo_h = document.getElementById('id_card_photo_h');

function checkIt(field, photo) {
    if (photo.value.length) {
        $("#" + field + "_h").val(' ');
        $("#" + field + "_label").text('Choose File');
        $("#" + field + "_img").attr('src', DOMAIN + 'uploads/retiree_details/user_preview.png');
    }
    document.body.onfocus = null;
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