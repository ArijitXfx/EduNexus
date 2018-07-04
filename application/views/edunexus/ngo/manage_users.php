<head>
	<title>Manage Users</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo base_url()?>/resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/Pretty-Footer.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/styles.css">

	<script type="text/javascript" src="<?php echo base_url()?>resources/js/jquery.min.js"></script>
	<script src="<?php echo base_url()?>/resources/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>resources/js/manage_users.js?version=3"></script>
	<script type="text/javascript" src="<?php echo base_url()?>resources/js/SimpleCipher.Strings.js"></script>

</head>
<input type="hidden" id="hidden" value="<?php echo site_url();?>">
<body onload="loadVolunteers();">
	<div id="promo">
        <div class="jumbotron">
            <h1>Welcome to EduNexus</h1>
            <p>Edunexus is a web-based, application that enables teachers to provide study materials for the ngo students without having to be present at the location.</p>
        </div>
    </div>

    <!-- Add Volunteers Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" 
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" 
                       data-dismiss="modal">
                           <span aria-hidden="true">&times;</span>
                           <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        Add A New Volunteer
                    </h4>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">
                  <input type="text" class="form-control" id="name" placeholder="Name"/>
                  <input type="number" class="form-control" id="phoneno" placeholder="Phone Number" />
                  <input type="text" class="form-control" id="address" placeholder="Address"/>
                  <input type="email" class="form-control" id="username" placeholder="Email Id"/>
                  <input type="password" class="form-control" id="password" placeholder="Password"/>
                  <input type="password" class="form-control" id="confirmpassword" placeholder="Confirm Password"/>
                  <div id="modalbuttons">
                    <button id="add-user-btn" onclick="submit();" class="btn btn-success">Add Volunteer</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
            </div>
        </div>
    </div>
        <div class="container">
          <div id="optionButtons">
             <button  class="btn btn-default" type="button" onclick="addUser();"><i class="fa fa-user-plus"></i> Add Volunteer</button>
            <button onclick="location.href ='<?php echo site_url()?>/edunexus/ngomanager/manageCoursesPage'" class="btn btn-default" type="button"><i class="fa fa-folder-open"></i> Manage Courses</button>
            <button onclick="location.href ='<?php echo site_url()?>/edunexus/ngomanager/verifyMaterialsPage'" class="btn btn-default" type="button"><i class="fa fa-book"></i> Verify Material</button>
            <button onclick="location.href ='<?php echo site_url()?>/edunexus/ngomanager/verifyTeachersPage'" class="btn btn-default" tpe="button"><i class="fa fa-drivers-license-o"></i> Verify Teacher</button>
        </div>
        <h1 id="bodyheading">Manage Volunteers</h1>
        <p id="welcomenote">Welcome <?php echo $name?></p>
    
    	<div class="row" id="content">
    		
    	</div>
    	<div id="previousnextbuttons">
            <h6 id="count_text"></h6>
           <button  id="prev" class="previous" onclick="previous();">&laquo; Previous</button>
           <button  id="next" onclick="next();" class="next">Next &raquo;</button> 
        </div>
    </div>
	
</body>
