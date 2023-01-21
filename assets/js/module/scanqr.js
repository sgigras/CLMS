 $(document).ready(function () {
 console.log('enter');
        $("#device").click(function(){
             console.log('enter');
        }
       
           $("#device").select2({
      placeholder:"Select device No",
      createTag: function(term, data) {
        var value = term.term;
        return {
          id: value,
          text: value
        };                
      },
            // tags: true,
            ajax: {
              url:"<?= base_url('GenrateQRCode/get_device_list') ?>" ,
              dataType: 'json',
              delay: 250,
              data: function(params) {
                return {
                        q: params.term // search term
                      };
                    },
                    processResults: function(data, params) {

                      var resData = [];
                      data.forEach(function(value) {
                        if (value.vehicleno.toUpperCase().indexOf(params.term.toUpperCase()) != -1)
                          resData.push(value)
                      })
                      return {
                        results: $.map(resData, function(item) {
                          return {
                            text: item.deviceid,
                            id: item.deviceid
                          }
                        })
                      };
                    },
                    cache: true
                  },
                  minimumInputLength: 1

                });
     // getdevicelist();
     // $("#device").select2();
});

//     function getdevicelist()//from availableVehicle.html -- onload
// {
//       $.ajax({
//         type: 'POST',
//         url: "<?=base_url('GenrateQRCode/get_device_list') ?>",
//          dataType: 'html',
//         data: {user_id:userid},
//         success: function (response)
//         {
//             console.log(response);
//             var obj = JSON.parse(response);
           
//                  var html='<option></option>';
//                  for(var i=0;i<obj.length;i++){
//                     // console.log(obj[i].V_VEH_ID+ "****"+obj[i].V_VEHNO);
//                     html+='<option value="'+obj[i].deviceid+'" >'+obj[i].deviceid+'</option>';
//                 }
//                $("#device").html(html);
//             }
//     });
// }
// $(function () {        
    
//     $("#send").click(function(){
//         var device_id =$("#device").val();
// //        alert(deviceid+"**")

//         var err="";
//         if(document.getElementById("device").value == "")
//         {
//             err += "*Select Device id";
//         }
//         if(err == ""){
//             $.ajax({
//                 type: 'POST',
//                 url:  "<?=base_urlbase_url('GenrateQRCode/generate')?>",
//                 datatype: 'html',
//                 data: {deviceid: device_id},
//                 success: function (result)
//                 {
//                     // document.getElementById('qrdata').innerHTML=result;
//                    console.log(result);
//                 }
//             });
//         }
//     });    
// });    