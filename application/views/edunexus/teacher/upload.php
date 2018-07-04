<head>
	<title>Upload Material</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo base_url()?>/resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/Pretty-Footer.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/styles.css">

	<script type="text/javascript" src="<?php echo base_url()?>resources/js/jquery.min.js"></script>
	<script src="<?php echo base_url()?>/resources/bootstrap/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url()?>/resources/js/upload_materials.js?version=3"></script>
	<script type="text/javascript" src="<?php echo base_url()?>resources/js/SimpleCipher.Strings.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>/resources/tinyjs/tinymce/tinymce.min.js""></script>
  	<script >
	    tinyMCE.init({
	      selector: '#description',
	      plugins: "autoresize image lists advlist link searchreplace table textcolor",
	      toolbar: 'undo redo |  styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | numlist bullist | indent outdent | link image table',
	      branding: false,
	      default_link_target: "_blank",
	       
	    });
	    $(document).on('focusin', function(e) {
	  		if ($(e.target).closest(".mce-window").length) {
	    		e.stopImmediatePropagation();
	  		}	
		});
  </script>
</head>
<input type="hidden" id="hidden" value="<?php echo site_url();?>">

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
                    <button onclick="loadRequiredCourses();" class="btn btn-success">Filter</button>
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Add Course Modal -->
    <div class="modal fade" id="courseModal" tabindex="-1" role="dialog" 
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
                        Course Modal
                    </h4>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">
                  <input type="text"  class="form-control" id="title" placeholder="Course Title" readonly/>
                  <textarea  rows="3" id="description" placeholder="Description"></textarea>
                  <input type="text" class="form-control" id="subject" placeholder="Subject" readonly/>  
                  <input type="text" class="form-control" id="standard" placeholder="Standard" readonly/>   
                  <input type="text" class="form-control" id="language" placeholder="Language" readonly/> 
                  <input type="text" class="form-control" id="board" placeholder="Board" readonly/> 
                  <input type="text" class="form-control" id="video" placeholder="Youtube video link"/>
                  <label for="fileToUpload">Upload QABank PDF </label>
                  <input type="file" id="qabank" accept="application/pdf">
                  <div id="modalbuttons">
                    <button onclick="submit();" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Requirement Modal -->
     <div class="modal fade" id="requirementModal" tabindex="-1" role="dialog" 
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
                        Course Requirement
                    </h4>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">
                	<div id="show-requirement">
                		
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
          <button onclick="location.href ='<?php echo site_url()?>/edunexus/teachermanager/showMyCourses'" class="btn btn-default" type="button"><i class="fa fa-folder-open"></i> My Courses</button>
          <button class="btn btn-primary" id="filterbtn" data-target="#filterModal" data-toggle="modal"><i class="fa fa-gear"></i>
         Filter</button>
         <button class="btn btn-default" onclick="loadRequiredCourses();" id="searchbtn"><i class="fa fa-search"></i></button>
          <input type="text" id="keyword" class="form-control" placeholder="Search">
        </div>
        <h1 id="bodyheading">Upload Course Materials</h1>
        <p id="welcomenote">Welcome <?php echo $name;?></p>
        <div class="row" id="content"></div>
        <div id="previousnextbuttons">
            <h6 id="count_text"></h6>
           <button  id="prev" class="previous" onclick="previous();">&laquo; Previous</button>
           <button  id="next" onclick="next();" class="next">Next &raquo;</button> 
        </div>
    </div>

	<!-- <h1>Upload Materials</h1>
	<div id="form-div">
		Title: <input type="text" id="title"><br> -->
		<!-- Requirement: <input type="text" id="requirement"><br>
		Keyword: <input type="text" id="keyword"><br> -->
		<!-- Subject: <input type="text" id="subject"><br> -->
		<!-- Standard: <input type="text" id="standard"><br> -->
		<!-- Chapter: <input type="text" id="chapter"><br> -->
		<!-- Language: <input type="text" id="language"><br> -->
		<!-- Board: <input type="text" id="board"><br> -->
		<!-- Description: <input type="text" id="description"><br> -->
		<!-- Video Link: <input type="text" id="video"><br>
		QA Bank: <input type="file" id="qabank"><br>
		<button  onclick="submit();">Submit</button>
	</div>
	<div>
		<div id="content">		
		</div>
		<div>
				<h6 id="count_text"></h6>
		</div>
		<div>
				<button id="prev" onclick="previous();">Previous</button>
				<button id="next" onclick="next();">Next</button>
		</div>
	</div> -->
</body>