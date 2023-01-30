$(document).ready(function() {
    $("#liquor_type").select2({
        width: "100%",
        placeholder: "Select a liquor type",
    });
    $("#liquor_brand").select2({
        width: "100%",
        placeholder: "Select liquor brand",
    });
    var entity_type = $("#entity_type").val();
    if (entity_type == 2) {
        $("#base_price").val(0);
        $("#base_price_div").hide();
        $("#baseprice_tr").hide();
    }
    $("#base_price").numeric({ decimalPlaces: 2, decimal: ".", negative: false, scale: 2 });
    $("#purchase_price").numeric({ decimalPlaces: 2, decimal: ".", negative: false, scale: 2 });
    $("#sell_price").numeric({ decimalPlaces: 2, decimal: ".", negative: false, scale: 2 });
    $("#select_ml").select2({ width: "100%", placeholder: "Select ml" });
    $("#tax_category").numeric({ decimalPlaces: 2, decimal: ".", negative: false, scale: 2 });
    $("#tax_category1").numeric({ decimalPlaces: 2, decimal: ".", negative: false, scale: 2 });
    $("#tax_category2").numeric({ decimalPlaces: 2, decimal: ".", negative: false, scale: 2 });
    $("#tax_category3").numeric({ decimalPlaces: 2, decimal: ".", negative: false, scale: 2 });
    $("#tax_category4").numeric({ decimalPlaces: 2, decimal: ".", negative: false, scale: 2 });
    $("#tax_category5").numeric({ decimalPlaces: 2, decimal: ".", negative: false, scale: 2 });
    $("#base_price").change(function() {
        var liquor_description_id = $('#liquor_brand').val().trim();
        if (!checkInputEmpty("liquor_brand", "kindly Select a Liquor Brand")) {
            $.ajax({
                url: DOMAIN + "master/Liquor_mapping/getLiquorTaxForPurchasePrice",
                method: "POST",
                data: { csrf_test_name: csrfHash, liquor_description_id: liquor_description_id },
                success: function(response) {
                    var res = JSON.parse(response);
                    var tax_pct = res[0]["tax_value"];
                    var base_price = $("#base_price").val();
                    var purchase_price = display_pct(base_price, tax_pct);
                    $("#liquor_display_base_price").text(base_price);
                    $("#purchase_price").val(purchase_price).trigger('change');
                    $("#purchase_price").attr("disabled", "disabled");
                },
                errror: function() {
                    swal("Can't reach to the server");
                },
            });
        }
    });
    $("#purchase_price").change(function() {
        var liquor_description_id = $('#liquor_brand').val().trim();
        if (!checkInputEmpty("liquor_brand", "kindly Select a Liquor Brand")) {
            $.ajax({
                url: DOMAIN + "master/Liquor_mapping/getLiquorTaxForSellingPrice",
                method: "POST",
                data: { csrf_test_name: csrfHash, liquor_description_id: liquor_description_id, entity_type: entity_type },
                success: function(response) {
                    var purchase_price = $("#purchase_price").val();
                    var selling_price = '';
                    var tax_category = '';
                    var res = JSON.parse(response);
                    if (res.length == 2) {
                        var tax_pct = res[0]["tax_value"];
                        var tax_abs = res[0]["selling_price"];
                        var percentage_price = display_pct(purchase_price, tax_pct);
                        selling_price = display_abs(percentage_price, tax_abs);
                    } else if (res.length == 1) {
                        if (res[0]["tax_type_id"] == 0) {
                            var tax_pct = res[0]["tax_value"];
                            selling_price = display_pct(purchase_price, tax_pct);
                        } else if (res[0]["tax_type_id"] == 1) {
                            var tax_abs = res[0]["tax_value"];
                            selling_price = display_abs(purchase_price, tax_abs);
                        }
                    }
                    var tax_pct = res[0]["tax_value"];
                    var tax_abs = res[0]["selling_price"];
                    var percentage_price = display_pct(purchase_price, tax_pct);
                    selling_price = display_abs(percentage_price, tax_abs);
                    $("#sell_price").attr("disabled", "disabled");
                    $("#liquor_display_purchase").text(purchase_price);
                    $("#liquor_display_sell").text(selling_price);
                    $("#sell_price").val(selling_price);
                    $("#hid_purchase_price").val(purchase_price);
                    $("#hid_sell_price").val(selling_price);
                    $("#sell_price").attr("disabled", "disabled");
                    if (entity_type == 2) {
                        $("#sell_price_error").html("Note:- This Sell Price is Exclusive of CESS & Assessment Fee.");
                    }
                },
                errror: function() {
                    swal("Can't reach to the server");
                },
            });
        }
    });

    function display_pct(price, percentage) {
        var result = parseFloat(price) + ((parseFloat(percentage) / 100) * price);
        return result.toFixed(2);
    }

    function display_abs(price, absolute) {
        var result = parseFloat(price) + parseFloat(absolute);
        return result.toFixed(2);
    }

    function displayProfit(sell_price, purchase_price) {
        var selling_price = parseFloat(sell_price).toFixed(2);
        var purchase_price = parseFloat(purchase_price).toFixed(2);
        profit = selling_price - purchase_price;
        $("#profit").text(profit);
        $("#liquor_display_profit").text(profit);
    }
    $("#holdLiquorImage").css(
        "background-image",
        "url(" + DOMAIN + "liquor_images/liquor_preview.jpg" + ")"
    );
    // on change of liquor select fetch liquor_brand details
    $("#liquor_type").change(function() {
        if (!checkInputEmpty("liquor_type", "Kindly select liquor type")) {
            var liquor_brand_id = $(this).val();
            $("#liquor_display_type ").text($("#liquor_type option:selected").text());
            $("#liquor_brand").val("").trigger("change");
            $.ajax({
                url: DOMAIN + "master/Liquor_mapping/getLiquorBrandList",
                method: "POST",
                data: { csrf_test_name: csrfHash, liquor_brand_id: liquor_brand_id },
                success: function(response) {
                    var result = JSON.parse(response);
                    var liquor_brand_option_html = "<option></option>";
                    for (var i = 0; i < result.length; i++) {
                        liquor_brand_option_html +=
                            "<option value='" +
                            result[i].id +
                            "'>" +
                            result[i].liquor_name +
                            "</option>";
                    }
                    $("#liquor_brand").html(liquor_brand_option_html);
                },
                errror: function() {
                    swal("Can't reach to the server");
                },
            });
        }
    });
    $("#liquor_brand").change(function() {
        var value = $(this).val().split("#");
        checkInputEmpty("liquor_brand", "Kindly select liquor type");
        $("#liquor_display_brand").text($("#liquor_brand option:selected").text());
        var image_path =
            $(this).val() !== "" ?
            DOMAIN + value[1] :
            DOMAIN + "liquor_images/liquor_preview.jpg";
        console.log(image_path);
        $("#liquor_display_image").attr('src', image_path);
        $("#holdLiquorImage").css("background-image", "url(" + image_path + ")");
    });
    // on change of entity select fetch entity_name details
    $("#select_ml").change(function() {
        checkInputEmpty("select_ml", "Kindly select a ml");
        $("#liquor_display_ml").text($("#select_ml option:selected").text());
    });
    $("#moq").change(function() {
        checkInputEmpty("moq", "Kindly enter a lot size");
        $("#liquor_display_lot_size").text($("#moq").val());
    });
    $("#available_quantity").change(function() {
        checkInputEmpty("available_quantity", "Kindly enter a available quantity");
        $("#liquor_display_available_quantity").text($("#available_quantity").val());
    });
    $("#reorder_level").change(function() {
        checkInputEmpty("reorder_level", "Kindly enter a re-order level");
        $("#liquor_display_reorder_level").text($("#reorder_level").val());
    });
    $("#liquor_mapping_details").click(function(event) {
        event.preventDefault();
        submit_data_error_check = true;
        checkInputEmpty("liquor_type", "kindly select a liquor type");
        checkInputEmpty("liquor_brand", "kindly select a liquor brand");
        checkInputEmpty('select_ml', 'kindly select ml')
        checkInputEmpty("moq", "kindly enter a lot size");
        checkInputEmpty("purchase_price", "kindly enter a purchase price");
        checkInputEmpty("available_quantity", "kindly enter a available quantity");
        checkInputEmpty("reorder_level", "kindly enter a re-order level");
        if (submit_data_error_check) {
            console.log(submit_data_error_check);
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#myModal').modal('show');
            // return false;
        } else {
            Swal.fire({
                title: "Incomplete form",
                text: "Kindly check all fields",
                icon: "warning",
            });
        }
    });
});

