var cart_id = 0
var details_mode = 'hold_cart_collapsible_view';
// $('#select_order').select2({ 'width': '100%', 'placeholder': 'search order code' })

$('#select_order').change(function () {
  console.log("changed");
  console.log(page_hit);
  if (page_hit !== 'start') {
    $('#searchCartDetails').prop('disabled', true)
  }
  // checkInputEmpty('select_order', 'Kindly select a order code')
})

// console.log('on ready');
$("#select_order").keypress(function(e) {
  // alert("hello")
  console.log('hiii');
  var key = e.keyCode || e.which; // Use either which or keyCode, depending on browser support
  if (!((key >= 65 && key <= 90) || (key >= 97 && key <= 122) || (key>=48 && key<=57) ||key == 32)) {
      $("#error").text("Only Alphabets and Numbers allowed.");
      return false;
  } else {
      $("#select_order").css({
          "border": "1px solid grey"
      });
      $("#error").text("");
      return true;
  }
});

// $("#searchCartDetails").click(function(e) {

//   // console.log("clicked");
//   // console.log(page_hit);
//   var select_order = document.getElementById('select_order').value;
//   console.log(select_order);

//   var regex=/[a-zA-Z0-9]/;
//   console.log(regex.test(select_order));
//   if( regex.test( select_order )) {
//     $("#error").text("input is not alphanumeric.");
//     // e.preventDefault();
//     // return false;
//   }
//   else{
//     $("#error").text("");
//   // console.log('Righttt');
//   // return true;
//   }    
// });

function checkPageHit() {
  console.log(page_hit);
  if (page_hit !== 'start') {
    console.log("inside if");
    $('#select_order').val(order_code)
    $('#searchCartDetails').trigger('click')
  }
}

$('#searchCartDetails').click(function (event) {
  event.preventDefault()
  details_mode = 'hold_cart_collapsible_view';
  fetchOrderDetails(details_mode);
})

function fetchOrderDetails(div) {
  // event.preventDefault()
  console.log(page_hit)
  if (page_hit == 'start') {
    order_code = $('#select_order').val()
    console.log(order_code)
    // cart_id = $('#select_order').val()
    var empty_flag = (order_code !== '') ? false : true
  } else {
    $('#searchCartDetails').prop('disabled', true)
    empty_flag = false
  }
  console.log(order_code)

  if (!empty_flag) {
    $.ajax({
      url: DOMAIN + 'order/OrderDetails/fetchOrderDetails',
      method: 'POST',
      data: { csrf_test_name: csrfHash, page_label: page_label, order_code: order_code },
      dataType: 'html',
      success: function (response) {
        $(`#${div}`).html(response)
      },
      error: function (error) {
        console.log('error')
      }
    })
  } else {
    Swal.fire({
      title: 'Select Order Code',
      text: '',
      icon: 'warning'
    })
  }
}


function edit_order_delivery(cart_id) {
  // console.log('clicked')
  // return false
  // var cart_id = $('#select_order').val()
  var order_code = $('#select_order').val()
  var page_mode = 'order_summary_delivery'
  console.log(cart_id + '----' + order_code + '--' + page_mode)
  $.ajax({
    url: DOMAIN + 'order/OrderDetails/modifyCartDetails',
    method: 'POST',
    data: { csrf_test_name: csrfHash, order_code: order_code, page_mode: page_mode },
    success: function (response) {
      // console.log(response)
      window.location.href = DOMAIN + 'order/OrderDetails/fetchCartDetails'
    },
    error: function (error) {
      console.log(error)
    }
  })
}

function submit_order_delivery() {
  $("#myModal").modal('show');
  $("#confirmDeliveryModal").html('');
  $(".card-info").clone().appendTo("#confirmDeliveryModal");
}

function submit_order_delivery_check() {
  // var cart_id = $('#select_order').val()
  var order_code = $('#select_order').val()
  var page_mode = 'order_summary_delivery'

  // console.log(cart_id + '----' + order_code + '--' + page_mode)
  $.ajax({
    url: DOMAIN + 'order/OrderDetails/completeDeliveryProcess',
    method: 'POST',
    data: { csrf_test_name: csrfHash, order_code: order_code, page_mode: page_mode },
    success: function (response) {
      console.log(response)
      var result = JSON.parse(response)
      var cart_type = result[0].V_CART_TYPE;
      console.log(result);
      // dispatchEvent()
      // return false;
      if (result[0].V_SWAL_TYPE == 'success') {
        Swal.fire({
          title: result[0].V_SWAL_TITLE,
          text: '',
          icon: result[0].V_SWAL_TYPE,
          confirmButtonText: 'Print Receipt',
        }).then(function () {
          if (cart_type === 'consumer') {
            window.location = DOMAIN + "order/OrderDetails/printReceipt";
          }
        });
      } else {
        Swal.fire({
          title: result[0].V_SWAL_TITLE,
          text: result[0].V_SWAL_TEXT,
          icon: result[0].V_SWAL_TYPE,
          confirmButtonText: 'OK',
        });
      }
    },
    error: function (error) {
      console.log(error)
    }
  })
}

