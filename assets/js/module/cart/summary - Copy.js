// event during cart summary
$('.place_order').click(function (event) {
  event.preventDefault()
  // alert('m')
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
      console.log(result)
      if (result[0].V_SWAL_TYPE == 'success') {
        window.location.href = DOMAIN + 'cart/CartDetails/orderCodeDisplay'
      }
    },
    error: function (response) {
      console.log(response)
    }
  })
})

// event during order summary
$('.edit_order_delivery_summary').click(function (event) {
  event.preventDefault()
  var cart_id = extractCartID($(this)[0].id)
  console.log(cart_id)
  $.ajax({
    url: DOMAIN + 'order/OrderDetails/fetchOrderCartDetails',
    method: 'POST',
    data: {csrf_test_name: csrfHash,cart_id: cart_id},
    success: function (response) {
      console.log(response)
    },
    error: function (response) {
      console.log(response)
    }
  })
  // $.ajax({
  //   'url': DOMAIN + 'order/OrderDetails/editDeliveryCart'
  // })

})

$('.submit_order_delivery_summary').click(function (event) {
  event.preventDefault()
  var cart_id = extractCartID($(this)[0].id)
  console.log(cart_id)
  $.ajax({
    url: DOMAIN + 'order/OrderDetails/completeDeliveryProcess',
    method: 'POST',
    data: {csrf_test_name: csrfHash,cart_id: cart_id},
    success: function (response) {
      console.log(response)
    },
    error: function (response) {
      console.log(response)
    }
  })

// $.ajax({
//   'url': DOMAIN + 'order/OrderDetails/completeDelviery'
// })
})

function extractCartID (product_cart_id) {
  var cart_details = product_cart_id.split('_')
  var cart_id = cart_details[0]
  return cart_id
}

function displayMessage (mode) {
  switch (mode) {
    case 'order_summary':
      Swal.fire({
        icon: 'success',
        title: 'Order Code:' + order_code,
        text: 'Order has been placed kindly provide the given order code during purchase',
      // footer: '<a href="">Why do I have this issue?</a>'
      })
      break
    default:
      break
  }
}
