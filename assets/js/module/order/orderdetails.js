$(function () {

$('#orderdescdiv').hide();

})

$('#submitordercode').on('click', function (event) {
    // alert('clicked');
    submit_data_error_check = true;

    var ordercode = $('#ordercode').val();
    checkInputEmpty("ordercode", "Kindly Enter Order Code");

    if (submit_data_error_check) {
        var databaseObject = { csrf_test_name: csrfHash, ordercode: ordercode };
        $.ajax({
            url: baseurl + 'order/OrderAPI/fetch_orderdetails',
            type: 'post',
            data: databaseObject,
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if (response.length > 0) {
                    $('#orderdescdiv').show();



                    var htmlsnippet = "";
                    for (const key in response) {



                        htmlsnippet += '                 <tr>' +
                            '                            <td>' + response[key]['liquor_name'] + '<br>'+
                            '<img style="width:150px;height:150px;" src="'+baseurl+response[key]['liquor_image'] +'">'+
                            
                            '</td>' +
                            '                            <td>'+response[key]['liquor_type'] +'</td>' +
                            '                            <td>'+response[key]['liquor_ml'] +'ml </td>' +
                            '                            <td>'+response[key]['quantity'] +'</td>' +
                            '                            <td>'+response[key]['unit_lot_cost'] +'</td>' +
                            '                            <td>'+response[key]['total_quantity_cost'] +'</td>' +
                            '                            <td>' +
                            '<div class="col-12 col-md-12 hh-grayBox pt45 pb20">' +
                            '                        <div class="row justify-content-between">' +
                            '                            <div class="order-tracking completed">' +
                            '                                <span class="is-complete"></span>' +
                            '                                <p>Ordered <span>'+ response[key]['order_time'] +'</span></p>' +
                            '                            </div>' +
                            '                            <div class="order-tracking">' +
                            '                                <span class="is-complete"></span>' +
                            '                                <p>Shipped<br><span> </span></p>' +
                            '                            </div>' +
                            '                            <div class="order-tracking">' +
                            '                                <span class="is-complete"></span>' +
                            '                                <p>Delivered<br><span> </span></p>' +
                            '                            </div>' +
                            '                        </div>' +
                            '                    </div>' +
                            '</td>' +
                            '                        </tr>';


                    }
                    // console.log(htmlsnippet);
                    $('#orderdesctbody').html(htmlsnippet);



                } else {
                    Swal.fire({
                        title: 'No Details Found',
                        text: 'Invalid Order Code',
                        icon: 'info',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            }
        });
    } else {
        // Swal.fire({
        //     title: 'Kindly Enter Ord',
        //     text: "",
        //     icon: 'info',
        //     timer: 1500,
        //     showConfirmButton: false
        // });
    }
});
