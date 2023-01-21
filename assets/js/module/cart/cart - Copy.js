$(document).ready(function () {
  var page_mode = 'shopping_cart'

  $('.place_order').click(function (event) {
    event.preventDefault()
    var product_cart_id = $(this)[0].id
    var cart_details = product_cart_id.split('_')
    var cart_id = cart_details[0]
    var cart_data = []
    var liquor_count = $('#liquor_count').val()
    var display_field_id = ''
    $('#' + cart_id + '_cart').find('tbody').find('tr').each(function () {
      var cart_row_data = {}
      quantity_liquor_id_cart_id = $(this).find('td:eq(0) input:hidden').val().split('_') // 12_10_1 quantity_liquor_id_cart_id
      cart_row_data.total_cost_quantity = $(this).find('td:eq(5)').text().trim()
      cart_row_data.unit_cost_quantity = $(this).find('td:eq(6)').text().trim()

      // 1_14
      // cart_row_data.quantity = quantity_liquor_id_cart_id[0] // quanity at 0th position
      cart_row_data.liquor_id = quantity_liquor_id_cart_id[1]
      cart_row_data.cart_id = quantity_liquor_id_cart_id[2]
      console.log('#' + cart_row_data.cart_id + '_' + cart_row_data.liquor_id + '_quantity_display')
      display_field_id = cart_row_data.liquor_id + '_' + cart_row_data.cart_id
      console.log(display_field_id)
      cart_row_data.quantity = $('#' + display_field_id + '_quantity_display').val() // 10_1_quantity_display cartid_liquorentity_id to get the displayed quantiy
      cart_row_data.remove = $('#' + display_field_id + '_remove_flag').val()
      cart_data.push(cart_row_data)
    })

    $.ajax({
      url: DOMAIN + 'cart/CartDetails/checkOut',
      method: 'POST',
      beforeSend: function () {},
      data: {csrf_test_name: csrfHash,cart_data: cart_data,cart_id: cart_id,page_mode: page_mode,liquor_count: liquor_count},
      success: function (response) {
        console.log(response)
        var result = JSON.parse(response)
        // return false
        if (result[0].V_SWAL_TYPE = 'success') {
          window.location.href = DOMAIN + 'cart/CartDetails/displaySessionOrder'
        }
      },
      error: function (response) {
        console.log(response)
      }
    })
  })

  $('.continue_shopping').click(function (event) {
    event.preventDefault()

    var product_cart_id = $(this)[0].id

    var cart_details = product_cart_id.split('_')

    var cart_id = cart_details[0]

    console.log(cart_id)

    $('#' + cart_id + '_cart').find('tbody').find('tr').each(function () {
      var quantity_cart_id_product_id = $(this).find('td:eq(0) input:hidden').val()
    })
  })
})
