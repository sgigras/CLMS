var cart_id = 0
$('#select_order').select2({'width': '100%','placeholder': 'search order code'})

$('#select_order').change(function () {
  checkInputEmpty('select_order', 'Kindly select a order code')
})

$('#searchCartDetails').click(function (event) {
  event.preventDefault()

  var order_code = $('#select_order option:selected').text()
  var cart_id = $('#select_order').val()
  // var page_label=
  console.log(checkInputEmpty('select_order', 'Kindly select a order code'))
  // console.log(csrfHash)
  if (!checkInputEmpty('select_order', 'Kindly select a order code')) {
    $.ajax({
      url: DOMAIN + 'order/OrderDetails/fetchOrderDetails',
      method: 'POST',
      data: {csrf_test_name: csrfHash,page_label: page_label,order_code: order_code,cart_id: cart_id},
      // dataType: 'html',
      success: function (response) {
        console.log(response)
        $('#hold_cart_collapsible_view').html(response)
      },
      error: function (error) {
        console.log('error')
      }
    })
  }
})

function edit_order_delivery (cart_id) {
  var cart_id = $('#select_order').val()
  var order_code = $('#select_order option:selected').text()
  var page_mode = 'order_summary_delivery'
  console.log(cart_id + '----' + order_code + '--' + page_mode)
  $.ajax({
    url: DOMAIN + 'order/OrderDetails/modifyCartDetails',
    method: 'POST',
    data: {csrf_test_name: csrfHash,cart_id: cart_id,order_code: order_code,page_mode: page_mode},
    success: function (response) {
      // console.log(response)
      window.location.href = DOMAIN + 'order/OrderDetails/fetchCartDetails'
    },
    error: function (error) {
      console.log(error)
    }
  })
}

function submit_order_delivery (cart_id) {
  // alert('clicked')
  var cart_id = $('#select_order').val()
  var order_code = $('#select_order option:selected').text()
  var page_mode = 'order_summary_delivery'
  console.log(cart_id + '----' + order_code + '--' + page_mode)
  // console.log(response)
  $.ajax({
    url: DOMAIN + 'order/OrderDetails/completeDeliveryProcess',
    method: 'POST',
    data: {csrf_test_name: csrfHash,cart_id: cart_id,order_code: order_code,page_mode: page_mode},
    success: function (response) {
      console.log(response)
    },
    error: function (error) {
      console.log(error)
    }
  })
}
