$(document).ready(function() {
    $("#outlet_type").select2({ width: '100%', placeholder: 'Select an outlet' });
    $("#battalion_unit").select2({ width: '100%', placeholder: 'Select an Unit' });
    $("#select_state").select2({ width: '100%', placeholder: 'Select a state' });
    $("#select_city").select2({ width: '100%', placeholder: 'Select a city' });
    $("#select_chairman").select2({ width: '100%', placeholder: 'Select a chairman' });
    $("#select_executive").select2({ width: '100%', placeholder: 'Select a executive' });
    $("#select_supervisor").select2({ width: '100%', placeholder: 'Select a supervisor' });
    $("#select_distrubuting_authority").select2({ width: '100%', placeholder: 'Select a distributor authorithy' });
    $("#select_distributor_name").select2({ width: '100%', placeholder: 'Select a distributor name' });
    // $("select_distrubuting_authority").hide();
    $('#distrubuting_authority').hide();
    $('#distrubuting_authority_1').hide();



    $("#outlet_type").change(function() {
        checkInputEmpty("outlet_type", "Kindly select a outlet");
        var outlet_type_val = $("#outlet_type option:selected").text();
        if (outlet_type_val == 'Depot') {
            $('#distribute_authority').hide();
        } else {
            $('#distribute_authority').show();
        }
        distribution_authority_enable_disable_option(outlet_type_val);
        create_canteen_name();
    });

    $("#battalion_unit").change(function() {
        checkInputEmpty("battalion_unit", "Kindly select a unit");
        create_canteen_name();
    });

    $("#select_chairman").select2({
        width: '100%',
        placeholder: "Enter Irla No",
        minimumInputLength: 5,
        allowClear: true,
        ajax: {
            url: DOMAIN + 'master/CanteenMaster/getUsers',
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
                console.log(data);
                return {

                    results: data

                };
            },
            cache: true
        }
    });



    $("#select_executive").select2({
        width: '100%',
        placeholder: "Enter Irla No",
        minimumInputLength: 5,
        allowClear: true,
        ajax: {
            url: DOMAIN + 'master/CanteenMaster/getUsers',
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
                console.log(data);
                return {

                    results: data

                };
            },
            cache: true
        }
    });


    $("#select_supervisor").select2({
        width: '100%',
        placeholder: "Enter Irla No",
        minimumInputLength: 5,
        allowClear: true,
        ajax: {
            url: DOMAIN + 'master/CanteenMaster/getUsers',
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
                console.log(data);
                return {

                    results: data

                };
            },
            cache: true
        }
    });


    $("#select_distrubuting_authority").change(function() { //on selection of distrubition authority fetching the respective distributor
        console.log("in change func");
        // checkInputEmpty("select_distrubuting_authority", "Kindly select a distributoin authority");
        var distrubtor_authority_text = $("#select_distrubuting_authority option:selected").text();
        var distrubtor_authority = $("#select_distrubuting_authority").val();

        // console.log(distrubtor_authority);

        // let valueSelect = $('#select_distributor_name_1');
        // alert(distrubtor_authority);
        // console.log(dist)
        // $("#select_distributor_name_1").val('').trigger('change');
        outlet_type_enable_disable_option(distrubtor_authority_text);
        $.ajax({
            url: DOMAIN + 'master/CanteenMaster/getDistrubutors',
            method: 'POST',
            data: { csrf_test_name: csrfHash, distrubtor_authority: distrubtor_authority },
            success: function(response) {
                var result = JSON.parse(response);
                // console.log(result);
                var distrubtor_name_html = '<option></option>';
                for (var i = 0; i < result.length; i++) {
                    distrubtor_name_html += "<option value='" + result[i].id + "'>" + result[i].name + "</option>"
                }
                $('#distrubuting_authority').show();

                if (distrubtor_authority == 1 || distrubtor_authority == '1') {
                    $('#distrubuting_authority').show();
                    $('#distrubuting_authority_1').hide();
                    // valueSelect.attr("multiple", "multiple");
                    $("#select_distributor_name_1").html(distrubtor_name_html);
                    // $('#select_distributor_name_1').select2();
                } else {
                    $('#distrubuting_authority_1').show();
                    $('#distrubuting_authority').hide();
                    // $('#select_distributor_name_1').multiselect();
                    $("#select_distributor_name").html(distrubtor_name_html);
                    // valueSelect.removeAttr("multiple", "multiple");
                }
            },
            errror: function() {
                swal("Can't reach to the server");
            }
        });
    });

    if ($("#select_distrubuting_authority").val().trim() != "") {
        console.log($("#select_distrubuting_authority").val().trim());
        $("#select_distrubuting_authority").trigger("change");
    }


    //on change of state select fetch city details
    // $("#select_state").change(function () {
    //     // alert("Hello world ");
    //     checkInputEmpty("select_state", "Kindly select a state");
    //     var state_id = $(this).val();
    //     $("#select_city").val('').trigger('change');
    //     $.ajax({
    //         url: DOMAIN + 'master/CanteenMaster/getCityList',
    //         method: 'POST',
    //         data: { csrf_test_name: csrfHash, state_id: state_id },
    //         success: function (response) {
    //             var result = JSON.parse(response);
    //             var city_option_html = '<option></option>';
    //             for (var i = 0; i < result.length; i++) {
    //                 city_option_html += "<option value='" + result[i].id + "'>" + result[i].city_district_name + "</option>"
    //             }
    //             $("#select_city").html(city_option_html);
    //         },
    //         errror: function () {
    //             swal("Can't reach to the server");
    //         }
    //     });
    // });


    $("#select_state").change(function() {
        // alert("Please select");
        checkInputEmpty("select_state", "Kindly select a state");
        var state_id = $(this).val();
        $("#select_city").val('').trigger('change');
        $.ajax({
            url: DOMAIN + 'master/CanteenMaster/getCityList',
            method: 'POST',
            data: { csrf_test_name: csrfHash, state_id: state_id },
            success: function(response) {
                var result = JSON.parse(response);
                var city_option_html = '<option></option>';
                for (var i = 0; i < result.length; i++) {
                    city_option_html += "<option value='" + result[i].id + "'>" + result[i].city_district_name + "</option>"
                }
                $("#select_city").html(city_option_html);
            },
            errror: function() {
                swal("Can't reach to the server");
            }
        });
    });



    $("#select_city").change(function() {
        checkInputEmpty("select_city", "Kindly select a city");
    });

    $("#select_chairman").change(function() {
        checkInputEmpty("select_chairman", "Kindly select a chairman");
        // disableEnableSelectOptionUser("select_chairman", "select_exceutive");
        // disableEnableSelectOptionUser("select_chairman", "select_supervisor");
    });


    $("#select_executive").change(function() {
        checkInputEmpty("select_executive", "Kindly select a executive");
        // disableEnableSelectOptionUser("select_executive", "select_supervisor");
        // disableEnableSelectOptionUser("select_executive", "select_chairman");
    });


    $("#select_supervisor").change(function() {

        checkInputEmpty("select_supervisor", "Kindly select a supervisor");
        // disableEnableSelectOptionUser("select_supervisor", "select_exceutive");
        // disableEnableSelectOptionUser("select_supervisor", "select_chairman");
    });


    $("#select_distributor_name").change(function() {
        // checkInputEmpty("select_distributor_name", "Kindly select a distributor name");
    });

    $("#canteen_name").change(function() {
        nameValidation();
    });

    $("#address").change(function() {
        checkInputEmpty("address", "Kindly enter a address");
    });
    //    $("#")


    function disableEnableSelectOptionUser(select_value_element, action_select_element) {
        var option_value = $("#" + select_value_element).val();
        var prop_status = true;
        enable_disable_select_option(action_select_element, option_value, prop_status);
    }


    //    $("#addEntity").click(function (e) {
    //        var me = $(this);
    //        sumit_data_error_check = true;
    //        e.preventDefault();
    //        var canteenDetailsObj = {};
    //        canteenDetailsObj.outlet_type = $("#outlet_type").val().trim();
    //        canteenDetailsObj.state = $("#select_state").val().trim();
    //        canteenDetailsObj.city = $("#select_city").val().trim();
    //        canteenDetailsObj.chairman = $("#select_chairman").val().trim();
    //        canteenDetailsObj.executive = $("#select_executive").val().trim();
    //        canteenDetailsObj.supervisor = $("#select_supervisor").val().trim();
    //        canteenDetailsObj.distrubuting_authority = $("#select_distrubuting_authority").val().trim();
    //        canteenDetailsObj.distributor_name = $("#select_distributor_name_1").val().trim();
    //        canteenDetailsObj.canteen_name = $("#canteen_name").val().trim();
    //        canteenDetailsObj.address = $("#address").val().trim();
    //        canteenDetailsObj.submit = true;
    //        canteenDetailsObj.csrf_test_name = csrfHash;
    //        checkInputEmpty("outlet_type", "Kindly select a outlet");
    //        checkInputEmpty("select_state", "Kindly select a state");
    //        checkInputEmpty("select_city", "Kindly select a city");
    //        checkInputEmpty("select_chairman", "Kindly select a chairman");
    //        checkInputEmpty("select_executive", "Kindly select a executive");
    //        checkInputEmpty("select_supervisor", "Kindly select a supervisor");
    //        checkInputEmpty("select_distrubuting_authority", "Kindly select a distribution authority");
    //        checkInputEmpty("select_distributor_name_1", "Kindly select a distribution name");
    //        nameValidation();
    //        addressValidation();
    ////        console.log(sumit_data_error_check);
    ////console.log()
    //
    //
    //        var action_url = $("#entity_add").attr('action');
    //        console.log(action_url)
    //        if (sumit_data_error_check) {
    //            $.ajax({
    //                url: action_url,
    //                method: 'POST',
    //                data: canteenDetailsObj,
    //                datatype: 'JSON',
    //                success: function (response) {
    //                    console.log(response);
    //                },
    //                error: function (response) {
    //                    console.log(response);
    //                }
    //            });
    //        }
    //    });
    $("#entity_details").submit(function(event) {
        var entity_form_data = $(this);
        // alert(entity_form_data);
        // console.log(entity_form_data.serialize());
        event.preventDefault();
        sumit_data_error_check = true;
        var canteenDetailsObj = {};
        //checkInputEmpty("outlet_type", "Kindly select a outlet");
        checkInputEmpty("select_state", "Kindly select a state");
        checkInputEmpty("select_city", "Kindly select a city");
        checkInputEmpty("select_distrubuting_authority", "Kindly select a distribution authority");
        checkInputEmpty("select_distributor_name", "Kindly select a distribution name");
        checkDistinctPersonnel();
        nameValidation();
        addressValidation();
        //        alert('called');

        if (sumit_data_error_check) {
            var action_url = $("#entity_details").attr('action');
            $.ajax({
                url: action_url,
                method: 'POST',
                data: entity_form_data.serialize(),
                dataType: 'JSON',
                beforeSend: function() {
                    $("#addEntity").attr('disabled', 'disabled');
                },
                success: function(response) {
                    //                alert('here');
                    $("#addEntity").removeAttr('disabled');
                    console.log(response);
                    if (!response.success) {

                        $.each(response.messages, function(key, value) {
                            //                        console.log(key);
                            //                        console.log(value);
                            var element = $('#' + key);
                            element.closest('input,select')
                                .removeClass('is-invalid')
                                .addClass(value.length > 0 ? 'is-invalid' : 'is-valid').find('.text-danger').remove();
                            //                        element.next('p').remove();
                            element = $('#' + key + '_error');

                            element.html(value);

                        });
                        //                }
                    } else {
                        if (response.model_response[0].V_SWAL_TYPE === 'success') {
                            Swal.fire({
                                title: response.model_response[0].V_SWAL_MESSAGE,
                                text: "",
                                icon: response.model_response[0].V_SWAL_TYPE,
                                showConfirmButton: true
                                    //                            toast: true
                            }).then(function(isConfirm) {
                                if (isConfirm) {
                                    window.location.href = DOMAIN + 'master/CanteenMaster';
                                }
                            });
                        } else {
                            Swal.fire({
                                title: response.model_response[0].V_SWAL_MESSAGE,
                                text: "",
                                icon: response.model_response[0].V_SWAL_TYPE,
                                showConfirmButton: true
                                    //                            toast: true
                            });
                        }
                    }

                },
                error: function(xhr, status, error) {
                    $("#addEntity").removeAttr('disabled');
                    //                    var err = eval("(" + xhr.responseText + ")");
                    //                    console.log(status);
                    //                    console.log(error);
                    //                    alert(err.Message);
                }

            });
        } else {
            Swal.fire({
                title: 'Kindly check all fields',
                text: "",
                icon: 'info',
                showConfirmButton: true
            });
        }



    });
});


