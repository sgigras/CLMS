/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * Jitendra Pal
 * to use common validation on all pages 
 */

var alphabet_regex_pattern = /^[A-Za-z]*$/;
var email_regex_pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
var alphabet_space_regex_pattern = /^[A-Za-z ]*$/;
var numeric_regex_pattern = /^[0-9]*$/;
var alphanumeric_regex_pattern = /^[A-Za-z0-9]*$/;
var alphanumeric_space_regex_pattern = /^[A-Za-z0-9 ]*$/;
var text_area_regex_pattern = /^[A-Za-z0-9 ]*$/;
var submit_data_error_check = true;
var address_regex_pattern = /[A-Za-z0-9\.\-\:\\(\)\s\,]/;
var slash_underscore_dash_regex_pattern = /[A-Za-z0-9_\/-]/;
var dash_space_regex_pattern = /[A-Za-z0-9 ]/g;
var hyphen_space_regex_pattern = /[A-Za-z0-9 -]/;
var pipe_underscore_hyphen_regex_pattern = /[A-Za-z0-9-_| ]/;
var email_pattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

function checkValidInputKeyPress(validChars) {
    var keyChar = String.fromCharCode(event.which || event.keyCode);
    return validChars.test(keyChar) ? keyChar : false;
}

function checkValidInput(field, error_message, regexPattern) {
    var valid_string = $("#" + field).val();
    if (regexPattern.test(valid_string)) {
        if ($("#" + field).hasClass("is-invalid")) {
            $("#" + field).removeClass("is-invalid");
        }
        $("#" + field + "_error").html("").hide();

        return true;
    } else {
        //        sumit_data_error_check++;
        submit_data_error_check = false;
        $("#" + field).addClass("is-invalid");
        $("#" + field + "_error").show().html(error_message);
        return false;
    }

}

//developed
function checkInputEmpty(field, error_message) {
    if ($("#" + field).val().trim() === '') {
        $("#" + field + "_error").show().html(error_message);
        ($("#" + field).is('select')) ? '' : $("#" + field).val('');
        $("#" + field).addClass("is-invalid");
        submit_data_error_check = false;
        return true;
    } else {
        if ($("#" + field).hasClass("is-invalid")) {
            $("#" + field).removeClass("is-invalid");
        }
        $("#" + field + "_error").html("").hide();
        return false;
    }
}


function checkUploadImageEmpty(image_field, error_message) {
    console.log(image_field);
    console.log($("#" + image_field + "_h").val());
    if ($("#" + image_field + "_h").val() === '') {
        $("#" + image_field + "_error").html(error_message);
        $("#" + image_field + "_label").addClass('is-invalid');
        submit_data_error_check = false;
        return true;
    } else {
        if ($("#" + image_field + "_label").hasClass('is-invalid')) {
            $("#" + image_field + "_label").removeClass('is-invalid');
        }
        $("#" + image_field + "_error").html("").hide();
        return false;
    }
}

//trying to improve the empty validation function till now not in use under development
//function checkEmptyInput(field, field_type, error_message) {
//    var field_value = (field_type === 'image') ? $("#" + field + "_h").val().trim() : $("#" + field).val().trim();
//    if (field_value === '') {
//        $("#" + field + "_error").html(error_message);
//        submit_data_error_check = false;
//        return true;
//    } else {
//
//    }
//
//}

//function addFieldTypeErrorMessage(field_type) {
//    switch (field_type) {
////        case 'image';
//    }
//}



//check min and max length of field
function checkMaxMinLengthValidation(field, error_message, min_length, max_length) {
    var value = $("#" + field).val().length;
    if (value >= min_length && value <= max_length) {
        $("#" + field + "_error").html("").hide();

        return true;
    } else {
        $("#" + field + "_error").show().html(error_message);
        submit_data_error_check = false;
        return false;
    }
}

//check exact length of field
function checkExactLengthValidation(field, error_message, exact_length) {
    var value = $("#" + field).val().length;
    console.log(value);

    if (value === parseInt(exact_length)) {
        $("#" + field + "_error").html("").hide();
        return true;
    } else {
        $("#" + field + "_error").show().html(error_message);
        submit_data_error_check = false;
        return false;
    }
}

