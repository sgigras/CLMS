$(document).ready(function () {
    // console.log();
    console.log("hello")
})
function approve_reject_order(approval_status) {
    var chairman_remark = $("#chairman_remark").val().trim();
    if (chairman_remark.length == 0) {
        // SVGFEDropShadowElement()
        swal("Kindly enter a remark");
        return false;
    }
    $.ajax({
        url: DOMAIN + 'master/BreweryMaster/approveRejectOrder',
        method: 'POST',
        data: {
            order_id: order_id, chairman_remark: chairman_remark, approval_status: approval_status
        },
        success: function (response) {
           location.href='loadBreweryOrderList';
        },
        error: function (error) {
            console.log(error);
        }
    });
}
