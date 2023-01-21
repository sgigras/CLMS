<link rel="stylesheet" href="<?= base_url()?>assets/plugins/animation/animate.min.css">
<canvas id="winter-field" style="width: 100vw;
    height: 100vh;
    position: absolute;
    left: 0;
    top: 0;"></canvas>
<style>
.register-card-body1 {
    opacity: 0.9 !important;
}

.error_form p {
    color: red !important;
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
        <!-- <div class="register-logo">
      <h2><a href="<?= base_url('admin'); ?>"></a></h2>
    </div> -->

        <div class="card register-card-body1">
            <div class="card-body register-card-body">
                <div class="row">
                    <div class="col-12 ">
                        <!-- <p class="login-box-msg">Register</p> -->
                        <center>
                            <h2 class="login-label">Set Your Account PIN</h2>
                        </center>

                        <?php
            if (null!=($this->session->flashdata('errors'))) {


              $this->load->view('admin/includes/_messages.php');
            }
            ?>
                        <?php $validation = $this->session->flashdata('error'); ?>

                        <?php echo form_open(base_url('admin/auth/setpassword'), 'class="login-form" '); ?>

                        <br>
                        <div class="form-group has-feedback">

                            <!-- <label>Create 4 Digit PIN For Your Account</label> -->
                            <?php $sessionpin= $this->session->flashdata('pin'); ?>
                            <!-- <input type="password" maxlength="4" name="pin" id="pin" password_numeric_field class="form-control login-fields" placeholder="Set PIN"  value=""> -->
                            <?php  $this->load->view('master/password_numeric_field',array("max_length"=>"4","field_id"=>"pin","place_holder"=>"Create Pin","value"=>$sessionpin,"label"=>"Create 4 Digit PIN")); ?>
                            <small class="error_form"
                                id="pin_error"><?php echo isset($validation) ? $validation['pin'] : "" ?></small>
                        </div>
                        <div class="form-group has-feedback">

                            <!-- <label>Create 4 Digit PIN For Your Account</label> -->
                            <?php $sessionpin= $this->session->flashdata('pin'); ?>
                            <!-- <input type="password" maxlength="4" name="pin" id="pin" password_numeric_field class="form-control login-fields" placeholder="Set PIN"  value=""> -->
                            <?php  $this->load->view('master/numeric_field',array("max_length"=>"4","field_id"=>"confirm_pin","place_holder"=>"Confirm Pin","value"=>$sessionpin,"label"=>"Confirm PIN")); ?>
                            <small class="error_form"
                                id="confirm_pin_error"><?php echo isset($validation) ? $validation['confirm_pin'] : "" ?></small>
                        </div>
                        <br>
                        <center> <button id="submit" name="submit" type="submit" class="btn login-button"
                                value="register">Submit</button></center>
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