$(document).ready(function () {
	// var page_mode = 'shopping_cart'

	$(".place_order").click(function (event) {
		event.preventDefault();
		var product_cart_id = $(this)[0].id;
		var cart_details = product_cart_id.split("_");
		// var total_single_bottle_count = 0
		var cart_id = cart_details[0];
		var cart_data = [];
		var liquor_count = 0;
		var liquor_per_bottle = 0;
		var display_field_id = "";
		$("#" + cart_id + "_cart")
			.find("tbody")
			.find("tr")
			.each(function () {
				++liquor_count;
				var cart_row_data = {};
				quantity_liquor_id_cart_id = $(this)
					.find("td:eq(0) input:hidden")
					.val()
					.split("_"); // 12_10_1 quantity_liquor_id_cart_id
				cart_row_data.unit_cost_quantity = $(this)
					.find("td:eq(5)")
					.text()
					.trim();
				cart_row_data.total_cost_quantity = $(this)
					.find("td:eq(6)")
					.text()
					.trim();

				// liquor_per_bottle = liquor_per_bottle + cart_row_data.total_cost_quantity

				// if()

				// 1_14
				// cart_row_data.quantity = quantity_liquor_id_cart_id[0] // quanity at 0th position
				cart_row_data.liquor_id = quantity_liquor_id_cart_id[1];
				cart_row_data.cart_id = quantity_liquor_id_cart_id[2];
				// console.log('#' + cart_row_data.cart_id + '_' + cart_row_data.liquor_id + '_quantity_display')
				display_field_id =
					cart_row_data.liquor_id + "_" + cart_row_data.cart_id;
				// console.log(display_field_id)
				cart_row_data.quantity = $(
					"#" + display_field_id + "_quantity_display"
				).val(); // 10_1_quantity_display cartid_liquorentity_id to get the displayed quantiy
				cart_row_data.remove = $("#" + display_field_id + "_remove_flag").val();
				console.log(cart_row_data.quantity);
				if (cart_row_data.remove == 0) {
					liquor_per_bottle =
						parseInt(liquor_per_bottle) + parseInt(cart_row_data.quantity);
				}
				// cart_row_data.liquor_per_bottle = liquor_per_bottle
				cart_data.push(cart_row_data);

				console.log(cart_data);
			});

		var dataObject = {
			csrf_test_name: csrfHash,
			cart_data: cart_data,
			cart_id: cart_id,
			page_mode: page_mode,
			liquor_per_bottle: liquor_per_bottle,
			liquor_count: liquor_count,
		};

		console.log(dataObject);
		// console.log(cart_type);
		// return false;
		$.ajax({
			url: DOMAIN + "cart/CartDetails/checkOut",
			method: "POST",
			beforeSend: function () {
				// $(this)[0].attr('disabled', 'disabled')
				// $('.continue_shopping')[0].attr('disabled', 'disabled')
			},
			data: {
				csrf_test_name: csrfHash,
				cart_data: cart_data,
				cart_type: cart_type,
				cart_id: cart_id,
				page_mode: page_mode,
				liquor_per_bottle: liquor_per_bottle,
				liquor_count: liquor_count,
			},
			success: function (response) {
				// $('.continue_shopping')[0].removeAttr('disabled')
				// $('.place_order')[0].removeAttr('disabled')
				console.log(response);
				var result = JSON.parse(response);
				console.log(result);
				// // console.log(redirect_url)
				// return false
				if (result[0].V_SWAL_TYPE == "success") {
					window.location.href = DOMAIN + redirect_url;
				} else {
					Swal.fire({
						title: result[0].V_SWAL_TITLE,
						text: result[0].V_SWAL_TEXT,
						icon: result[0].V_SWAL_TYPE,
					});
					return false;
				}
			},
			error: function (response) {
				console.log(response);
			},
		});
	});

	$(".continue_shopping").click(function (event) {
		event.preventDefault();

		var product_cart_id = $(this)[0].id;

		var cart_details = product_cart_id.split("_");

		var cart_id = cart_details[0];

		console.log(cart_id);

		$("#" + cart_id + "_cart")
			.find("tbody")
			.find("tr")
			.each(function () {
				var quantity_cart_id_product_id = $(this)
					.find("td:eq(0) input:hidden")
					.val();
			});

		var dataObject = {
			csrf_test_name: csrfHash,
			cart_id: cart_id,
			delivarable_entity_id: delivarable_entity_id,
			cart_type: cart_type,
			redirect_url: redirect_url,
			page_mode: page_mode
		};
		// console.log(page_mode);
		// console.log(dataObject);
		// return false;
		$.ajax({
			url: DOMAIN + "cart/CartDetails/createContinueSessionShopping",
			method: "POST",
			beforeSend: function () {
				// $(this)[0].attr('disabled', 'disabled')
				// $('.place_order')[0].attr('disabled', 'disabled')
			},
			data: {
				csrf_test_name: csrfHash,
				cart_id: cart_id,
				delivarable_entity_id: delivarable_entity_id,
				cart_type: cart_type,
				redirect_url: redirect_url,
				page_mode: page_mode
			},
			success: function (response) {
				console.log(response);
				var result = JSON.parse(response);
				// console.log(result);
				// return false
				if ((result.V_SWAL_TYPE = "success")) {
					// console.log(DOMAIN + redirect_url)
					window.location.href =
						DOMAIN + "admin/order/Ordering/continueCartShoppingSession";
				}
			},
			error: function (response) {
				console.log(response);
			},
		});
	});
});
