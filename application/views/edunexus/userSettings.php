
<head>
	<link rel="stylesheet" href="<?php echo base_url()?>/resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/Pretty-Footer.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/styles.css">
  <script type="text/javascript" src="<?php echo base_url()?>resources/js/user_settings.js?version=2"></script>
  <script type="text/javascript" src="<?php echo base_url()?>resources/js/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url()?>/resources/js/SimpleCipher.Strings.js"></script>
  <script src="<?php echo base_url()?>/resources/bootstrap/js/bootstrap.min.js"></script>
</head>
<body onload="load();" >
<input type="hidden" id="hidden" value="<?php echo site_url()?>">
<input type="hidden" id="hidden1" value="<?php echo base_url()?>">
<input type="hidden" id="usertype" value="<?php echo $usertype;?>">
<div id="promo">
        <div class="jumbotron">
            <h1>Welcome to EduNexus</h1>
            <p>Edunexus is a web-based, application that enables teachers to provide study materials for the ngo students without having to be present at the location.</p>
        </div>
    </div>
<div id="setting-form-container">
       <div class="container form-container">
            <p class="setting-form-title">Update User Details</p>
           <div class="thumbnail thumbnail-form-settings">
                <label for="name">Name</label>
               <input type="text" class="form-control" id="name">

               <div id="details-div">
               <label for="phoneno">Phone Number</label>
               <input type="number" class="form-control" id="phoneno">

               <label for="address">Address</label>
               <input type="text" id="address" class="form-control"/> 
               </div>
               <label for="username">Email</label>
               <input type="text" class="form-control" id="username" readonly>

               <div id="qualification-div">
                <p style="margin-top: 20px;"><a href="" download id="qualification-link">Download your resume</a></p>

                <label for="qualification">Upload new resume in pdf</label>
                <input type="file" id="qualification">
  
               </div>
               
               <button class="btn btn-success" onclick="updateDetails();">Update Details</button>
               <p><span style="color: #ff0000" id="errorMsgUserDetails" ></span></p>
           </div>
       </div>

       <div class="container form-container">
            <p class="setting-form-title">Change Password</p>
           <div class="thumbnail thumbnail-form-settings">
               <label for="name">Current Password</label>
               <input type="password" class="form-control" id="curpassword">
               <label for="newpassword">New Password</label>
               <input type="password" class="form-control" id="newpassword">
               <label for="password">Confirm Password</label>
               <input type="password" class="form-control" id="connewpassword">
               <button class="btn btn-success" onclick="changePassword();";">Change Password</button>
               <p><span style="color: #ff0000" id="errorMsgUserDetails" ></span></p>
           </div>
       </div>

       <div class="container form-container">
            <p class="setting-form-title">Delete Your Account</p>
           <div class="thumbnail thumbnail-form-settings">
                <label for="name">The following option will delete your account permanently. Please be notified that you cannot use the same email id for re-registration!</label>
                <form action="<?php echo site_url() ?>/edunexus/LoginManager/deleteMyAccount" method="post" onsubmit="return tryDelete();">
                  <button class="btn btn-danger" type="submit">Delete Account</button>
                </form>
           </div>
       </div>
   </div>
</body>
