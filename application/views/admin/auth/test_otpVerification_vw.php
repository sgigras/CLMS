<link rel="stylesheet" href="<?= base_url()?>assets/plugins/animation/animate.min.css">
<div class="form-background">
  <div class="login-box">
    <div class="login-logo">
      <h2><a href="<?//= base_url('admin'); ?>"></a></h2>
    </div>
    <!-- /.login-logo -->
    <div class="card login-card-body1 animate__animated animate__zoomIn">
      <div class="card-body login-card-body" style="margin-right :100px">
        <div class="row">

          <div class="col-12 login-details">

            <center>
              <h2 class="login-label">Verify OTP</h2>
            </center>

            <?php
            // if (null!=($this->session->flashdata('errors'))) {


            //   $this->load->view('admin/includes/_messages.php');
            // }

            // if (null!=($this->session->flashdata('success'))) {


            //   $this->load->view('admin/includes/_messages.php');
            // }
            ?>
            <?php $validation = $this->session->flashdata('error');


             ?>

            <?php
          
          echo form_open(base_url('admin/Forgot_passwordAPI/verifyotp'), array('class' => 'login-form', 'id' => 'verify_otp'));
          ?>

            <div class="form-group has-feedback">
             <div id="otpdiv">
              <label for="otp"><?=trans('enter_otp') ?></label>
              <input type="password" class="form-control" id="otp"  name = "otp" placeholder="Enter OTP" maxlength="4" value = "<?php echo $this->session->flashdata('otp'); ?>">
<!--               <small id="otperror" style="color: red" class="login_error_message"></small> -->
              <?php echo isset($validation) ? $validation['otp'] : ""?>
             </div>

          </div>
          <center>
            <button id="submit" type="submit" class="btn login-button" ><?=trans('verify_otp') ?></button>
          </center> 
                
                         

                         
          <?php echo form_close(); ?>

        </div>
      </div>
    </div>

    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
</div>
<script type="text/javascript">

$('#verify_otp').submit(function(){
  event.preventDefault();

});
</script>


