<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo $this->lang->line('lang_app_name'); ?></title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/bootstrap/css/bootstrap.min.css">
        
        <!-- MetisMenu CSS -->
        <!--<link href="<?php echo base_url();?>assets/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">-->

        <!-- Optional theme -->
        <!--<link rel="stylesheet" href="<?php echo base_url();?>assets/bootstrap/css/bootstrap-theme.min.css">-->

        <!-- Morris Charts CSS -->
        <!--<link href="<?php echo base_url();?>assets/css/plugins/morris.css" rel="stylesheet">-->
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/font-awesome/css/font-awesome.min.css">
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/datepicker.css">
        
        <!-- Select2 CSS -->
        <link href="<?php echo base_url();?>assets/css/plugins/select2/select2.css" rel="stylesheet">
        
        <!-- file CSS -->
        <link href="<?php echo base_url();?>assets/bootstrap-fileinput/css/fileinput.css" rel="stylesheet">
        
        <!-- DataTables CSS -->
        <link href="<?php echo base_url();?>assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

        
        <!-- custome css -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/wanthook_putih.css">
        
        <style>
            .datepicker{z-index:1151 !important;}
        </style>
        
        <style>
        .navbar-default {
            background-color: #5aa383;
            border-color: #429461;
          }
          .navbar-default .navbar-brand {
            color: #ecf0f1;
          }
          .navbar-default .navbar-brand:hover, .navbar-default .navbar-brand:focus {
            color: #047250;
          }
          .navbar-default .navbar-text {
            color: #ecf0f1;
          }
          .navbar-default .navbar-nav > li > a {
            color: #ecf0f1;
          }
          .navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus {
            color: #047250;
          }
          .navbar-default .navbar-nav > li > .dropdown-menu {
            background-color: #5aa383;
          }
          .navbar-default .navbar-nav > li > .dropdown-menu > li > a {
            color: #ecf0f1;
          }
          .navbar-default .navbar-nav > li > .dropdown-menu > li > a:hover,
          .navbar-default .navbar-nav > li > .dropdown-menu > li > a:focus {
            color: #047250;
            background-color: #429461;
          }
          .navbar-default .navbar-nav > li > .dropdown-menu > li > .divider {
            background-color: #5aa383;
          }
          .navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus {
            color: #047250;
            background-color: #429461;
          }
          .navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .open > a:hover, .navbar-default .navbar-nav > .open > a:focus {
            color: #047250;
            background-color: #429461;
          }
          .navbar-default .navbar-toggle {
            border-color: #429461;
          }
          .navbar-default .navbar-toggle:hover, .navbar-default .navbar-toggle:focus {
            background-color: #429461;
          }
          .navbar-default .navbar-toggle .icon-bar {
            background-color: #ecf0f1;
          }
          .navbar-default .navbar-collapse,
          .navbar-default .navbar-form {
            border-color: #ecf0f1;
          }
          .navbar-default .navbar-link {
            color: #ecf0f1;
          }
          .navbar-default .navbar-link:hover {
            color: #047250;
          }

          @media (max-width: 767px) {
            .navbar-default .navbar-nav .open .dropdown-menu > li > a {
              color: #ecf0f1;
            }
            .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover, .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus {
              color: #047250;
            }
            .navbar-default .navbar-nav .open .dropdown-menu > .active > a, .navbar-default .navbar-nav .open .dropdown-menu > .active > a:hover, .navbar-default .navbar-nav .open .dropdown-menu > .active > a:focus {
              color: #047250;
              background-color: #429461;
            }
          }
    </style>
        
        <!--<link rel="stylesheet" href="<?php echo base_url();?>assets/css/reset.css">-->
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="wrapper">

            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">&nbsp;<?php echo $this->lang->line("lang_app_name"); ?></a>
                </div>
                <!-- /.navbar-header -->
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav pull-right">

                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <?php echo $this->lang->line('dash_welcome').",&nbsp;".$this->session->userdata('name'); ?><i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li>
                                    <a href="#" id="changePassword"><i class="fa fa-lock fa-fw"></i> <?php echo $this->lang->line('user_change_password_head'); ?></a>
                                <li>
                                    <a href="<?php echo site_url("Login/logout"); ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                                </li>
                            </ul>
                            <!-- /.dropdown-user -->
                        </li>
                        <!-- /.dropdown -->
                    </ul>
                
                    <ul class="nav navbar-nav navbar-left">
<!--                        <li>
                            <a class="navbar-brand" href="<?php echo site_url("home"); ?>"><span class="glyphicon glyphicon-home"></span>&nbsp;<?php echo $this->lang->line('common_home')?></a>        
                        </li>  -->
                        <?php echo $menu; ?>
                    </ul>
                </div>

                <!-- /.navbar-static-side -->
            </nav>
        </div>
        <div class="mainContent" id="content">
            
        </div>
        <!-- /#wrapper -->