$(document).ready(function () {
	var fileName = '';
	$('#template').hide();
	
	$('#file').change(function () {

		var file_path = $('#file').val();

		fileName = file_path.slice(12);

		document.getElementById('file_name').innerHTML = fileName;



	});

	$('#import_form').on('submit', function (event) {
		event.preventDefault();

		var form = $('form')[0]; // You need to use standard javascript object here
		// console.log(form);
		var formData = new FormData(form);
		var File = $("#file")[0].files[0];
		// console.log(File);
		formData.append('FILE', File);
		formData.append('csrf_test_name', csrfHash);
		var me = $(this);
		// console.log(formData);
		//var data_length= Object.keys(formData).length;
		if(fileName == ''){
			Swal.fire({
				title: "Warning",
				text: "Kindly select file to upload",
				icon: "warning",
				showCancelButton: true,
				closeOnConfirm: false,
				showLoaderOnConfirm: true,
			});

		}
		else
		{
			$.ajax({
				url: me.attr('action'),
				method: "POST",
				data: formData,
				contentType: false,
				processData: false,
				success: function (data) 
				{
					var resobj = data.trim();
					
					response= JSON.parse(resobj);
					$('#file').val('');
					if(!response){
						
						Swal.fire({
						title: "Warning",
						text: "Some data's are missing in excel sheet",
						icon: "warning",
						showConfirmButton: true,
						closeOnConfirm: false,
						showLoaderOnConfirm: true,
					});

					}else if(response[0].V_SWAL_TITLE === 'SUCCESS')
					{
						Swal.fire({
							title: "success!",
							text: "Successfully imported data",
							icon: "success",
							showConfirmButton: true
						});
					}else{
						
						Swal.fire({
							title: "Failed to import!",
							text: "Failed to upload",
							icon: "error",
							showConfirmButton: true,
							closeOnConfirm: false,
							showLoaderOnConfirm: true,
						});


					}

					
				},
				error:function (){
					Swal.fire({
						title: "Warning",
						text: "Something went wrong",
						icon: "warning",
						showConfirmButton: true,
						closeOnConfirm: false,
						showLoaderOnConfirm: true,
					});


					


				}
			});
		}
		
	});



	$('#template_menu').click(function(){
		//$('#template').show();
		var bsf_template = document.getElementById('template1').value;
				if(bsf_template == 'bsf_tepmlate1'){
			$('#template').show();


		}

	});

	


});
















