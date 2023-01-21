
 var id=0;
 var row_previous_id=0;
 var new_id='';
 $(document).ready(function(){


$('.addBtn').click(function()  {
    alert('clicked');
    id++;
    addRow(id);
});


$('#submit_liquor').submit(function(){
     event.preventDefault(); 

     if(row_previous_id<    id){
         var liquor_type = document.getElementById("alcohol_type"+id).value;
    console.log(liquor_type);
    console.log(csrfHash);

    $.ajax({
        url: DOMAIN + 'master/Alcohol_masterAPI/addalcholType',
        method: 'POST',
        data: {csrf_test_name: csrfHash, liquor_type: liquor_type},
        success: function (response) {
            console.log(response);

        },
        errror: function () {
            swal("Can't reach to the server");
        }
    });





     }


 })

  


});

  function formatRows(id) {

    return '<tr> <td class="col-sm-6"><label>Alcohol Name</label><br><input type="text" name="no_of_trucks[]" maxlength="11" onkeypress="return /[A-Z]/i.test(event.key)"  id="alcohol_type'+id+'" class="form-control addMain" placeholder="Liquor type"></td>' +
    '<td class="col-xs-1 text-center">'+
    '<td><button  class="btn btn-primary pull-right button_submit_new_test_data"  id="liquor_submit'+id+'" >save</button></td></tr>';


}


function deleteRow(trash) {
    $(trash).closest('tr').remove();


};


function addRow(id) {


    $(formatRows(id)).insertAfter('#addRow');
}






 function liquor_submit(id){


    console.log(id);


    var liquor_type = document.getElementById("alcohol_type"+id).value;
    console.log(liquor_type);
    console.log(csrfHash);

    $.ajax({
        url: DOMAIN + 'master/Alcohol_masterAPI/addalcholType',
        method: 'POST',
        data: {csrf_test_name: csrfHash, liquor_type: liquor_type},
        success: function (response) {

        },
        errror: function () {
            swal("Can't reach to the server");
        }
    });



}