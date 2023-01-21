<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/animation/animate.min.css">
<canvas id="winter-field" style="width: 100vw;
    height: 100vh;
    position: absolute;
    left: 0;
    top: 0;" ></canvas>

<div class="form-background">
<div class="hlogo">
              <a href="./">
                <img src="<?=base_url('assets/dist/img')?>/bsf-logo.png" alt="">
              </a>
            </div>
  <h1 class="tagline">India's First Line of Defence</h1>
  <div class="register-box">
    <!-- <div class="register-logo">
      <h2><a href="<? //= base_url('admin'); 
                    ?>"></a></h2>
    </div> -->

    <div class="card login-card-body1 animate__animated animate__zoomIn">
      <div class="card-body login-card-body">
        <div class="row">

          <div class="col-12 ">
          <div class="mlogo">
              <a href="./">
                <img src="<?=base_url('assets/dist/img')?>/bsf-logo.png" alt="">
              </a>
            </div>
            <!-- <p class="login-box-msg">Register</p> -->
            <center>
              <h2 class="login-label">Register</h2>
            </center>

            <?php
            if (null != ($this->session->flashdata('errors'))) {


              $this->load->view('admin/includes/_messages.php');
            }
            ?>
            <?php $validation = $this->session->flashdata('error'); ?>

            <?php echo form_open(base_url('admin/auth/register'), 'class="login-form" '); ?>
            <br>
            <!-- <div class="form-group has-feedback">
              <label style="font-size:15px">IRLA No/Regiment No.</label>
              <input type="text" maxlength="8" name="irlano" id="irlano" onkeypress="return checkValidInputKeyPress(numeric_regex_pattern);" class="form-control login-fields" placeholder="Enter IRLA No/Regiment No." value="<?php echo $this->session->flashdata('irlano'); ?>">
              <?php echo isset($validation) ? $validation['irlano'] : "" ?>
            </div> -->
            <div class="form-group has-feedback">
            <div class="form-item">
              <input type="text" id="irlano" name="irlano" onkeypress="return checkValidInputKeyPress(numeric_regex_pattern);" maxlength="9" class="uk-input" autocomplete="off" required="" value="">
              <label for="irla">IRLA No/Regiment No.</label>
            </div>
            </div>
            <div class="form-group has-feedback">
            <div class="form-item">
              <input type="date" id="dob" name="dob" class="uk-input" autocomplete="off" required="" value="<?php echo $this->session->flashdata('dob'); ?>">
              <label for="dob" style="padding-right:17px;">Date of Birth</label>
            </div>
            </div>

            <!-- <div class="form-group has-feedback">
              <label style="font-size:15px">Date of Birth</label>
              <input type="date" name="dob" id="dob" value="<?= set_value('dob') ?>" class="form-control login-fields" placeholder="" value="<?php echo $this->session->flashdata('dob'); ?>">
              <?php echo isset($validation) ? $validation['dob'] : "" ?>
            </div> -->
            <br>
            <center> <button id="submit" name="submit" type="submit" class="btn login-button" value="register">Register</button></center>
            <br>

            <?php echo form_close(); ?>

            <center>
              <p class="register" style="cursor:auto;"> Already have an Account? <a class="register" href="<?= base_url('admin/auth/login'); ?>" class="text-center">Login</a></p>
            </center>
          </div>
        </div>
      </div>
      <!-- /.form-box -->
    </div><!-- /.card -->
  </div>
</div>


<script>
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