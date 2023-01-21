<link rel="stylesheet" href="<?= base_url()?>assets/plugins/animation/animate.min.css">
<canvas id="winter-field" style="width: 100vw;
    height: 100vh;
    position: absolute;
    left: 0;
    top: 0;" ></canvas>
<style>
.login-card-body1 {
    opacity: 0.9 !important;
}
</style>

<div class="form-background">
<h1 class="tagline">India's First Line of Defence</h1>
<div class="hlogo">
              <a href="./">
                <img src="<?=base_url('assets/dist/img')?>/bsf-logo.png" alt="">
              </a>
            </div>
    <div class="login-box">

        <div class="card login-card-body1 animate__animated animate__zoomIn">

            <div class="card-body login-card-body">
            <div class="mlogo">
              <a href="./">
                <img src="<?=base_url('assets/dist/img')?>/bsf-logo.png" alt="">
              </a>
            </div>

                <center>
                    <h2 class="login-label"><?= trans('forgot_password') ?></h2>
                </center>

                <?php $this->load->view('admin/includes/_messages.php') ?>

                <?php echo form_open(base_url('admin/auth/forgot_password'), 'class="login-form" '); ?>

                <!-- <div class="form-group has-feedback">
                    <label style="font-size: 15px;">IRLA No/Regiment No</label>
                    <input type="text" name="irlano" id="irlano" maxlength="8" onkeypress="return checkValidInputKeyPress(numeric_regex_pattern);" class="form-control login-fields"
                        placeholder="Enter IRLA No/Regiment No">

                </div> -->
                <div class="form-group has-feedback">
                <div class="form-item">
                    <input type="text" name="irlano" id="irlano" maxlength="9" onkeypress="return checkValidInputKeyPress(numeric_regex_pattern);" class="uk-input" autocomplete="off" required="" value="">
                    <label for="irlano">IRLA No/Regiment No.</label>
                </div>
                </div>

                <div class="form-group has-feedback">
            <div class="form-item">
              <input type="date" id="dob" name="dob" class="uk-input" autocomplete="off" required="" value="<?php echo $this->session->flashdata('dob'); ?>">
              <label for="dob" style="padding-right:15px;">Date of Birth</label>
              <?php echo isset($validation) ? $validation['dob'] : "" ?>
            </div>
            </div>

                <div class="row">

                    <!-- /.col -->

                    <div class="col-12">

                        <center> <button type="submit" name="submit" id="submit" class="btn login-button"
                                value="submit"><?= trans('submit') ?></button></center>

                    </div>

                    <!-- /.col -->

                </div>

                <?php echo form_close(); ?>


                <!-- You remember Password? Sign In -->
                <center>
                    <p class="register" style="cursor:auto;"> You remember Password? <a class="register" href="<?= base_url('admin/auth/login'); ?>"
                            class="text-center">Login</a></p>
                </center>



            </div>

            <!-- /.login-card-body -->

        </div>

    </div>

    <!-- /.login-box -->

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