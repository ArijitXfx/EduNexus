<head>
	<title>Verify Teachers</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo base_url()?>/resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/Pretty-Footer.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/styles.css">

	<script type="text/javascript" src="<?php echo base_url()?>resources/js/jquery.min.js"></script>
	<script src="<?php echo base_url()?>/resources/bootstrap/js/bootstrap.min.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url()?>/resources/js/verify_teachers.js?version=1"></script>
	<script type="text/javascript" src="<?php echo base_url()?>resources/js/SimpleCipher.Strings.js"></script>
</head>
<input type="hidden" id="hidden" value="<?php echo base_url();?>"/>
<input type="hidden" id="hidden1" value="<?php echo site_url();?>">
	
<body onload="showVerifiedTeachers(true);">
	<div id="promo">
        <div class="jumbotron">
            <h1>Welcome to EduNexus</h1>
            <p>Edunexus is a web-based, application that enables teachers to provide study materials for the ngo students without having to be present at the location.</p>
        </div>
    </div>

    <div class="container">
          <div id="optionButtons">
            <button onclick="location.href ='<?php echo site_url()?>/edunexus/ngomanager/manageVolunteersPage'" class="btn btn-default" type="button"><i class="fa fa-user-circle"></i> Manage Volunteers</button>
            <button onclick="location.href ='<?php echo site_url()?>/edunexus/ngomanager/manageCoursesPage'" class="btn btn-default" type="button"><i class="fa fa-folder-open"></i> Manage Courses</button>
            <button onclick="location.href ='<?php echo site_url()?>/edunexus/ngomanager/verifyMaterialsPage'" class="btn btn-default" type="button"><i class="fa fa-book"></i> Verify Material</button>
            
        </div>
    	<h1 id="bodyheading">Verify Teachers</h1>
        <p id="welcomenote">Welcome <?php echo $name;?></p>

        <ul class="nav nav-tabs" >
            <li class="active"><a onclick="showVerifiedTeachers(true);" data-toggle="tab" href="#verified">Verified Teachers</a></li>
            <li><a onclick="showVerifiedTeachers(false);" data-toggle="tab" href="#nonverified">Non Verified Teachers</a></li>
        </ul>

        <div class="tab-content">
            <div id="verified" class="tab-pane fade in active">
            	<div class="row" id="verified-content"></div>
            </div>
            <div id="nonverified" class="tab-pane fade">
              	<div class="row" id="non-verified-content"></div>
        	</div>
        </div>
        <div id="previousnextbuttons">
            <h6 id="count_text"></h6>
           <button  id="prev" class="previous" onclick="previous();">&laquo; Previous</button>
           <button  id="next" onclick="next();" class="next">Next &raquo;</button> 
        </div>
    </div>
</body>
