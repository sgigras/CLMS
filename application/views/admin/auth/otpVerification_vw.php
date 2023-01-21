<!-- Content Wrapper. Contains page content -->
<?php
// $resultArray = (isset($canteen_club_data)) ? $canteen_club_data[0] : new stdClass;
// $city_select_array = (isset($city_list)) ? $city_list : array();
// $distributor_name_select_array = (isset($distributor_name_list)) ? $distributor_name_list : array();
?>
<!--<div class="content-wrapper" style="margin-top: 55px;min-height: 580.08px !important;">-->
	<!-- Main content -->
	<section class="content">
		<div class="card card-default">
			<div class="card-header">
				<div class="d-inline-block">
					<h3 class="card-title"> <i class="fas fa-user-check"></i>
						<?= trans('otp_verify_reset_password') ?> </h3>
					</div>

					<div class="d-inline-block float-right">

						<a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
					</div>
				</div>
				<div class="card-body">

					<!-- For Messages -->
					<?php // $this->load->view('admin/includes/_messages.php')   ?>

					<?php
					$redirect_url =  'admin/Forgot_passwordAPI/verifyotp';
					echo form_open(base_url($redirect_url), array('class' => 'form-hoxrizontal', 'id' => 'verify_otp'), 'class="form-horizontal"');
					?> 

					<!--outlet and canteen name-->
					<div class="row">
						<div class= "col-12 col-md-12">
							<div class="col-md-6">
								<div id="otpdiv">
									<label for="otp"><?=trans('enter_otp') ?></label>
									<input type="password" class="form-control" id="enterotp" placeholder="Enter OTP" maxlength="4" >
	                                <small id="otperror" style="color: red" class="login_error_message"></small>
	                            </div>
							</div>

						</div>
					</div>


					<div id="passworddiv" style="display: none;">
					
				    <div class="row">
				    	

				    		
				    			<div class=" col-md-6">
				    				<label for="otp"><?=trans('enter_new_password') ?></label>
				    				<input type="password" class="form-control" id="new_password" placeholder="Enter new password">
				    				<small id="password_err" style="color: red" class="login_error_message"></small>

				    			</div>
				    			<div class=" col-md-6">
				    				
				    					<label for="otp"><?=trans('confirm_new_password') ?></label>
				    					<input type="password" class="form-control" id="con_new_password" placeholder="Enter new password">
				    					<small id="con_password_err" style="color: red" class="login_error_message"></small>
				    				
				    			</div>

				    		
				    	</div>
				    </div><br>
				    <div id="otpVerifybutton">
						<div class="form-group">
							<div class="col-md-12">

								<button id="otpButton" class="btn btn-primary pull-right" ><?=trans('verify_otp') ?></button>
								<!--<in[>-->
									<input type="hidden"  name="submit" value="submit" />
							</div>
						</div>
				    </div>

				     <div id="passVerifybutton" style="display: none;">
						<div class="form-group">
							<div class="col-md-12">

								<button id="pass_button" class="btn btn-primary pull-right" ><?=trans('reset_password') ?></button>
								<!--<in[>-->
									<input type="hidden"  name="submit" value="submit" />
							</div>
						</div>
				    </div>

					
						<?php echo form_close(); ?>
					</div>
					<!-- /.box-body -->
				</div>
			</section>
			<script>
				var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
				var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
				var baseurl = "<?php echo base_url(); ?>";
			</script>
			<!--</div>-->
			<script src="<?= base_url() ?>assets/js/module/common/validation.js"></script>
			<script src="<?= base_url() ?>assets/js/module/password_forgot.js"></script>