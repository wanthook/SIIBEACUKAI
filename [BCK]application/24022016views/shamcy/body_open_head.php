<body>

<div class="mainwrapper">
    
	<!-- START HEAD -->
    <div class="header">
        <div class="logo">
            <a href="<?php echo site_url("Dashboard"); ?>"><img src="<?php echo base_url();?>assets/images/appLogo.png" alt="" /></a>
        </div>
		
        <!-- START HEAD INNER -->
        <div class="headerinner">
            <!-- START HEAD MENU -->
            <ul class="headmenu">
                <li class="odd"></li>
                <li class="right">
                    <div class="userloggedinfo">
                        <img src="<?php echo base_url();?>assets/images/photos/<?php if($this->session->userdata('photo')){echo $this->session->userdata('photo');}else {echo 'polos.png';} ?>" alt="" />
                        <div class="userinfo">
                            <h5><?php echo $this->session->userdata('name'); ?> <small>- <?php echo $this->session->userdata('type'); ?></small></h5>
                            <ul>
<!--                                <li><a href="editprofile.html">Edit Profile</a></li>
                                <li><a href="">Account Settings</a></li>-->
                                <li><a href="<?php echo site_url("Login/logout"); ?>">Sign Out</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul><!-- END HEAD MENU -->
        </div><!-- END HEAD INNER -->
    </div><!-- END HEAD-->