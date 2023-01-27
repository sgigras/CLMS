<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/animation/animate.min.css">
<canvas id="winter-field" style="width: 100vw;
    height: 100vh;
    position: absolute;
    left: 0;
    top: 0;" ></canvas>
<div class="form-background">
<h1 class="tagline">HIMVEER</h1>
            <div class="hlogo">
              <a href="./">
                <img src="<?=base_url('assets/dist/img')?>/bsf-logo.png" alt="">
              </a>
            </div>
  <div class="login-box">
    <div class="card login-card-body1 animate__animated animate__zoomIn">
      <div class="card-body login-card-body">
        <div class="row">
          <div class="col-12">
          <div class="mlogo">
              <a href="./">
                <img src="<?=base_url('assets/dist/img')?>/bsf-logo.png" alt="">
              </a>
            </div>
            <center>
              <h2 class="login-label">Login</h2>
            </center>
            <?php
            if (null != ($this->session->flashdata('errors'))) {
              $this->load->view('admin/includes/_messages.php');
            }
            if (null != ($this->session->flashdata('success'))) {
              $this->load->view('admin/includes/_messages.php');
            }
            ?>
            <?php $validation = $this->session->flashdata('error'); ?>
            <?php echo form_open(base_url('admin/Auth/login'), array("id" => "loginform", "class" => "login-form")); ?>
            <div class="form-group has-feedback">
            <div class="form-item">
              <input type="text" id="irlano" class="uk-input" onkeypress="return checkValidInputKeyPress(numeric_regex_pattern);" maxlength="9" name="irlano" autocomplete="off" required="" value="">
              <label for="irlano">IRLA No/Regiment No.</label>
              <?php echo isset($validation) ? $validation['irlano'] : "" ?>
            </div>
            </div>
            
            <div class="form-group has-feedback">
            <div class="form-item">
              <input type="date" id="dob" name="dob" class="uk-input" autocomplete="off" required="" value="<?php echo $this->session->flashdata('dob'); ?>">
              <label for="dob" style="padding-right:15px;">Date of Birth</label>
              <?php echo isset($validation) ? $validation['dob'] : "" ?>
            </div>
            </div>
            <div class="form-group has-feedback">
            <div class="form-item">
              <input type="password" maxlength="4" name="pin" id="pin" class="uk-input" autocomplete="off" required="" value="<?php echo $this->session->flashdata('pin'); ?>" onkeypress="return checkValidInputKeyPress(numeric_regex_pattern);">
              <label for="pin" >PIN</label>
              <?php echo isset($validation) ? $validation['pin'] : "" ?>
            </div>
            </div>
            <div class="row">
              <!-- add link for Himveer SSO Login for Service person-->
              <div class="col-6 ">
                <div style="padding-top: 8px;">
                  <center> <button id="himveerSubmit" name="himveerSubmit" type="button" class="btn login-button" value="HimVeerSubmit" onClick="RedirectHimveer()">Himveer Login</button></center>
                </div>
              </div>
              <div class="col-6 ">
                <div style="padding-top: 8px;">
                <center> <button id="submit" name="submit" type="submit" class="btn login-button" value="signin">login</button></center>
                </div>
              </div>
          </div>
            <?php echo form_close(); ?>
            <div class="row">
              <div class="col-6 ">
                <div style="padding-top: 8px;">
                  <a class="register" href="<?= base_url('admin/auth/forgot_password'); ?>">Pin
                    Forgotten?</a>
                </div>
              </div>
              <div class="col-6">
                <div class="checkbox icheck text-right">
                  <label class="register">
                    <input type="checkbox"> <?= trans('remember_me') ?>
                  </label>
                </div>
              </div>
            </div>
            <center>
              <p  class="register" style="cursor:auto;"> Don't have an Account?
                <a class="register" href="<?= base_url('admin/auth/register'); ?>" class="text-center">Register</a>
              </p>
            </center>
          </div>
        </div>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->
</div>
<script>
  //add link for Himveer SSO Login for Service person
  function RedirectHimveer()
  {
    window.location = "<?php echo $AuthUrl;?>";
  }
  
  function init (){
	var portrait = document.getElementById('winter-field');
	var context = portrait.getContext('2d');
	var w = portrait.width;
	var h = portrait.height;
	var background = new Image();
	background.src = "";
	var snowflakes = [];
  function snowfall (){
	  context.clearRect(0, 0, w, h);
	  context.drawImage(background, 0, 0);
	  addSnowFlake();
	  snow();
  };
  function addSnowFlake (){
	  var x = Math.ceil(Math.random() * w);
	  var s = Math.ceil(Math.random() * 3);
	  snowflakes.push({"x": x, "y": 0, "s": s});
  };
  function snow (){
	  for (var i = 0; i < snowflakes.length; i++){
		  var snowflake = snowflakes[i];
		  context.beginPath();
		  context.fillStyle = "rgba(255, 255, 255, 0.7)";
		  context.arc(snowflake.x, snowflakes[i].y += snowflake.s/2, snowflake.s/2, 0, 2 * Math.PI);
		  context.fill();
		  if(snowflakes[i].y > h){
			  snowflakes.splice(i, 1);
		  }
	  };
  };
  setInterval(snowfall, 20);
};
window.onload = init;
</script>