function distribution_authority_enable_disable_option(outlet) {
    // alert(outlet);
    $("#select_distrubuting_authority").find('option').removeAttr("disabled");
    $("#select_distrubuting_authority").select2();
    switch (outlet) {
        case 'Sub-Depot':
            $("#select_distrubuting_authority_1").prop("disabled", true);
            $("#select_distrubuting_authority_2").prop("disabled", true);
            $("#select_distrubuting_authority_3").prop("disabled", false);
            // $("#select_distrubuting_authority_4").prop("disabled", true);
            $("#select_distrubuting_authority").select2({ width: '100%', placeholder: 'Select a distributor authorithy' });
            break;

        case 'Depot':
            $("#select_distrubuting_authority_1").prop("disabled", true);
            $("#select_distrubuting_authority_2").prop("disabled", true);
            $("#select_distrubuting_authority_3").prop("disabled", true);
            // $("#select_distrubuting_authority_4").prop("disabled", true);
            $("#select_distrubuting_authority").select2({ display: "none", width: '100%', placeholder: 'Select a distributor authorithy' });
            break;

            // case 'FHQ':
            //     $("#select_distrubuting_authority_1").prop("disabled", false);
            //     $("#select_distrubuting_authority_2").prop("disabled", true);
            //     $("#select_distrubuting_authority_3").prop("disabled", true);
            //     $("#select_distrubuting_authority_4").prop("disabled", true);
            //     $("#select_distrubuting_authority").select2({ width: '100%', placeholder: 'Select a distributor authorithy' });
            //     break;

        case 'Club':
            $("#select_distrubuting_authority_1").prop("disabled", true);
            $("#select_distrubuting_authority_2").prop("disabled", false);
            $("#select_distrubuting_authority_3").prop("disabled", true);
            // $("#select_distrubuting_authority_4").prop("disabled", true);
            $("#select_distrubuting_authority").select2({ width: '100%', placeholder: 'Select a distributor authorithy' });
            break;

        default:
            $("#select_distrubuting_authority_1").prop("disabled", false);
            $("#select_distrubuting_authority_2").prop("disabled", false);
            $("#select_distrubuting_authority_3").prop("disabled", false);
            // $("#select_distrubuting_authority_4").prop("disabled", false);
            $("#select_distrubuting_authority").select2({ width: '100%', placeholder: 'Select a distributor authorithy' });
            $("#select_distrubuting_authority").val('2').trigger('change');
            break;
            // code block
    }
}

