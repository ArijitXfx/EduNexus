<html>
<link rel="shortcut icon"  href="<?php echo base_url()?>/resources/images/logo.png ?>">
<center><h1>Welcome to NGO EDU NEXUS</h1></center>
    <nav class="navbar navbar-default navigation-clean-button navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">
                <img src="<?php echo base_url()?>/resources/images/logo.png ?>"  id="logo">EduNexus</a><button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button></div>
                <div class="dropdown navbar-right actions">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="userProfile" data-toggle="dropdown"> 
                        <i class="fa fa-user-circle-o"></i>
                        <span class="caret"></span>
                    </button>

                    <ul class="dropdown-menu">
                        <li><a href="<?php echo site_url().'/edunexus/LoginManager';?>" " ><i class="fa fa-home"></i> Home</a></li>
                        <li><a href="<?php echo site_url().'/edunexus/LoginManager/settingsPanel'; ?>" ><i class="fa fa-gear"></i> Settings</a></li>
                        <li><a href="<?php echo site_url().'/edunexus/LoginManager/logout';?>" ><i class="fa fa-ban"></i> Log Out</a></li>
                    </ul>
                </div>
            </div>
    </nav>