function submitDetails() {
    $('#myModal').modal('hide');
    var liquor_form_data = $("#liquor_mapping_details_form");
    var action_url = liquor_form_data.attr("action");
    console.log(liquor_form_data.serialize());
    console.log(action_url);
    $.ajax({
        url: action_url,
        method: "POST",
        data: liquor_form_data.serialize(),
        dataType: "JSON",
        beforeSend: function() {
            $(this).attr("disabled", "disabled");
        },
        success: function(response) {
            $(this).removeAttr("disabled");
            console.log(response);
            if (!response.success) {
                $.each(response.messages, function(key, value) {
                    var element = $("#" + key);
                    element
                        .closest("input,select")
                        .removeClass("is-invalid")
                        .addClass(value.length > 0 ? "is-invalid" : "is-valid")
                        .find(".text-danger")
                        .remove();
                    element = $("#" + key + "_error");
                    element.html(value);
                });
            } else {
                if (response.model_response[0].V_SWAL_TYPE === "success") {
                    Swal.fire({
                        title: response.model_response[0].V_SWAL_MESSAGE,
                        text: "",
                        icon: response.model_response[0].V_SWAL_TYPE,
                        showConfirmButton: true,
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            window.location.href = DOMAIN + "admin/order/Liquor_Inventory/stockList";
                        }
                    });
                } else {}
            }
        },
        error: function(response) {
            $(this).removeAttr("disabled");
        },
    });
    console.log(action_url);
}