function create_canteen_name() {
    var outlet_type_val = $("#outlet_type option:selected").text();
    var battalion_unit = $("#battalion_unit option:selected").text();
    var canteen_name = battalion_unit + ' ' + outlet_type_val;
    $("#canteen_name").val(canteen_name);
}

function outlet_type_enable_disable_option(outlet) {
    $("#outlet_type").find('option').removeAttr("disabled");
    // $("#outlet_type").find('option').removeAttr("disabled");

    // alert(outlet);
    switch (outlet) {
        case 'Sub-Depot':
            $("#outlet_type_2").prop("disabled", false);
            $("#outlet_type_3").prop("disabled", true);
            $("#outlet_type_4").prop("disabled", true);
            $("#outlet_type_5").prop("disabled", false);
            $("#outlet_type").select2({ width: '100%', placeholder: 'Select a distributor authorithy' });
            break;
        case 'Depot':
            $("#outlet_type_2").prop("disabled", false);
            $("#outlet_type_3").prop("disabled", true);
            $("#outlet_type_4").prop("disabled", true);
            $("#outlet_type_5").prop("disabled", true);
            $("#outlet_type").select2({ width: '100%', placeholder: 'Select a distributor authorithy' });
            break;
        case 'FHQ':
            $("#outlet_type_2").prop("disabled", false);
            $("#outlet_type_3").prop("disabled", false);
            $("#outlet_type_4").prop("disabled", false);
            $("#outlet_type_5").prop("disabled", true);
            $("#outlet_type").select2({ width: '100%', placeholder: 'Select a distributor authorithy' });
            //            $("#select_distrubuting_authority").val('1').trigger('change');
            break;
        case 'Brewery':
            $("#outlet_type_2").prop("disabled", false);
            $("#outlet_type_3").prop("disabled", false);
            $("#outlet_type_4").prop("disabled", false);
            $("#outlet_type_5").prop("disabled", true);
            $("#outlet_type").select2({ width: '100%', placeholder: 'Select a distributor authorithy' });
            //            $("#select_distrubuting_authority").val('1').trigger('change');
            break;
        default:
            $("#outlet_type_2").prop("disabled", true);
            $("#outlet_type_3").prop("disabled", false);
            $("#outlet_type_4").prop("disabled", true);
            $("#outlet_type_5").prop("disabled", true);
            $("#outlet_type").select2({ width: '100%', placeholder: 'Select a distributor authorithy' });
            break;
            // code block
    }
}

