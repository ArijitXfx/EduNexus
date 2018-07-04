<head>
	<title>Display Course</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo base_url()?>/resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/Pretty-Footer.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/styles.css">

	<script type="text/javascript" src="<?php echo base_url()?>resources/js/jquery.min.js"></script>
	<script src="<?php echo base_url()?>/resources/bootstrap/js/bootstrap.min.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url()?>/resources/js/display_course.js?version=1"></script>
	<script type="text/javascript" src="<?php echo base_url()?>resources/js/SimpleCipher.Strings.js"></script>

</head>
<input type="hidden" id="hidden" value="<?php echo  site_url()?>">
<input type="hidden" id="base" value="<?php echo  base_url()?>">
<input type="hidden" id="hidden-user" value="<?php echo $usertype?>">


<body onload="loadCourse();">
 <div class="container video-section">
        <div >
            <div class="video-header" id="video-player-header">
                <h3 id="title">Pythagoras Theorem</h3>
            </div>
            <iframe id="video-player" width="800" height="510" frameborder="0" allowfullscreen></iframe>
            <div class="video-course-content">
                 <div class="video-header thumbnail-header">
                    <h3>Course Details</h3>
                </div>
                <div class="thumbnail thumbnail-details">
                    <p id="title"></p>
                    <p id="name"></p>
                    <p id="board"></p>
                    <p id="standard"></p>
                    <p id="language"></p>
                    <p id="subject"></p>
                </div>

                 <div class="video-header thumbnail-header">
                    <h3>Author Details</h3>
                </div>
                <div class="thumbnail thumbnail-details">                 
                    <p id="author"> </p>
                    <p id="phoneno"> </p>
                    <p id="username"> </p>
          
                </div>
                
                <div id="video-option-buttons">
                    <a class="btn btn-success btn-block" id="qabank" href="" download>Download Question and Answer Bank</a>
                    <a class="btn btn-success btn-block" onclick="accessPortal();" id="forum-btn"> Discussion Forum</a>
                </div>

            </div>
            
            <div id="video-course-description">
                <h3>Description</h3>
                <p id="description">
                </p>
            </div>

        </div>
    </div>
</body>
