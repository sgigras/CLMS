<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/animation/animate.min.css">
<canvas id="winter-field" style="width: 100vw;
    height: 100vh;
    position: absolute;
    left: 0;
    top: 0;"></canvas>
<style>
.register-card-body1 {
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
    <div class="register-box">
        <div class="register-logo">
            <h2><a href="<?= base_url('admin'); ?>"></a></h2>
        </div>

        <div class="card register-card-body1">
            <div class="card-body register-card-body">
                <div class="row">
                    <div class="col-12">
                        <!-- <p class="login-box-msg">Register</p> -->
                        <center>
                            <h2 class="login-label">Verify OTP</h2>
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

                        <?php echo form_open(base_url('admin/auth/otpValidation'), 'class="login-form" '); ?>

                        <br>
                        <div class="form-group has-feedback">
                            <div class="form-item">

                                <input type="text" name="otp"
                                    onkeypress="return checkValidInputKeyPress(numeric_regex_pattern);" maxlength="6"
                                    id="otp" class="uk-input" autocomplete="off" required=""
                                    value="<?php echo $this->session->flashdata('otp'); ?>">
                                <label for="otp">Enter OTP</label>
                                <small id="otp_error"
                                    style="color:red"><?php echo isset($validation) ? $validation['otp'] : "" ?></small>
                            </div>
                        </div>
                        <br>
                        <center> <button id="submit" name="submit" type="submit" class="btn login-button"
                                value="register">Verify OTP</button></center>
                        <br>

                        <?php echo form_close(); ?>

                        <center>
                            <p> Already have an Account? <a class="register" href="<?= base_url('admin/auth/login'); ?>"
                                    class="text-center">Login</a></p>
                        </center>
                    </div>
                </div>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
</div>

<script>
function init() {
    var portrait = document.getElementById('winter-field');
    var context = portrait.getContext('2d');
    var w = portrait.width;
    var h = portrait.height;
    var background = new Image();
    background.src = "";
    var snowflakes = [];

    function snowfall() {
        context.clearRect(0, 0, w, h);
        context.drawImage(background, 0, 0);
        addSnowFlake();
        snow();
    };

    function addSnowFlake() {
        var x = Math.ceil(Math.random() * w);
        var s = Math.ceil(Math.random() * 3);
        snowflakes.push({
            "x": x,
            "y": 0,
            "s": s
        });
    };

    function snow() {
        for (var i = 0; i < snowflakes.length; i++) {
            var snowflake = snowflakes[i];
            context.beginPath();
            context.fillStyle = "rgba(255, 255, 255, 0.7)";
            context.arc(snowflake.x, snowflakes[i].y += snowflake.s / 2, snowflake.s / 2, 0, 2 * Math.PI);
            context.fill();
            if (snowflakes[i].y > h) {
                snowflakes.splice(i, 1);
            }
        };
    };
    setInterval(snowfall, 20);
};
window.onload = init;
</script>