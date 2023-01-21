$(document).ready(function(){
	
	$("#user_name").on('change', function () {
		$("#username_error").html("");
		$("#usernamebtn").attr('disabled', false);
	});
	$("#enterotp").on('change', function () {
		$("#otperror").html("");
		$("#otpButton").attr('disabled', false);
	});
	$("#new_password").on('change', function () {
		$("#password_err").html("");
		$("#pass_button").attr('disabled', false);
	});
	$("#con_new_password").on('change', function () {
		$("#con_password_err").html("");
		$("#pass_button").attr('disabled', false);
	});


	function isValidEmailAddress(emailAddress) {
		var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
		return pattern.test(emailAddress);
	}



	$('#usernamebtn').click(function(event){
		event.preventDefault();
		var username = $('#user_name').val().trim();
		var ERROR_FLAG = 0;
		var action_url = $('#user_verify').attr('action');
		csrf_test_name = csrfHash;
		console.log(action_url);
		if(!isValidEmailAddress(username)){
			document.getElementById('username_error').innerHTML = "Please enter valid email";
			ERROR_FLAG++;
		}
		if(username == ''){
			document.getElementById('username_error').innerHTML = "Please enter email";
			ERROR_FLAG++;
		}
		if(ERROR_FLAG >0)
		{
			$("#usernamebtn").attr('disabled', true);
		}else{
			$("#usernamebtn").attr('disabled', false);
			$.ajax({
				url : action_url,
				type : "POST",
				data : {username :username, csrf_test_name:csrf_test_name},
				success:function(response){
					var data = JSON.parse(response);
					if(data.length >= 1 )
					{
						//console.log(data);
						var exist_user = data[0].email_id;
						console.log(exist_user);
						if(username == exist_user)
						{
							$.ajax({
								url:"Forgot_passwordAPI/sendVerificationMail",
								type :"POST",
								data :{username : username, csrf_test_name:csrf_test_name},
								success:function(response){
									Swal.fire({
										title: "Success",
										text: "Kindly check your mail",
										icon: "success"
									});
								},
								error:function(){
									Swal.fire({
										title: "Error",
										text: "Something went wrong",
										icon: "error"
									});

								}
							});
						}
					}else{
						Swal.fire({
							title: "Warning",
							text: "User is invalid",
							icon: "warning"
						});
					}
					
					

				},
				error:function()
				{
					Swal.fire({
						title: "Error",
						text: "Something went wrong",
						icon: "error"
						});
				}
			});
		}
	});


	$('#otpButton').click(function(event){
		event.preventDefault();
		csrf_test_name = csrfHash;
		var action_url = $('#verify_otp').attr('action');

		var otp = $('#enterotp').val();
		console.log(otp);
		var ERROR_FLAG =0;
		if(otp == ''){
			document.getElementById('otperror').innerHTML = 'Please enter otp';
			ERROR_FLAG++;
		}
		if(ERROR_FLAG > 0)
		{
			$("#otpButton").attr('disabled', true);
		}else
		{
			$("#otpButton").attr('disabled', false);
			$.ajax({
				url:action_url,
				type:"POST",
				data:{otp:otp,csrf_test_name:csrf_test_name},
				success:function(response){
					var data = JSON.parse(response);
					console.log(data.length);
					if(data.length >= 1){
						var check_otp = data[0].otp;
						if(otp == check_otp){
							$('#otpdiv').hide();
							$('#otpVerifybutton').hide();
							$('#passworddiv').show();
							$('#passVerifybutton').show();
							$('#pass_button').click(function(event){
								event.preventDefault();
								var new_pass = $('#new_password').val();
								var con_new_pass = $('#con_new_password').val();
								csrf_test_name = csrfHash;
								console.log(new_pass);
								console.log(con_new_pass);
								var ERROR_FLAG = 0;
								if(new_pass == ''){
									document.getElementById('password_err').innerHTML = "Please enter password";
									ERROR_FLAG++;

								}
								if(con_new_pass == ''){
									document.getElementById('con_password_err').innerHTML = "Please enter confirm password";
									ERROR_FLAG++;

								}
								if(new_pass.trim()!==con_new_pass.trim())
								{
									document.getElementById('con_password_err').innerHTML = "New password and confirm password should be same";
									ERROR_FLAG++;
								}

								if(ERROR_FLAG > 0)
								{
									$("#pass_button").attr('disabled', true);

								}else{
									$("#pass_button").attr('disabled', false);
									$.ajax({
										url:'updatePassword',
										type:"POST",
										data :{new_pass:new_pass, csrf_test_name:csrf_test_name },
										success:function(response){
											Swal.fire({
												title: "Success",
												text: "Password reset successfully",
												icon: "success"
											});

										},
										error:function(){
											Swal.fire({
												title: "Error",
												text: "Something went wrong",
												icon: "error"
											});
										}
									});



								}

							});


						}
					}else{
						Swal.fire({
							title: "Warning",
							text: "Please enter valid OTP",
							icon: "warning"
						});


					}
					


				},
				error:function(){
					Swal.fire({
												title: "Error",
												text: "Something went wrong",
												icon: "error"
											});


				}
			})

		}

	});
});























