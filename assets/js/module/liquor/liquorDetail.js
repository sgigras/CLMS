$(document).ready(function () {
    $('#brewery_name').select2();
    $("#brewery_name").select2({width: '100%', placeholder: 'Select a Brewery'});
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
});