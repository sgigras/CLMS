$('.place_order').click(function (event) {
  event.preventDefault()

  var product_cart_id = $('.place_order')[0].id
  var cart_details = product_cart_id.split('_')
  var cart_id = cart_details[0]
  //   console.log(cart_id)
  $.ajax({
    url: DOMAIN + 'cart/CartDetails/placeOrder',
    method: 'POST',
    beforeSend: function () {},
    data: {csrf_test_name: csrfHash,cart_id: cart_id},
    success: function (response) {
      console.log(response)
      var result = JSON.parse(response)
      // console.log(result)
      if (result[0].V_SWAL_TYPE = 'success') {
        window.location.href = DOMAIN + 'cart/CartDetails/orderCodeDisplay'
      }
    },
    error: function (response) {
      console.log(response)
    }
  })
})
