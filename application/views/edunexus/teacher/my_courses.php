<head>
	<title>My Courses</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo base_url()?>/resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/Pretty-Footer.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/styles.css">

	<script type="text/javascript" src="<?php echo base_url()?>resources/js/jquery.min.js"></script>
	<script src="<?php echo base_url()?>/resources/bootstrap/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url()?>resources/js/my_courses.js?version=1"></script>
	<script type="text/javascript" src="<?php echo base_url()?>resources/js/SimpleCipher.Strings.js"></script>
	
</head>
<input type="hidden" id="hidden" value="<?php echo base_url();?>">
<input type="hidden" id="hidden1" value="<?php echo site_url();?>">
<body onload="load();">
	<div id="promo">
        <div class="jumbotron">
            <h1>Welcome to EduNexus</h1>
            <p>Edunexus is a web-based, application that enables teachers to provide study materials for the ngo students without having to be present at the location.</p>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" 
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
                        Add Filters
                    </h4>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">
                  <select class="form-control" id="board-filter">
                    <option value="none">All Board</option>
              </select>
              <select class="form-control" id="standard-filter">
                <option value="none">All Standard</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
              </select>
              <select class="form-control" id="subject-filter">
                <option value="none">All Subject</option>
              </select>
              <select class="form-control" id="language-filter">
                <option value="none">All Language</option>
              </select>
                  <div id="modalbuttons">
                    <button onclick="loadCourses();" class="btn btn-success">Filter</button>
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
            </div>
        </div>
    </div>

     <!-- Add Description Modal -->
     <div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" 
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
                        Course Description
                    </h4>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">
                	<div id="show-description">
                		
                	</div>
                  <div id="modalbuttons">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div id="optionButtons">
            <button onclick="location.href ='<?php echo site_url()?>/edunexus/teachermanager/showUploadPage'" class="btn btn-default" tpe="button"><i class="fa fa-upload"></i> Upload Materials</button>
            <button class="btn btn-primary" id="filterbtn" data-target="#filterModal" data-toggle="modal"><i class="fa fa-gear"></i>
         Filter</button>
         <button class="btn btn-default" onclick="loadCourses();" id="searchbtn"><i class="fa fa-search"></i></button>
          <input type="text" id="keyword" class="form-control" placeholder="Search">
        </div>
    	<h1 id="bodyheading">My Courses</h1>
        <p id="welcomenote">Welcome <?php echo $name;?></p>

        <ul class="nav nav-tabs" >
            <li class="active"><a onclick="showVerifiedCourses(true);" data-toggle="tab" href="#verified">Verified Courses</a></li>
            <li><a onclick="showVerifiedCourses(false);" data-toggle="tab" href="#nonverified">Non Verified Courses</a></li>
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
