<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= isset($title) ? $title . ' - ' : 'Title -' ?> <?= $this->general_settings['application_name']; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/enplugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <!--<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/adminlte.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- DropZone -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/dropzone/dropzone.css">
    <!-- Google Font: Source Sans Pro -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
    <!-- Select2 -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/select2/select2.min.css">
    <!--datatable-->
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css">
    <!-- sweet alert -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/sweetalert/sweetalert2.min.css">
    <!--core-style-->
    <link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/core-style.css">
    <!--custom created-style-->
    <link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/style.css">
    <!--animate.css-->
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/animation/animate.min.css">

    <!-- jQuery -->


    <!-- jQuery -->
    <script src="<?= base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/plugins/jquery/jquery.numeric.js"></script>
    <script src="<?= base_url() ?>assets/plugins/select2/select2.full.min.js"></script>
    <script src="<?= base_url() ?>assets/plugins/sweetalert/sweetalert2.all.min.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>assets/plugins/sweetalert/sweetalert2.min.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>assets/plugins/sweetalert/sweetalert.min.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>assets/js/module/common/date.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>assets/js/module/common/common_function.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>assets/js/module/common/validation.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>assets/js/module/common/input_masking.js" type="text/javascript"></script>

    <script>
        const DOMAIN = "<?= base_url(); ?>"
    </script>
    <style>
        .select2-selection__rendered {
            line-height: 31px !important;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        .select2-selection__arrow {
            height: 37px !important;
        }

        .select2-invalid-border {
            /*height: 37px !important;*/
            border-color: #dc3545 !important;
        }


        /*            .select2-container .select2-selection--single in{
                            height: 38px !important;
                        }*/

        /*            button{
                            border-radius: 7px;
                        }*/
    </style>
</head>

<body class="hold-transition sidebar-mini <?= (isset($bg_cover)) ? 'bg-cover' : '' ?> p-0">

    <!-- Main Wrapper Start -->
    <div class="wrapper">

        <!-- Navbar -->

        <?php if (!isset($navbar)) : ?>

            <nav class="main-header navbar navbar-expand bg-red navbar-denger border-bottom navbar-dark bg-danger" style="flex: 1;width: -webkit-fill-available;">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                    </li>



                </ul>

                <!-- SEARCH FORM -->
                <form class="form-inline ml-3">
                    <div class="input-group input-group-sm">

                    </div>
                </form>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <!-- Languages -->
                    <?php $languages = get_language_list(); ?>
                    <li class="nav-item dropdown">

                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <?php foreach ($languages as $lang) : ?>
                                <a href="<?= base_url('home/site_lang/' . $lang['id']) ?>" class="dropdown-item">
                                    <i class="fa fa-flag mr-2"></i> <?= $lang['name'] ?>
                                </a>
                                <div class="dropdown-divider"></div>
                            <?php endforeach; ?>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i class="fa fa-th-large"></i></a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="<?= base_url('admin/auth/logout') ?>" class="nav-link"><?= trans('logout') ?></a>
                    </li>
                </ul>
            </nav>

        <?php endif; ?>

        <!-- /.navbar -->


        <!-- Sideabr -->

        <?php if (!isset($sidebar)) : ?>

            <?php $this->load->view('admin/includes/_sidebar'); ?>

        <?php endif; ?>

        <!-- / .Sideabr -->
        <?php
        if (isset($title)) {
            if ($title !== 'Login' && $title !== 'Register' && $title !== 'Verify OTP' && $title !== 'Enter Pin' &&  $title !== 'Forget Password') {
                echo '<div class="content-wrapper" style="min-height: 580.08px !important;">';
            }
        } else {
            echo '<div class="content-wrapper" style="min-height: 580.08px !important;">';
        }
        ?>
