$(function() {
    //Initialize Select2 Elements
    $('.select2').select2()
    getListOfStates();
    getListOfBrand();
})

function getListOfStates() {
    var databaseObject = { csrf_test_name: csrfHash };
    $.ajax({
        url: baseurl + 'admin/brewery/Brewery/fetchStatesList',
        type: 'post',
        data: databaseObject,
        success: function(response) {
            var obj = JSON.parse(response);
            var htmlsnippet = "";
            htmlsnippet += '<div class="row" style="padding-left:20px">\n';
            for (const key in obj) {
                if (key % 4 == 0 && (key != 0)) {
                    htmlsnippet += '</div>\n';
                    htmlsnippet += '<br><div class="row" style="padding-left:20px" id="row">\n';
                }
                htmlsnippet += '                    <div class="col-sm-3">\n' +
                    '                        <div class="pretty p-svg p-round p-jelly">\n' +
                    '                            <input id="' + obj[key]['id'] + '" type="checkbox" />\n' +
                    '                            <div class="state p-primary">\n' +
                    '                                <!-- svg path -->\n' +
                    '                                <svg class="svg svg-icon" viewBox="0 0 20 20">\n' +
                    '                                    <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>\n' +
                    '                                </svg>\n' +
                    '                                <label>' + obj[key]['state'] + '</label>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                    </div>\n';
            }
            $('#statesdiv').html(htmlsnippet);
        }
    });
}
//fetch brand mapping details with brewery
$('#breweryname').on('select2:select', function(e) {
    e.preventDefault();
    var data = e.params.data;
    var stockistid = data.id;
    var databaseObject = { csrf_test_name: csrfHash, stockistid: stockistid };
    $.ajax({
        url: baseurl + 'admin/brewery/Brewery/fetchbrandmapped',
        type: 'post',
        data: databaseObject,
        dataType: 'json',
        success: function(response) {
            $('input[type="checkbox"]').prop("checked", false);
            var strArray = response.brand_id.replace(/"/g, '').replace(/ /g, '').split(',');
            for (var i = 0; i < strArray.length; i++) {
                $('#' + strArray[i]).prop("checked", true);
            }
        }
    });
});
$('#submitstates').on('click', function(event) {
    var submit_data_error_check = true;
    var someObj = {};
    someObj.fruitsGranted = [];
    someObj.fruitsDenied = [];
    var brandnameval = $('#breweryname').val();
    // console.log(states);
    if (brandnameval.length > 0 && brandnameval[0] !== "") {
        // alert('selected');

        $('#breweryname_error').html("");
    } else {
        // alert('not selected');
        submit_data_error_check = false;
        $('#breweryname_error').html("Please Select A Stockist");
    }
    if (submit_data_error_check) {
        var breweryname = $('#brandname').select2('data');
        var breweryid = breweryname[0].id;
        // alert(breweryid);
        // $(":checkbox").change(function() {
        var notChecked = [],
            checked = [];
        $(":checkbox").map(function() {
            this.checked ? checked.push(this.id) : notChecked.push(this.id);
        });
        // alert("checked: " + checked);
        // alert("not checked: " + notChecked);
        // });
        // console.log(someObj.fruitsGranted);
        var databaseObject = { csrf_test_name: csrfHash, breweryid: breweryid, statesids: checked };
        $.ajax({
            url: baseurl + 'admin/brewery/Brewery/mapStockistToBrand',
            type: 'post',
            data: databaseObject,
            dataType: 'json',
            success: function(response) {
                Swal.fire({
                    title: 'Updated!',
                    text: "Stockist Have Been Mapped!",
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                })
            }
        });
    } else {
        Swal.fire({
            title: 'Please Select A Brewery From Brewery List',
            text: "",
            icon: 'info',
            timer: 1500,
            showConfirmButton: false,
            toast: true
        });
    }
});
//load list of all brand 
function getListOfBrand() {
    var databaseObject = { csrf_test_name: csrfHash };
    $.ajax({
        url: baseurl + 'admin/brewery/Brewery/fetchBrandNameList',
        type: 'post',
        data: databaseObject,
        success: function(response) {
            var obj = JSON.parse(response);
            var htmlsnippet = "";
            htmlsnippet += '<div class="row" style="padding-left:20px">\n';
            for (const key in obj) {
                if (key % 1 == 0 && (key != 0)) {
                    htmlsnippet += '</div>\n';
                    htmlsnippet += '<div class="row" style="padding-left:20px" id="row">\n';
                }
                htmlsnippet += '              <div class="col-md-6 brandItemMain">\n' +
                    '                        <div class="pretty p-svg p-round p-jelly">\n' +
                    '                            <input id="' + obj[key]['liquor_description_id'] + '" type="checkbox" />\n' +
                    '                            <div class="state p-primary">\n' +
                    '                                <!-- svg path -->\n' +
                    '                                <svg class="svg svg-icon" viewBox="0 0 20 20">\n' +
                    '                                    <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>\n' +
                    '                                </svg>\n' +
                    '                                <label class="brandItem">' + obj[key]['brand'] + '(' + obj[key]['liquor_type'] + ') < /label>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                    </div>\n';
            }
            $('#liquorbranddiv').html(htmlsnippet);
        }
    });
}
//update and assign liquor brand to brewery
$('#submitbranddetails').on('click', function(event) {
    var submit_data_error_check = true;
    var someObj = {};
    someObj.fruitsGranted = [];
    someObj.fruitsDenied = [];
    var brewerynameval = $('#breweryname').val();
    if (brewerynameval.length > 0 && brewerynameval[0] !== "") {
        $('#breweryname_error').html("");
    } else {
        submit_data_error_check = false;
        $('#breweryname_error').html("Please Select A Brewery");
    }
    if (submit_data_error_check) {
        var breweryname = $('#breweryname').select2('data');
        var stockistid = breweryname[0].id;
        var notChecked = [],
            checked = [];
        $(":checkbox").map(function() {
            this.checked ? checked.push(this.id) : notChecked.push(this.id);
        });
        var databaseObject = { csrf_test_name: csrfHash, stockistid: stockistid, brandid: checked };
        $.ajax({
            url: baseurl + 'admin/brewery/Brewery/mapStockistToBrand',
            type: 'post',
            data: databaseObject,
            dataType: 'json',
            success: function(response) {
                Swal.fire({
                    title: 'Updated!',
                    text: "Stockist Have Been Mapped!",
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                })
            }
        });
    } else {
        Swal.fire({
            title: 'Please Select A Brewery From Brewery List',
            text: "",
            icon: 'info',
            timer: 1500,
            showConfirmButton: false,
            toast: true
        });
    }
});
// search brand name from list of showing brnad 
$('#searchliquor').keyup(function() {
    // alert('searchliquor');
    var input = document.getElementById("searchliquor");
    input = input.value.toUpperCase();
    var brandList = document.querySelectorAll(".brandItem");
    for (var i = 0; i < brandList.length; i++) {
        var item = brandList[i].textContent.toUpperCase();
        if (item.includes(input) == true) {
            brandList[i].closest(".brandItemMain").style.display = "";
        } else {
            brandList[i].closest(".brandItemMain").style.display = "none";
        }
    }
});