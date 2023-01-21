/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * to use common jquery function 
 */

// $(document).functio
$(function () {
  // to remove liquor from cart

  $('.edit_order_delivery_summary').click(function (event) {
    event.preventDefault()
    // alert('clicked')
    // var cart_id = $('#serchCartDetails').val()
    // var order_code = $('#searchCartDetails option:selected').text()
    // var page_mode = 'order_summary_delivery'
    // $.ajax({
    //   url: DOMAIN + 'order/OrderDetails/modifyCartDetails',
    //   method: 'POST',
    //   data: {cart_id: cart_id,order_code: order_code,page_mode: page_mode},
    //   success: function (response) {
    //     console.log(response)
    //   },
    //   error: function (error) {
    //     console.log(response)
    //   }
    // })
  })

  $('.remove_liquor').click(function (event) {
    event.preventDefault()
    var display_field = extractDisplayFieldId($(this)[0].id.split('_')); // btn id to know increment decrement and id value 12_1_increment_btn 12-product id 1- cart id
    console.log(display_field + 'remove_filed_id')
    var remove_flag = $('#' + display_field + '_remove_flag').val();
    var quantity_element_table_display = $('#' + display_field + '_remove_flag').parent();
    var siblingElments = quantity_element_table_display.siblings();
    var liquor_name = siblingElments.eq(2).children().html().replace("<br>", " ");

    if (remove_flag == 0) {
      $('#' + display_field + '_remove_flag').val(1);
      var swal_title = "Liquor removed";
      var swal_text = liquor_name + " has been removed from the cart";
      var fa_icon = '<i style="color: blue;text-shadow: 1px 1px 1px #ccc; font-size: 2.5em;" class="fas fa-plus-circle" aria-hidden="true"></i>';
    } else {
      $('#' + display_field + '_remove_flag').val(0);
      var swal_title = "Liquor Added";
      var swal_text = liquor_name + " has been added to the cart";
      var fa_icon = '<i style="color: red;text-shadow: 1px 1px 1px #ccc; font-size: 2.5em;" class="fa fa-times-circle-o" aria-hidden="true"></i>';
    }

    console.log(display_field);

    console.log(fa_icon);
    console.log("#" + display_field + "_remove_liquor");

    $("#" + display_field + "_remove_liquor").html(fa_icon);

    Swal.fire({ title: swal_title, text: swal_text, icon: 'success' });
    // var element = siblingElments[2].children();



    const Liqour_Toast = Swal.mixin({
      toast: true,
      position: 'center',
      showConfirmButton: false,
      timer: 2000
    })

    // var toast_message = product_name + ' has been added to the cart';

    Liqour_Toast.fire({
      icon: 'success',
      title: swal_text
    })


    // console.log(quantity_element_table_display);
    // console.log(siblingElments);
    console.log(element);
  })

  $('.increment_decrement_quantity_button').click(function (event) {
    event.preventDefault()

    var field_details = $(this)[0].id.split('_'); // btn id to know increment decrement and id value 12_1_increment_btn 12-product id 1- cart id
    var display_field = field_details[0] + '_' + field_details[1] // to create display field id input type text

    var quantity = parseInt($('#' + display_field + '_quantity_display').val())
    if (field_details.includes('increment')) {
      ++quantity
      $('#' + display_field + '_quantity_display').val(quantity)
    } else {
      quantity = (quantity > 1) ? --quantity : 1
      $('#' + display_field + '_quantity_display').val(quantity)
    }

    var quantity_element_table_display = $('#' + display_field + '_quantity_display').parentsUntil('tr').last()
    var single_unit_element = quantity_element_table_display.next()
    var total_quantity_element = single_unit_element.next().children() // total_quantity text
    single_unit_value = single_unit_element.text()

    // console.log(quantity_element_table_display)
    // console.log(single_unit_element)
    // console.log(total_quantity_element)
    // console.log(single_unit_element)
    // console.log('in')
    changeCartTotalValues(quantity, single_unit_value, total_quantity_element) // to change cart table total quantity
    if ($('#' + display_field + '_cart_quantity_in_lot_buttons').is(':visible')) { // checks whether cart is created for entity or consumer and accordingly adds new quantity into cart total
      var quantity_element = '_cart_quantity_in_lot_buttons'
      fetchCartTotalQunatityElements(quantity, display_field, quantity_element)
    } else if ($('#' + display_field + '_cart_quantity_buttons').is(':visible')) {
      var quantity_element = '_cart_quantity_buttons'
      fetchCartTotalQunatityElements(quantity, display_field, quantity_element)
    }
  })
})

function extractDisplayFieldId(field_details) {
  var display_field = field_details[0] + '_' + field_details[1] // to create display field id input type text
  return display_field
}

function fetchCartTotalQunatityElements(quantity, display_field, quantity_element) {
  console.log(quantity)
  $('#' + display_field + quantity_element).text(quantity)

  var quantity_element_cart_total = $('#' + display_field + quantity_element)
  var single_unit_element = quantity_element_cart_total.next() // single_unit_text
  var total_quantity_element = single_unit_element.next() // total_quantity text

  var quantity_value = quantity_element_cart_total.text()

  var single_unit_value = single_unit_element.text()
  changeCartTotalValues(quantity_value, single_unit_value, total_quantity_element)
}
// qun
function changeCartTotalValues(quantity, single_unit, total_quantity_element) {
  var total_quantity = parseInt(quantity) * parseFloat(single_unit)
  total_quantity_element.text(total_quantity.toFixed(2))
}

function enable_disable_select_option(field_id, option_id, prop_status) {
  var placeholder = $('#' + field_id).next().find('.select2-selection__placeholder').text()
  $('#' + field_id).find('option').removeAttr('disabled')
  $('#' + field_id).select2()
  $('#' + field_id + '_' + option_id).prop('disabled', prop_status)
  $('#' + field_id).select2({ width: '100%', placeholder: placeholder })
}

// to upload image files on server and display to the server
function readURL(input) {
  var input_field_id = input.id
  var image_field_id = input_field_id.replace('_input_file', '')
  if (input.files && input.files[0]) {
    var reader = new FileReader()
    reader.onload = function (e) {
      $('#' + image_field_id + '_img').attr('src', e.target.result)
      $('#' + image_field_id + '_label').css('padding-right', '0px')
      $('#' + image_field_id + '_label').text(input.files[0].name)
      $('#' + image_field_id + '_h').val(input.files[0].name)
    }
    reader.readAsDataURL(input.files[0])
  }
}

// function uploda

// var files = uploadfile
//    var error = ''
//    var count
//    var form_data = new FormData()
//    for (count = 0; count < files.length; count++)
//    {
//        var name = files[count].name
// //   alert(name)
// //   alert(name)
//        var extension = name.split('.').pop().toLowerCase()
//        if (jQuery.inArray(extension, ['jpg', 'png', 'PNG', 'jpeg', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt']) == -1)
//        {
//            error += "Invalid File ,file type not supported"
//        } else
//        {
//            form_data.append("files[]", files[count])
//        }
//        console.log(form_data)
//    }
