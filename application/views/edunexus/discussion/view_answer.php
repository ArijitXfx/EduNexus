<head>
	<title>Discussion Thread</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo base_url()?>/resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/fonts/font-awesome.min.css">
<!--     <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie"> -->
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/Pretty-Footer.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/styles.css">

	<script type="text/javascript" src="<?php echo base_url()?>resources/js/jquery.min.js"></script>
	<script src="<?php echo base_url()?>/resources/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>resources/js/answer_thread.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>resources/js/SimpleCipher.Strings.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>/resources/tinyjs/tinymce/tinymce.min.js""></script>
	<script >
	    tinyMCE.init({
	      selector: '#writeAnswer',
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
<input type="hidden" id="discussion_id" value="<?php echo $discussion_id?>">
<body onload="loadQuestionAndAnswer();">

	 <!-- Add Write Answer Modal -->
    <div class="modal fade" id="writeAnswerModal" tabindex="-1" role="dialog" 
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
                        Write Your Answer
                    </h4>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">
                  <textarea class="form-control" id="writeAnswer" placeholder="Write Your Answer"></textarea>
                  <div id="modalbuttons">
                    <button onclick="submitAnswer();" type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
            </div>
        </div>
    </div>

	<div class="container" id="discussion_thread_container">
		<div id="question">
		
		</div>
		<?php
            $userType = $this->session->userdata('user_details')->getUserType();
            if($userType != 4)
                echo '<button type="button" class="btn btn-default" aria-label="Left Align" data-target="#writeAnswerModal" onclick="writeAnswer();"><i class="fa fa-pencil"> Reply</i>';
            else
                echo '<button type="button" class="btn btn-default" aria-label="Left Align" data-target="#writeAnswerModal" onclick="writeAnswer();"><i class="fa fa-pencil"> Write Answer</i>';
        ?>
	    </button>

		<div class="row" id="answers">
			
		</div>
	</div>

	<div id="previousnextbuttons">
        <h6 id="count_text"></h6>
        <button  id="prev" class="previous" onclick="previous();">&laquo; Previous</button>
        <button  id="next" onclick="next();" class="next">Next &raquo;</button> 
    </div>
</body>
</html>