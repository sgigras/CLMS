$(document).ready(function () {

    $("#liquor_type").select2({width: '100%', placeholder: 'Select a liquor type'});
    $("#liquor_brand").select2({width: '100%', placeholder: 'Select a liquor brand'});
    $("#bottle_size").select2({width: '100%', placeholder: 'Select a bottle size'});
    $("#bottle_vol").select2({width: '100%', placeholder: 'Select a bottle volume'});
     $("#bottle_vol").change(function () {
        checkInputEmpty('bottle_vol', 'kindly select a bottle volume');
    });
    $("#liquor_type").change(function () {
        checkInputEmpty('liquor_type', 'kindly select a liquor type');
    });
    $("#liquor_brand").change(function () {
        checkInputEmpty('liquor_brand', 'kindly select a liquor brand');
    });

    $("#bottle_size").change(function () {
        checkInputEmpty('bottle_size', 'kindly select a bottle size');
    });
    $("#liquor_name").change(function () {
        checkInputEmpty('liquor_name', 'kindly enter a liquor name');
    });
     $("#liquor_description").change(function () {
        checkInputEmpty('liquor_description', 'kindly enter a liquor description');
    });

    $("#bottle_vol").change(function () {
        checkInputEmpty('bottle_vol', 'kindly select a bottle volume');
    });
//     $("#liquor_details").click(function (event) {
//         event.preventDefault();

//         console.log(this.id);

//         var liquor_form_data = $("#" + this.id + "_form");//fetching the form id
//         submit_data_error_check = true;

//          var inputFile=$('#liquor_image_input_file');
//         var fileToUpload=inputFile[0].files[0];
//         var other_data = $("#" + this.id + "_form").serializeArray();
//         var formdata=new FormData();
//         formdata.append('liquor_image_input_file',fileToUpload);
//         formdata.append('formdata',other_data);

//         checkInputEmpty('bottle_vol', 'kindly select a bottle volume');
//         checkInputEmpty('liquor_type', 'kindly select a liquor type');
//         checkInputEmpty('liquor_brand', 'kindly select a liquor brand');
//         checkInputEmpty('liquor_name', 'kindly enter a liquor name');
//         checkInputEmpty('liquor_description', 'kindly enter a liquor description');
//         checkUploadImageEmpty('liquor_image', 'Kindly select a image');
//         checkInputEmpty('bottle_size', 'Kindly select a bottle size');

//         if (submit_data_error_check) {
//             var action_url = liquor_form_data.attr('action');

//             $.ajax({
//                 url: action_url,
//                 method: 'POST',
//                 data: formdata.serialize(),
//                 dataType: 'JSON',
//                 beforeSend: function () {
//                     $(this).attr('disabled', 'disabled');
//                 },
//                 success: function (response) {
//                     $(this).removeAttr('disabled');


//                     if (!response.success) {

//                         $.each(response.messages, function (key, value) {
//                             var element = $('#' + key);
//                             element.closest('input,select')
//                                     .removeClass('is-invalid')
//                                     .addClass(value.length > 0 ? 'is-invalid' : 'is-valid').find('.text-danger').remove();
//                             element = $('#' + key + '_error');

//                             element.html(value);

//                         });
//                     } else {
//                         if (response.model_response[0].V_SWAL_TYPE === 'success') {
//                             window.location.href = DOMAIN + 'master/LiquorMaster';
//                         } else {
//                             Swal.fire({
//                                 title: response.model_response[0].V_SWAL_MESSAGE,
//                                 text: "",
//                                 icon: response.model_response[0].V_SWAL_TYPE,
//                                 showConfirmButton: false
// //                            toast: true
//                             });
//                         }
//                     }
//                 },
//                 error: function (response) {
//                     $(this).removeAttr('disabled');
//                 }
//             });

//             console.log(action_url);
//         } else {
//             Swal.fire({
//                 title: 'Incomplete form',
//                 text: 'Kindly check all fields',
//                 icon: 'warning'
//             });
//         }

//     });
});