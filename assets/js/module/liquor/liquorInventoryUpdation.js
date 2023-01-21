$(document).ready(function () {
  $('#liquor_data').select2({width: '100%', placeholder: 'Select a liquor'});

  $('#holdLiquorImage').css('background-image', 'url(' + DOMAIN + 'liquor_images/liquor_preview.jpg' + ')')
  // on change of liquor select fetch liquor_brand details
  $('#liquor_data').change(function () {
    checkInputEmpty('liquor_data', 'Kindly select liquor')
    var liquor_entity_mapping_id = $(this).val()
    $('#liquor_brand').val('').trigger('change')
    $.ajax({
      url: DOMAIN + 'liquor/LiquorInventory/getLiquorPrvAvlQty',
      method: 'POST',
      data: {csrf_test_name: csrfHash, liquor_entity_mapping_id: liquor_entity_mapping_id},
      success: function (response) {
        var result = JSON.parse(response);
        $('#previous_avl_qty').val(result[0].available_quantity);
      },
      errror: function () {
        swal("Can't reach to the server")
      }
    })
  })


  $('#liquor_mapping_details').click(function (event) {
    event.preventDefault()

    //var liquor_form_data = $('#' + this.id + '_form') // fetching the form id

    submit_data_error_check = true

    checkInputEmpty('liquor_data', 'kindly select a liquor type')
    checkInputEmpty('previous_avl_qty', 'kindly enter Previous Available Quantity')
    checkInputEmpty('current_avl_qty', 'kindly enter Current Available Quantity')

    if (submit_data_error_check) {
      //var action_url = liquor_form_data.attr('action')
	  var previous_avl_qty = $('#previous_avl_qty').val();
	  var current_avl_qty = $('#current_avl_qty').val();
	  var liquor_entity_mapping_id = $('#liquor_data').val();
	  
      $.ajax({
        url: DOMAIN + 'liquor/LiquorInventory/updateQuantity',
        method: 'POST',
        data: {csrf_test_name: csrfHash,liquor_entity_mapping_id : liquor_entity_mapping_id, previous_avl_qty: previous_avl_qty, current_avl_qty:current_avl_qty},
        success: function (response) {
			var response = JSON.parse(response);
			console.log(response[0].result);
            if (response[0].result === 'success') {
              //  

              Swal.fire({
                title: 'Qty Updated',
                text: '',
                icon: 'success',
                showConfirmButton: true
              //                            toast: true
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */

                if (result.isConfirmed) {
                  window.location.href = DOMAIN + 'liquor/LiquorInventory/liquor_inventory_updation'
                }
              })
            }
        },
        error: function (response) {
        }
      })
    } else {
      Swal.fire({
        title: 'Incomplete form',
        text: 'Kindly check all fields',
        icon: 'warning'
      })
    }
  })
})