function nameValidation() {
    if (!checkInputEmpty("canteen_name", "Kindly enter a entity name")) {

        if (checkValidInput("canteen_name", "Only alphanumeric characters are allowed", alphanumeric_space_regex_pattern)) {
            // console.log(alphanumeric_space_regex_pattern);
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function addressValidation() {
    if (!checkInputEmpty("address", "Kindly enter a address")) {

        if (checkValidInput("address", "Only alphanumeric characters are allowed", alphanumeric_space_regex_pattern)) {
            //            console.log(alphanumeric_space_regex_pattern);
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function checkDistinctPersonnel() {
    var chairman_empty_flag = checkInputEmpty("select_chairman", "Kindly select a chairman");
    var executive_empty_flag = checkInputEmpty("select_executive", "Kindly select a executive");
    var supervisor_empty_flag = checkInputEmpty("select_supervisor", "Kindly select a supervisor");

    if (!chairman_empty_flag && !executive_empty_flag && !supervisor_empty_flag) {

        var chairman = $("#select_chairman").val();
        var supervisor = $("#select_executive").val();
        var executive = $("#select_supervisor").val();

        if ((chairman === supervisor) && (chairman === executive)) {
            sumit_data_error_check = false;
            Swal.fire({
                title: "You have selected same chairman,supervisor,executive",
                text: "Kindly select different chairman,supervisor,executive",
                icon: 'info',
                showConfirmButton: true
            });
        } else {

            if (chairman === supervisor) {
                sumit_data_error_check = false;
                Swal.fire({
                    title: "You have selected same chairman and supervisor",
                    text: "Kindly select different chairman and supervisor",
                    icon: 'info',
                    showConfirmButton: true
                });
            } else if (supervisor === executive) {
                sumit_data_error_check = false;
                Swal.fire({
                    title: "You have selected same supervisor and executive",
                    text: "Kindly select different supervisor and executive",
                    icon: 'info',
                    showConfirmButton: true
                });
            }
        }
    }
}