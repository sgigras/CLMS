<link rel="stylesheet" href="<?= base_url()?>assets/plugins/animation/animate.min.css">
<div class="form-background">
  <div class="login-box">
    <div class="login-logo">
      <h2><a href="<?= base_url('admin'); ?>"></a></h2>
    </div>
    <!-- /.login-logo -->
    <div class="card login-card-body1 animate__animated animate__zoomIn">
      <div class="card-body login-card-body">
        <div class="row">
          
          <div class="col-12 login-details">
           
            <center>
              <h2 class="login-label">Verify User</h2>
            </center>

                       <?php $validation = $this->session->flashdata('error'); ?>

            <?php

          echo form_open(base_url('admin/Forgot_passwordAPI/verifyUser'), array('class' => 'login-form', 'id' => 'user_verify'));

          ?>

            <div class="form-group has-feedback">
              <label for="username"><?=trans('user_name') ?></label>
                <input type="text" class="form-control login-fields" id="username" placeholder="Enter Username" name="username" value="<?php echo $this->session->flashdata('username'); ?>">
                <?php echo isset($validation) ? $validation['username'] : "" ?>
                         
              </div>

            
<!--             <center> <button id="usernamebtn" class="btn login-button" ><?//=trans('verify_uer') ?></button></center>
 -->            <center><button id="submit" name="submit"  value="submit"  class="btn login-button" ><?=trans('verify_uer') ?></button></center>
            
            <?php echo form_close(); ?>
         
                     </div>
        </div>
      </div>

      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->
</div>
<!-- <script>
        var csrfName = '<?php// echo $this->security->get_csrf_token_name(); ?>';
        var csrfHash = '<?php// echo $this->security->get_csrf_hash(); ?>';
        var baseurl = "<?php// echo base_url(); ?>";
      </script> -->
      <!--</div>-->
      <script src="<?= base_url() ?>assets/js/module/common/validation.js"></script>
      <!-- <script src="<?//= base_url() ?>assets/js/module/password_forgot.js"></script> -->

