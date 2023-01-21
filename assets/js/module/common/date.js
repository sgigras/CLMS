//sets maximum date to date select field
function setMaxDateField(field) {
    var date = new Date();
    var day = date.getDate();
    var month = date.getMonth() + parseInt(1);
    var year = date.getFullYear() - parseInt(18);
    var maxDate = year + '-' + month + '-' + day;
    $("#" + field).prop('max', maxDate);
}

//check whether the given date is maximum then the required date
function validate_max_date(field, error_message, input_day, input_month, input_year) {
    var input_date_value = $("#" + field).val();
    var date = new Date();
    var day = date.getDate() - parseInt(input_day);
    var month = date.getMonth() + parseInt(1);
    var year = date.getFullYear() - parseInt(input_year);
    var maxDate = year + '-' + month + '-' + day;

    if (input_date_value < maxDate) {
        $("#" + field + "_error").html('').hide();
        return true;
    } else {
        $("#" + field + "_error").show().html(error_message);
        return false;
    }
}


function validate_max_min_date(field, error_message, min_day, min_month, min_year, max_day, max_month, max_year) {
    var date = new Date();
    var mi_day = date.getDate() - parseInt(min_day);
    var mi_month = date.getMonth() + parseInt(1);
    var mi_year = date.getFullYear() - parseInt(min_year);

    var ma_day = date.getDate() - parseInt(max_day);
    var ma_month = date.getMonth() + parseInt(1);
    var ma_year = date.getFullYear() - parseInt(max_year);

    var min_date = mi_year + '-' + mi_month + '' + mi_day;

}


//function createCurrentDate() {
//
//}