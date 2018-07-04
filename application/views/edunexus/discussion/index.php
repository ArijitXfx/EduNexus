
<head>
  <title>Discussion Portal</title>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/Pretty-Footer.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/styles.css">

  <script type="text/javascript" src="<?php echo base_url()?>resources/js/jquery.min.js"></script>
  <script src="<?php echo base_url()?>/resources/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url()?>resources/js/discussion_index.js?version=1"></script>
  <script type="text/javascript" src="<?php echo base_url()?>/resources/js/SimpleCipher.Strings.js"></script>
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
<input type="hidden" id="usertype" value="<?php echo $usertype;?>">


<body  onload="load();">
  <div id="promo">
        <div class="jumbotron">
            <h1>Welcome to EduNexus</h1>
            <p>Edunexus is a web-based, application that enables teachers to provide study materials for the ngo students without having to be present at the location.</p>
        </div>
    </div>

    <!-- Add Duplicate Link Modal -->
    <div class="modal fade" id="addDuplicateModal" tabindex="-1" role="dialog" 
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
                        Add your video link
                    </h4>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">
                  <input type="text" class="form-control" id="addlink" placeholder="Add Link" />
                  <div id="modalbuttons">
                    <button type="submit" class="btn btn-success" onclick="setDuplicate();">Add Duplicate</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Create Thread Modal -->
    <div class="modal fade" id="createThreadModal" tabindex="-1" role="dialog" 
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
                        Create A Thread
                    </h4>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">
                  <form method="post" action="DiscussionPortalManager/createDiscussion">
                    <input type="text" class="form-control" id="title" placeholder="Title" name="title" />
                    <textarea class="form-control" id="description" placeholder="Description" name="description"></textarea>
                    <div id="modalbuttons">
                      <button onclick="postThread();" class="btn btn-success">Submit</button>
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
      <?php if($this->session->userdata('user_details')->getUsertype() != 4){ ?>
        <div id="optionButtons">
            <button id="threadButton" class="btn btn-default" data-toggle="modal" data-target="#createThreadModal">
                <i class="fa fa-pencil"> Ask A Question</i>
            </button>
            <!-- <button class="btn btn-default" onclick="loadQuestion();" id="searchbtn"><i class="fa fa-search"></i></button>
          <input type="text" id="keyword" class="form-control" placeholder="Search"> -->
        </div>
        <?php } ?>
        <h1 id="bodyheading">Discussion Portal</h1>
        <p id="welcomenote">Welcome <?php echo $name;?></p>
        <div id="searchButtons">
        <button class="btn btn-default" onclick="loadQuestion();" id="searchbtn"><i class="fa fa-search"></i></button>
          <input type="text" id="keyword" class="form-control" placeholder="Search">
      </div>
        <div class="row row-question" id="question">

        </div>
        <div id="previousnextbuttons">
            <h6 id="count_text"></h6>
           <button  id="prev" class="previous" onclick="previous();">&laquo; Previous</button>
           <button  id="next" onclick="next();" class="next">Next &raquo;</button> 
        </div>
    </div>
</body>
