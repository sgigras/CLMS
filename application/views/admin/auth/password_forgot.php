<!-- Content Wrapper. Contains page content -->
<?php
// $resultArray = (isset($canteen_club_data)) ? $canteen_club_data[0] : new stdClass;
// $city_select_array = (isset($city_list)) ? $city_list : array();
// $distributor_name_select_array = (isset($distributor_name_list)) ? $distributor_name_list : array();
?>
<!--<div class="content-wrapper" style="margin-top: 55px;min-height: 580.08px !important;">-->
	<!-- Main content -->
	<!-- <section class="content">
		<div class="card card-default"> -->
			<div class="card-header">
				<div class="d-inline-block">
					<h3 class="card-title"> <i class="fas fa-user"></i>
						<?= trans('verify_uer') ?> </h3>
					</div>

					
				</div>
				<div class="card-body">

					<!-- For Messages -->
					<?php // $this->load->view('admin/includes/_messages.php')   ?>

					<?php
					$redirect_url =  'admin/Forgot_passwordAPI/verifyUser';
					echo form_open(base_url($redirect_url), array('class' => 'form-hoxrizontal', 'id' => 'user_verify'), 'class="form-horizontal"');
					?> 

					<!--outlet and canteen name-->
					<div class="row">
						<div class= "col-12 col-md-12">
							<div class="col-md-6">
								<?php //$this->load->view('master/email_field', array("field_id" => "user_name", "label" => "user_name", "max_length" => "40", "place_holder" => "Enter a username", "value" => "")); ?>
								<label for="username"><?=trans('user_name') ?></label>
								<input type="text" class="form-control" id="user_name" placeholder="Enter Username">
								<small id="username_error" style="color: red;"></small>


							</div>

						</div>


					</div><br>
					<div class="form-group">
						<div class="col-md-12">
							<button id="usernamebtn" class="btn btn-primary pull-right" ><?=trans('verify_uer') ?></button>
							<!--<in[>-->
								<input type="hidden"  name="submit" value="submit" />
						</div>
					</div>
						<?php echo form_close(); ?>
					</div>
					
			<!-- 	</div>
			</section>
 -->			<script>
				var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
				var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
				var baseurl = "<?php echo base_url(); ?>";
			</script>
			<!--</div>-->
			<script src="<?= base_url() ?>assets/js/module/common/validation.js"></script>
			<script src="<?= base_url() ?>assets/js/module/password_forgot.js"></script>