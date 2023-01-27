<?php 
$cur_tab = $this->uri->segment(2)==''?'dashboard': $this->uri->segment(2);  
?>  
<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 sidebar-light-danger">
  <!-- Brand Logo -->
  <a href="<?= base_url('admin'); ?>" class="brand-link">
     <img width="180" src="<?= base_url()?>assets/dist/img/BSFlogonav.png">
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?php echo ($this->session->has_userdata('profile_picture') && $this->session->userdata('profile_picture')!==NULL && $this->session->userdata('profile_picture')!=='') ? base_url(). $this->session->userdata('profile_picture'): base_url().'assets/dist/img/users.png' ?>" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <span href="#" class="d-block"><?= ucwords($this->session->userdata('full_name')); ?></span>
        <a class="d-block" style="font-size: 15px;"><?= ucwords($this->session->userdata('admin_role')); ?></a>
      </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <?php
        $menu = get_sidebar_menu();
            foreach ($menu as $nav)
            {
                extract($nav);
                $fa_icon = isset($fa_icon) ? $fa_icon : "";
        ?>
                <li id="<?= $controller_name ?>" class="nav-item <?= ($has_submenu == 1) ? 'has-treeview' : '' ?> has-treeview">
                  <a href="<?= base_url($controller_name) ?>" class="nav-link">
                    <i class="nav-icon <?= $fa_icon ?>"></i>
                    <p>
                      <?= trans($module_name) ?>
                      <?= ($has_submenu == 1) ? '<i class="right fa fa-angle-left"></i>' : '' ?>
                    </p>
                  </a>
                  <?php 
                    if ($has_submenu == 1){
                      $snavlist = explode(',', $subnav);
                  ?>
                    <ul class="nav nav-treeview">
                      <?php 
                        foreach ($snavlist as $sub_nav) {
                          $snav = explode("#", $sub_nav);
                      ?>
                      <li class="nav-item">
                        <a href="<?= base_url($controller_name . '/' . $snav[2]); ?>" class="nav-link">
                          <i class="fa fa-circle-o nav-icon"></i>
                          <p><?= trans($snav[1]) ?></p>
                        </a>
                      </li>
                      <?php } ?>                    
                    </ul>
                  <?php }?>
                </li>
        <?php 
            } 
        ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
<script>
  $("#<?= $cur_tab ?>").addClass('menu-open');
  $("#<?= $cur_tab ?> > a").addClass('active');
</script>